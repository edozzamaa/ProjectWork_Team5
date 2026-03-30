<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\ICategoriaRepository;
use src\Domain\Models\Categoria;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\Categoria\CategoriaTipo;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class CategoriaRepository implements ICategoriaRepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    public function findByCod(CategoriaId $codCat): ?Categoria {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codCat, tipo', 'CATEGORIA')
            . $this->queryBuilder->where('codCat = ?');

        $stmt = $connection->prepare($query);
        $codCatStr = (string) $codCat;
        $stmt->bind_param('s', $codCatStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return Categoria::reconstituteFromDatabase(
            new CategoriaId((string) $row['codCat']),
            new CategoriaTipo((string) $row['tipo'])
        );
    }

    /** @return Categoria[] */
    public function findAll(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codCat, tipo', 'CATEGORIA');

        $result = $connection->query($query);
        $categorie = [];

        while ($row = $result->fetch_assoc()) {
            $categorie[] = Categoria::reconstituteFromDatabase(
                new CategoriaId((string) $row['codCat']),
                new CategoriaTipo((string) $row['tipo'])
            );
        }

        return $categorie;
    }

    public function save(Categoria $categoria): void {
        $connection = $this->databaseConnector->getConnection();
        $codCat = (string) $categoria->getCodCat();
        $tipo = $categoria->getTipo()->value;

        $existing = $this->findByCod($categoria->getCodCat());

        if ($existing !== null) {
            $query = $this->queryBuilder->updatePrepared('CATEGORIA', ['tipo'], 'codCat = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $tipo, $codCat);
        } else {
            $query = $this->queryBuilder->insertPrepared('CATEGORIA', ['codCat', 'tipo']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $codCat, $tipo);
        }

        $stmt->execute();
    }

    public function delete(CategoriaId $codCat): void {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->delete('CATEGORIA', 'codCat = ?');

        $stmt = $connection->prepare($query);
        $codCatStr = (string) $codCat;
        $stmt->bind_param('s', $codCatStr);
        $stmt->execute();
    }
}
