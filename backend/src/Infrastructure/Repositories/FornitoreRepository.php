<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\IFornitoreRepository;
use src\Domain\Models\Fornitore;
use src\Domain\ValueObjects\Fornitore\FornitoreId;
use src\Domain\ValueObjects\Fornitore\PartitaIVA;
use src\Domain\ValueObjects\Fornitore\Telefono;
use src\Domain\ValueObjects\Fornitore\Indirizzo;
use src\Domain\ValueObjects\Fornitore\Email;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class FornitoreRepository implements IFornitoreRepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    public function findByRagSoc(FornitoreId $ragSoc): ?Fornitore {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('ragSoc, partIVA, telefono, indirizzo, email', 'FORNITORE')
            . $this->queryBuilder->where('ragSoc = ?');

        $stmt = $connection->prepare($query);
        $ragSocStr = (string) $ragSoc;
        $stmt->bind_param('s', $ragSocStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return $this->rowToModel($row);
    }

    /** @return Fornitore[] */
    public function findAll(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('ragSoc, partIVA, telefono, indirizzo, email', 'FORNITORE');

        $result = $connection->query($query);
        $fornitori = [];

        while ($row = $result->fetch_assoc()) {
            $fornitori[] = $this->rowToModel($row);
        }

        return $fornitori;
    }

    public function save(Fornitore $fornitore): void {
        $connection = $this->databaseConnector->getConnection();
        $ragSoc = (string) $fornitore->getRagSoc();
        $partIVA = $fornitore->getPartIVA() !== null ? (string) $fornitore->getPartIVA() : null;
        $telefono = $fornitore->getTelefono() !== null ? (string) $fornitore->getTelefono() : null;
        $indirizzo = $fornitore->getIndirizzo()?->value;
        $email = $fornitore->getEmail() !== null ? $fornitore->getEmail()->value : null;

        $query = $this->queryBuilder->insertPrepared('FORNITORE', ['ragSoc', 'partIVA', 'telefono', 'indirizzo', 'email']);
        $stmt = $connection->prepare($query);
        $stmt->bind_param('sssss', $ragSoc, $partIVA, $telefono, $indirizzo, $email);
        $stmt->execute();
    }

    public function update(Fornitore $fornitore, array $columns): void {
        $connection = $this->databaseConnector->getConnection();
        $ragSoc = (string) $fornitore->getRagSoc();

        $valueMap = [
            'partIVA' => $fornitore->getPartIVA() !== null ? (string) $fornitore->getPartIVA() : null,
            'telefono' => $fornitore->getTelefono() !== null ? (string) $fornitore->getTelefono() : null,
            'indirizzo' => $fornitore->getIndirizzo()?->value,
            'email' => $fornitore->getEmail() !== null ? $fornitore->getEmail()->value : null,
        ];

        $types = str_repeat('s', count($columns)) . 's';
        $values = [];
        foreach ($columns as $col) {
            $values[] = $valueMap[$col];
        }
        $values[] = $ragSoc;

        $query = $this->queryBuilder->updatePrepared('FORNITORE', $columns, 'ragSoc = ?');
        $stmt = $connection->prepare($query);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
    }

    public function delete(FornitoreId $ragSoc): void {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->delete('FORNITORE', 'ragSoc = ?');

        $stmt = $connection->prepare($query);
        $ragSocStr = (string) $ragSoc;
        $stmt->bind_param('s', $ragSocStr);
        $stmt->execute();
    }

    private function rowToModel(array $row): Fornitore {
        return Fornitore::reconstituteFromDatabase(
            new FornitoreId((string) $row['ragSoc']),
            $row['partIVA'] !== null ? new PartitaIVA((string) $row['partIVA']) : null,
            $row['telefono'] !== null ? new Telefono((string) $row['telefono']) : null,
            $row['indirizzo'] !== null ? new Indirizzo((string) $row['indirizzo']) : null,
            $row['email'] !== null ? new Email((string) $row['email']) : null
        );
    }
}
