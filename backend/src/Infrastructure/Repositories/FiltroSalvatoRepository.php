<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\IFiltroSalvatoRepository;
use src\Domain\Models\FiltroSalvato;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoId;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoNome;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoStato;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class FiltroSalvatoRepository implements IFiltroSalvatoRepository
{
    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder      = new QueryBuilder($this->databaseConnector);
    }

    /** @return FiltroSalvato[] */
    public function findAll(): array
    {
        $connection = $this->databaseConnector->getConnection();
        $query      = $this->queryBuilder->select('id, nome, stato', 'FILTRO_SALVATO')
            . ' ORDER BY createdAt ASC';

        $result  = $connection->query($query);
        $filtri  = [];

        while ($row = $result->fetch_assoc()) {
            $filtri[] = $this->rowToModel($row);
        }

        return $filtri;
    }

    public function findById(FiltroSalvatoId $id): ?FiltroSalvato
    {
        $connection = $this->databaseConnector->getConnection();
        $query      = $this->queryBuilder->select('id, nome, stato', 'FILTRO_SALVATO')
            . $this->queryBuilder->where('id = ?');

        $stmt = $connection->prepare($query);
        $idVal = $id->value;
        $stmt->bind_param('i', $idVal);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        return $row ? $this->rowToModel($row) : null;
    }

    public function save(FiltroSalvato $filtro): FiltroSalvatoId
    {
        $connection = $this->databaseConnector->getConnection();
        $nome  = $filtro->getNome()->value;
        $stato = $filtro->getStato()->value;

        $query = $this->queryBuilder->insertPrepared('FILTRO_SALVATO', ['nome', 'stato']);
        $stmt  = $connection->prepare($query);
        $stmt->bind_param('ss', $nome, $stato);
        $stmt->execute();

        return new FiltroSalvatoId((int) $connection->insert_id);
    }

    public function delete(FiltroSalvatoId $id): void
    {
        $connection = $this->databaseConnector->getConnection();
        $query      = $this->queryBuilder->delete('FILTRO_SALVATO', 'id = ?');
        $stmt       = $connection->prepare($query);
        $idVal      = $id->value;
        $stmt->bind_param('i', $idVal);
        $stmt->execute();
    }

    private function rowToModel(array $row): FiltroSalvato
    {
        return FiltroSalvato::reconstituteFromDatabase(
            new FiltroSalvatoId((int) $row['id']),
            new FiltroSalvatoNome((string) $row['nome']),
            new FiltroSalvatoStato((string) $row['stato'])
        );
    }
}
