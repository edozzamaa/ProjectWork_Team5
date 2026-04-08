<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\IProdottoRepository;
use src\Domain\Models\Prodotto;
use src\Domain\Models\AttrProd;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Prodotto\QuantitaRiordino;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\CodificaReg\CodificaRegId;
use src\Domain\ValueObjects\CodificaOE\CodificaOEId;
use src\Domain\ValueObjects\Attributo\AttributoId;
use src\Domain\ValueObjects\AttrProd\ValoreAttributo;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class ProdottoRepository implements IProdottoRepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    public function findByCod(ProdottoId $codProd): ?Prodotto {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, qtaRiordino, codCat, codReg, codOE', 'PRODOTTO')
            . $this->queryBuilder->where('codProd = ?');

        $stmt = $connection->prepare($query);
        $codProdStr = (string) $codProd;
        $stmt->bind_param('s', $codProdStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return $this->rowToModel($row);
    }

    /** @return Prodotto[] */
    public function findAll(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, qtaRiordino, codCat, codReg, codOE', 'PRODOTTO');

        $result = $connection->query($query);
        $prodotti = [];

        while ($row = $result->fetch_assoc()) {
            $prodotti[] = $this->rowToModel($row);
        }

        return $prodotti;
    }

    /** @return Prodotto[] */
    public function findByCategoria(CategoriaId $codCat): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, qtaRiordino, codCat, codReg, codOE', 'PRODOTTO')
            . $this->queryBuilder->where('codCat = ?');

        $stmt = $connection->prepare($query);
        $codCatStr = (string) $codCat;
        $stmt->bind_param('s', $codCatStr);
        $stmt->execute();
        $result = $stmt->get_result();
        $prodotti = [];

        while ($row = $result->fetch_assoc()) {
            $prodotti[] = $this->rowToModel($row);
        }

        return $prodotti;
    }

    public function save(Prodotto $prodotto): void {
        $connection = $this->databaseConnector->getConnection();
        $codProd = (string) $prodotto->getCodProd();
        $qtaRiordino = $prodotto->getQtaRiordino()->value;
        $codCat = $prodotto->getCodCat() !== null ? (string) $prodotto->getCodCat() : null;
        $codReg = $prodotto->getCodReg() !== null ? (string) $prodotto->getCodReg() : null;
        $codOE = $prodotto->getCodOE() !== null ? (string) $prodotto->getCodOE() : null;

        $query = $this->queryBuilder->insertPrepared('PRODOTTO', ['codProd', 'qtaRiordino', 'codCat', 'codReg', 'codOE']);
        $stmt = $connection->prepare($query);
        $stmt->bind_param('sisss', $codProd, $qtaRiordino, $codCat, $codReg, $codOE);
        $stmt->execute();
    }

    public function update(Prodotto $prodotto, array $columns): void {
        $connection = $this->databaseConnector->getConnection();
        $codProd = (string) $prodotto->getCodProd();

        $valueMap = [
            'qtaRiordino' => $prodotto->getQtaRiordino()->value,
            'codCat' => $prodotto->getCodCat() !== null ? (string) $prodotto->getCodCat() : null,
            'codReg' => $prodotto->getCodReg() !== null ? (string) $prodotto->getCodReg() : null,
            'codOE' => $prodotto->getCodOE() !== null ? (string) $prodotto->getCodOE() : null,
        ];

        $types = '';
        $values = [];
        foreach ($columns as $col) {
            $types .= $col === 'qtaRiordino' ? 'i' : 's';
            $values[] = $valueMap[$col];
        }
        $types .= 's';
        $values[] = $codProd;

        $query = $this->queryBuilder->updatePrepared('PRODOTTO', $columns, 'codProd = ?');
        $stmt = $connection->prepare($query);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
    }

    public function delete(ProdottoId $codProd): void {
        $connection = $this->databaseConnector->getConnection();
        $codProdStr = (string) $codProd;

        $connection->begin_transaction();

        try {
            $deleteAttrQuery = $this->queryBuilder->delete('ATTR_PROD', 'codProd = ?');
            $stmtAttr = $connection->prepare($deleteAttrQuery);
            $stmtAttr->bind_param('s', $codProdStr);
            $stmtAttr->execute();

            $deletePosQuery = $this->queryBuilder->delete('POS_PROD', 'codProd = ?');
            $stmtPos = $connection->prepare($deletePosQuery);
            $stmtPos->bind_param('s', $codProdStr);
            $stmtPos->execute();

            $deleteProdQuery = $this->queryBuilder->delete('PRODOTTO', 'codProd = ?');
            $stmtProd = $connection->prepare($deleteProdQuery);
            $stmtProd->bind_param('s', $codProdStr);
            $stmtProd->execute();

            $connection->commit();
        } catch (\Throwable $e) {
            $connection->rollback();
            throw new \RuntimeException("Impossibile eliminare il prodotto '{$codProdStr}'.");
        }
    }

    /** @return AttrProd[] */
    public function getAttributi(ProdottoId $codProd): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, codAttr, valore', 'ATTR_PROD')
            . $this->queryBuilder->where('codProd = ?');

        $stmt = $connection->prepare($query);
        $codProdStr = (string) $codProd;
        $stmt->bind_param('s', $codProdStr);
        $stmt->execute();
        $result = $stmt->get_result();
        $attributi = [];

        while ($row = $result->fetch_assoc()) {
            $attributi[] = AttrProd::reconstituteFromDatabase(
                new ProdottoId((string) $row['codProd']),
                new AttributoId((string) $row['codAttr']),
                $row['valore'] !== null ? new ValoreAttributo((string) $row['valore']) : null
            );
        }

        return $attributi;
    }

    /** @return AttrProd[] */
    public function findAllAttributi(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codProd, codAttr, valore', 'ATTR_PROD');
        $result = $connection->query($query);
        $attributi = [];

        while ($row = $result->fetch_assoc()) {
            $attributi[] = AttrProd::reconstituteFromDatabase(
                new ProdottoId((string) $row['codProd']),
                new AttributoId((string) $row['codAttr']),
                $row['valore'] !== null ? new ValoreAttributo((string) $row['valore']) : null
            );
        }

        return $attributi;
    }

    public function saveAttributo(AttrProd $attrProd): void {
        $connection = $this->databaseConnector->getConnection();
        $codProd = (string) $attrProd->getCodProd();
        $codAttr = (string) $attrProd->getCodAttr();
        $valore = $attrProd->getValore()?->value;

        $checkQuery = $this->queryBuilder->select('codProd', 'ATTR_PROD')
            . $this->queryBuilder->where('codProd = ? AND codAttr = ?');
        $checkStmt = $connection->prepare($checkQuery);
        $checkStmt->bind_param('ss', $codProd, $codAttr);
        $checkStmt->execute();
        $exists = (bool) $checkStmt->get_result()->fetch_assoc();

        if ($exists) {
            $query = $this->queryBuilder->updatePrepared('ATTR_PROD', ['valore'], 'codProd = ? AND codAttr = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $valore, $codProd, $codAttr);
        } else {
            $query = $this->queryBuilder->insertPrepared('ATTR_PROD', ['codProd', 'codAttr', 'valore']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $codProd, $codAttr, $valore);
        }

        $stmt->execute();
    }

    public function deleteAttributo(ProdottoId $codProd, AttributoId $codAttr): void {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->delete('ATTR_PROD', 'codProd = ? AND codAttr = ?');

        $stmt = $connection->prepare($query);
        $codProdStr = (string) $codProd;
        $codAttrStr = (string) $codAttr;
        $stmt->bind_param('ss', $codProdStr, $codAttrStr);
        $stmt->execute();
    }

    private function rowToModel(array $row): Prodotto {
        return Prodotto::reconstituteFromDatabase(
            new ProdottoId((string) $row['codProd']),
            new QuantitaRiordino((int) $row['qtaRiordino']),
            $row['codCat'] !== null ? new CategoriaId((string) $row['codCat']) : null,
            $row['codReg'] !== null ? new CodificaRegId((string) $row['codReg']) : null,
            $row['codOE'] !== null ? new CodificaOEId((string) $row['codOE']) : null
        );
    }
}
