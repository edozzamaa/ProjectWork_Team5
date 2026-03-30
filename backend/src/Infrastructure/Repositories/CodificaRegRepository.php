<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\ICodificaRegRepository;
use src\Domain\Models\CodificaReg;
use src\Domain\ValueObjects\CodificaReg\CodificaRegId;
use src\Domain\ValueObjects\CodificaReg\CodificaRegDescrizione;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class CodificaRegRepository implements ICodificaRegRepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    public function findByCod(CodificaRegId $codReg): ?CodificaReg {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codReg, descrizione', 'CODIFICA_REG')
            . $this->queryBuilder->where('codReg = ?');

        $stmt = $connection->prepare($query);
        $codRegStr = (string) $codReg;
        $stmt->bind_param('s', $codRegStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return CodificaReg::reconstituteFromDatabase(
            new CodificaRegId((string) $row['codReg']),
            new CodificaRegDescrizione((string) $row['descrizione'])
        );
    }

    /** @return CodificaReg[] */
    public function findAll(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codReg, descrizione', 'CODIFICA_REG');

        $result = $connection->query($query);
        $codifiche = [];

        while ($row = $result->fetch_assoc()) {
            $codifiche[] = CodificaReg::reconstituteFromDatabase(
                new CodificaRegId((string) $row['codReg']),
                new CodificaRegDescrizione((string) $row['descrizione'])
            );
        }

        return $codifiche;
    }

    public function save(CodificaReg $codifica): void {
        $connection = $this->databaseConnector->getConnection();
        $codReg = (string) $codifica->getCodReg();
        $descrizione = $codifica->getDescrizione()->value;

        $existing = $this->findByCod($codifica->getCodReg());

        if ($existing !== null) {
            $query = $this->queryBuilder->updatePrepared('CODIFICA_REG', ['descrizione'], 'codReg = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $descrizione, $codReg);
        } else {
            $query = $this->queryBuilder->insertPrepared('CODIFICA_REG', ['codReg', 'descrizione']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $codReg, $descrizione);
        }

        $stmt->execute();
    }

    public function delete(CodificaRegId $codReg): void {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->delete('CODIFICA_REG', 'codReg = ?');

        $stmt = $connection->prepare($query);
        $codRegStr = (string) $codReg;
        $stmt->bind_param('s', $codRegStr);
        $stmt->execute();
    }
}
