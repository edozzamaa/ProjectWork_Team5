<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\IAttributoRepository;
use src\Domain\Models\Attributo;
use src\Domain\ValueObjects\Attributo\AttributoId;
use src\Domain\ValueObjects\Attributo\AttributoNome;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class AttributoRepository implements IAttributoRepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    public function findByCod(AttributoId $codAttr): ?Attributo {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codAttr, nome', 'ATTRIBUTO')
            . $this->queryBuilder->where('codAttr = ?');

        $stmt = $connection->prepare($query);
        $codAttrStr = (string) $codAttr;
        $stmt->bind_param('s', $codAttrStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return Attributo::reconstituteFromDatabase(
            new AttributoId((string) $row['codAttr']),
            new AttributoNome((string) $row['nome'])
        );
    }

    /** @return Attributo[] */
    public function findAll(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codAttr, nome', 'ATTRIBUTO');

        $result = $connection->query($query);
        $attributi = [];

        while ($row = $result->fetch_assoc()) {
            $attributi[] = Attributo::reconstituteFromDatabase(
                new AttributoId((string) $row['codAttr']),
                new AttributoNome((string) $row['nome'])
            );
        }

        return $attributi;
    }

    public function save(Attributo $attributo): void {
        $connection = $this->databaseConnector->getConnection();
        $codAttr = (string) $attributo->getCodAttr();
        $nome = $attributo->getNome()->value;

        $existing = $this->findByCod($attributo->getCodAttr());

        if ($existing !== null) {
            $query = $this->queryBuilder->updatePrepared('ATTRIBUTO', ['nome'], 'codAttr = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $nome, $codAttr);
        } else {
            $query = $this->queryBuilder->insertPrepared('ATTRIBUTO', ['codAttr', 'nome']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $codAttr, $nome);
        }

        $stmt->execute();
    }

    public function delete(AttributoId $codAttr): void {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->delete('ATTRIBUTO', 'codAttr = ?');

        $stmt = $connection->prepare($query);
        $codAttrStr = (string) $codAttr;
        $stmt->bind_param('s', $codAttrStr);
        $stmt->execute();
    }
}
