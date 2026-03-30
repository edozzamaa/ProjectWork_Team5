<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\ICodificaOERepository;
use src\Domain\Models\CodificaOE;
use src\Domain\ValueObjects\CodificaOE\CodificaOEId;
use src\Domain\ValueObjects\CodificaOE\CodificaOEDescrizione;
use src\Domain\ValueObjects\Fornitore\FornitoreId;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class CodificaOERepository implements ICodificaOERepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    public function findByCod(CodificaOEId $codOE): ?CodificaOE {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codOE, descrizione, ragSoc', 'CODIFICA_OE')
            . $this->queryBuilder->where('codOE = ?');

        $stmt = $connection->prepare($query);
        $codOEStr = (string) $codOE;
        $stmt->bind_param('s', $codOEStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return CodificaOE::reconstituteFromDatabase(
            new CodificaOEId((string) $row['codOE']),
            new CodificaOEDescrizione((string) $row['descrizione']),
            $row['ragSoc'] !== null ? new FornitoreId((string) $row['ragSoc']) : null
        );
    }

    /** @return CodificaOE[] */
    public function findAll(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codOE, descrizione, ragSoc', 'CODIFICA_OE');

        $result = $connection->query($query);
        $codifiche = [];

        while ($row = $result->fetch_assoc()) {
            $codifiche[] = CodificaOE::reconstituteFromDatabase(
                new CodificaOEId((string) $row['codOE']),
                new CodificaOEDescrizione((string) $row['descrizione']),
                $row['ragSoc'] !== null ? new FornitoreId((string) $row['ragSoc']) : null
            );
        }

        return $codifiche;
    }

    /** @return CodificaOE[] */
    public function findByFornitore(FornitoreId $ragSoc): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codOE, descrizione, ragSoc', 'CODIFICA_OE')
            . $this->queryBuilder->where('ragSoc = ?');

        $stmt = $connection->prepare($query);
        $ragSocStr = (string) $ragSoc;
        $stmt->bind_param('s', $ragSocStr);
        $stmt->execute();
        $result = $stmt->get_result();
        $codifiche = [];

        while ($row = $result->fetch_assoc()) {
            $codifiche[] = CodificaOE::reconstituteFromDatabase(
                new CodificaOEId((string) $row['codOE']),
                new CodificaOEDescrizione((string) $row['descrizione']),
                $row['ragSoc'] !== null ? new FornitoreId((string) $row['ragSoc']) : null
            );
        }

        return $codifiche;
    }

    public function save(CodificaOE $codifica): void {
        $connection = $this->databaseConnector->getConnection();
        $codOE = (string) $codifica->getCodOE();
        $descrizione = $codifica->getDescrizione()->value;
        $ragSoc = $codifica->getRagSoc() !== null ? (string) $codifica->getRagSoc() : null;

        $existing = $this->findByCod($codifica->getCodOE());

        if ($existing !== null) {
            $query = $this->queryBuilder->updatePrepared('CODIFICA_OE', ['descrizione', 'ragSoc'], 'codOE = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $descrizione, $ragSoc, $codOE);
        } else {
            $query = $this->queryBuilder->insertPrepared('CODIFICA_OE', ['codOE', 'descrizione', 'ragSoc']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $codOE, $descrizione, $ragSoc);
        }

        $stmt->execute();
    }

    public function delete(CodificaOEId $codOE): void {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->delete('CODIFICA_OE', 'codOE = ?');

        $stmt = $connection->prepare($query);
        $codOEStr = (string) $codOE;
        $stmt->bind_param('s', $codOEStr);
        $stmt->execute();
    }
}
