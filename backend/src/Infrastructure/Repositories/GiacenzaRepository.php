<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\IGiacenzaRepository;
use src\Domain\Models\PosProd;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Posizione\ScaffaleId;
use src\Domain\ValueObjects\PosProd\Quantita;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class GiacenzaRepository implements IGiacenzaRepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    public function find(ProdottoId $codProd, ArmadioId $codArmadio, ScaffaleId $codScaffale): ?PosProd {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, codArmadio, codScaffale, qta', 'POS_PROD')
            . $this->queryBuilder->where('codProd = ? AND codArmadio = ? AND codScaffale = ?');

        $stmt = $connection->prepare($query);
        $codProdStr = (string) $codProd;
        $codArmadioStr = (string) $codArmadio;
        $codScaffaleStr = (string) $codScaffale;
        $stmt->bind_param('sss', $codProdStr, $codArmadioStr, $codScaffaleStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return $this->rowToModel($row);
    }

    /** @return PosProd[] */
    public function findByProdotto(ProdottoId $codProd): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, codArmadio, codScaffale, qta', 'POS_PROD')
            . $this->queryBuilder->where('codProd = ?');

        $stmt = $connection->prepare($query);
        $codProdStr = (string) $codProd;
        $stmt->bind_param('s', $codProdStr);
        $stmt->execute();
        $result = $stmt->get_result();
        $giacenze = [];

        while ($row = $result->fetch_assoc()) {
            $giacenze[] = $this->rowToModel($row);
        }

        return $giacenze;
    }

    /** @return PosProd[] */
    public function findByPosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, codArmadio, codScaffale, qta', 'POS_PROD')
            . $this->queryBuilder->where('codArmadio = ? AND codScaffale = ?');

        $stmt = $connection->prepare($query);
        $codArmadioStr = (string) $codArmadio;
        $codScaffaleStr = (string) $codScaffale;
        $stmt->bind_param('ss', $codArmadioStr, $codScaffaleStr);
        $stmt->execute();
        $result = $stmt->get_result();
        $giacenze = [];

        while ($row = $result->fetch_assoc()) {
            $giacenze[] = $this->rowToModel($row);
        }

        return $giacenze;
    }

    /** @return PosProd[] */
    public function findAll(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, codArmadio, codScaffale, qta', 'POS_PROD');

        $result = $connection->query($query);
        $giacenze = [];

        while ($row = $result->fetch_assoc()) {
            $giacenze[] = $this->rowToModel($row);
        }

        return $giacenze;
    }

    public function giacenzaTotale(ProdottoId $codProd): int {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('COALESCE(SUM(qta), 0) AS totale', 'POS_PROD')
            . $this->queryBuilder->where('codProd = ?');

        $stmt = $connection->prepare($query);
        $codProdStr = (string) $codProd;
        $stmt->bind_param('s', $codProdStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        return (int) $row['totale'];
    }

    public function save(PosProd $posProd): void {
        $connection = $this->databaseConnector->getConnection();
        $codProd = (string) $posProd->getCodProd();
        $codArmadio = (string) $posProd->getCodArmadio();
        $codScaffale = (string) $posProd->getCodScaffale();
        $qta = $posProd->getQta()->value;

        $existing = $this->find($posProd->getCodProd(), $posProd->getCodArmadio(), $posProd->getCodScaffale());

        if ($existing !== null) {
            $query = $this->queryBuilder->updatePrepared('POS_PROD', ['qta'], 'codProd = ? AND codArmadio = ? AND codScaffale = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('isss', $qta, $codProd, $codArmadio, $codScaffale);
        } else {
            $query = $this->queryBuilder->insertPrepared('POS_PROD', ['codProd', 'codArmadio', 'codScaffale', 'qta']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sssi', $codProd, $codArmadio, $codScaffale, $qta);
        }

        $stmt->execute();
    }

    public function delete(ProdottoId $codProd, ArmadioId $codArmadio, ScaffaleId $codScaffale): void {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->delete('POS_PROD', 'codProd = ? AND codArmadio = ? AND codScaffale = ?');

        $stmt = $connection->prepare($query);
        $codProdStr = (string) $codProd;
        $codArmadioStr = (string) $codArmadio;
        $codScaffaleStr = (string) $codScaffale;
        $stmt->bind_param('sss', $codProdStr, $codArmadioStr, $codScaffaleStr);
        $stmt->execute();
    }

    /**
     * @return array<int, array{codProd: string, qtaRiordino: int, qtaTotale: int}>
     */
    public function prodottiSottoSoglia(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, qtaRiordino, qtaTotale', 'v_stock_alert');

        $result = $connection->query($query);
        $prodotti = [];

        while ($row = $result->fetch_assoc()) {
            $prodotti[] = [
                'codProd' => sprintf('%04d', (int) $row['codProd']),
                'qtaRiordino' => (int) $row['qtaRiordino'],
                'qtaTotale' => (int) $row['qtaTotale'],
            ];
        }

        return $prodotti;
    }

    private function rowToModel(array $row): PosProd {
        return PosProd::reconstituteFromDatabase(
            new ProdottoId((int) $row['codProd']),
            new ArmadioId((string) $row['codArmadio']),
            new ScaffaleId((string) $row['codScaffale']),
            new Quantita((int) $row['qta'])
        );
    }
}
