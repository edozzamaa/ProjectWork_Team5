<?php declare(strict_types=1);

namespace src\Infrastructure\Repositories;

use src\Application\Interfaces\IRepositories\IArmadioRepository;
use src\Domain\Models\Armadio;
use src\Domain\Models\Posizione;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Armadio\ArmadioDescrizione;
use src\Domain\ValueObjects\Posizione\ScaffaleId;
use src\Domain\ValueObjects\Posizione\PosizioneDescrizione;
use src\Infrastructure\DatabaseConnector;
use src\Infrastructure\QueryBuilder\QueryBuilder;

class ArmadioRepository implements IArmadioRepository {

    private DatabaseConnector $databaseConnector;
    private QueryBuilder $queryBuilder;

    public function __construct() {
        $this->databaseConnector = DatabaseConnector::getInstance();
        $this->queryBuilder = new QueryBuilder($this->databaseConnector);
    }

    // ── Armadio ──

    public function findArmadio(ArmadioId $codArmadio): ?Armadio {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codArmadio, descrizione', 'ARMADIO')
            . $this->queryBuilder->where('codArmadio = ?');

        $stmt = $connection->prepare($query);
        $codArmadioStr = (string) $codArmadio;
        $stmt->bind_param('s', $codArmadioStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return Armadio::reconstituteFromDatabase(
            new ArmadioId((string) $row['codArmadio']),
            $row['descrizione'] !== null ? new ArmadioDescrizione((string) $row['descrizione']) : null
        );
    }

    /** @return Armadio[] */
    public function findAllArmadi(): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codArmadio, descrizione', 'ARMADIO');

        $result = $connection->query($query);
        $armadi = [];

        while ($row = $result->fetch_assoc()) {
            $armadi[] = Armadio::reconstituteFromDatabase(
                new ArmadioId((string) $row['codArmadio']),
                $row['descrizione'] !== null ? new ArmadioDescrizione((string) $row['descrizione']) : null
            );
        }

        return $armadi;
    }

    public function saveArmadio(Armadio $armadio): void {
        $connection = $this->databaseConnector->getConnection();
        $codArmadio = (string) $armadio->getCodArmadio();
        $descrizione = $armadio->getDescrizione()?->value;

        $existing = $this->findArmadio($armadio->getCodArmadio());

        if ($existing !== null) {
            $query = $this->queryBuilder->updatePrepared('ARMADIO', ['descrizione'], 'codArmadio = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $descrizione, $codArmadio);
        } else {
            $query = $this->queryBuilder->insertPrepared('ARMADIO', ['codArmadio', 'descrizione']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $codArmadio, $descrizione);
        }

        $stmt->execute();
    }

    public function deleteArmadio(ArmadioId $codArmadio): void {
        $connection = $this->databaseConnector->getConnection();
        $codArmadioStr = (string) $codArmadio;

        $connection->begin_transaction();

        try {
            $deleteGiacenzeQuery = $this->queryBuilder->delete('POS_PROD', 'codArmadio = ?');
            $stmtGiac = $connection->prepare($deleteGiacenzeQuery);
            $stmtGiac->bind_param('s', $codArmadioStr);
            $stmtGiac->execute();

            $deletePosizioniQuery = $this->queryBuilder->delete('POSIZIONE', 'codArmadio = ?');
            $stmtPos = $connection->prepare($deletePosizioniQuery);
            $stmtPos->bind_param('s', $codArmadioStr);
            $stmtPos->execute();

            $deleteArmadioQuery = $this->queryBuilder->delete('ARMADIO', 'codArmadio = ?');
            $stmtArm = $connection->prepare($deleteArmadioQuery);
            $stmtArm->bind_param('s', $codArmadioStr);
            $stmtArm->execute();

            $connection->commit();
        } catch (\Throwable $e) {
            $connection->rollback();
            throw new \RuntimeException("Impossibile eliminare l'armadio '{$codArmadioStr}'.");
        }
    }

    // ── Posizione ──

    public function findPosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): ?Posizione {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codArmadio, codScaffale, descrizione', 'POSIZIONE')
            . $this->queryBuilder->where('codArmadio = ? AND codScaffale = ?');

        $stmt = $connection->prepare($query);
        $codArmadioStr = (string) $codArmadio;
        $codScaffaleStr = (string) $codScaffale;
        $stmt->bind_param('ss', $codArmadioStr, $codScaffaleStr);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return Posizione::reconstituteFromDatabase(
            new ArmadioId((string) $row['codArmadio']),
            new ScaffaleId((string) $row['codScaffale']),
            $row['descrizione'] !== null ? new PosizioneDescrizione((string) $row['descrizione']) : null
        );
    }

    /** @return Posizione[] */
    public function findPosizioniByArmadio(ArmadioId $codArmadio): array {
        $connection = $this->databaseConnector->getConnection();
        $query = $this->queryBuilder->select('codArmadio, codScaffale, descrizione', 'POSIZIONE')
            . $this->queryBuilder->where('codArmadio = ?');

        $stmt = $connection->prepare($query);
        $codArmadioStr = (string) $codArmadio;
        $stmt->bind_param('s', $codArmadioStr);
        $stmt->execute();
        $result = $stmt->get_result();
        $posizioni = [];

        while ($row = $result->fetch_assoc()) {
            $posizioni[] = Posizione::reconstituteFromDatabase(
                new ArmadioId((string) $row['codArmadio']),
                new ScaffaleId((string) $row['codScaffale']),
                $row['descrizione'] !== null ? new PosizioneDescrizione((string) $row['descrizione']) : null
            );
        }

        return $posizioni;
    }

    public function savePosizione(Posizione $posizione): void {
        $connection = $this->databaseConnector->getConnection();
        $codArmadio = (string) $posizione->getCodArmadio();
        $codScaffale = (string) $posizione->getCodScaffale();
        $descrizione = $posizione->getDescrizione()?->value;

        $existing = $this->findPosizione($posizione->getCodArmadio(), $posizione->getCodScaffale());

        if ($existing !== null) {
            $query = $this->queryBuilder->updatePrepared('POSIZIONE', ['descrizione'], 'codArmadio = ? AND codScaffale = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $descrizione, $codArmadio, $codScaffale);
        } else {
            $query = $this->queryBuilder->insertPrepared('POSIZIONE', ['codArmadio', 'codScaffale', 'descrizione']);
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $codArmadio, $codScaffale, $descrizione);
        }

        $stmt->execute();
    }

    public function deletePosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): void {
        $connection = $this->databaseConnector->getConnection();
        $codArmadioStr = (string) $codArmadio;
        $codScaffaleStr = (string) $codScaffale;

        $connection->begin_transaction();

        try {
            $deleteGiacenzeQuery = $this->queryBuilder->delete('POS_PROD', 'codArmadio = ? AND codScaffale = ?');
            $stmtGiac = $connection->prepare($deleteGiacenzeQuery);
            $stmtGiac->bind_param('ss', $codArmadioStr, $codScaffaleStr);
            $stmtGiac->execute();

            $deletePosQuery = $this->queryBuilder->delete('POSIZIONE', 'codArmadio = ? AND codScaffale = ?');
            $stmtPos = $connection->prepare($deletePosQuery);
            $stmtPos->bind_param('ss', $codArmadioStr, $codScaffaleStr);
            $stmtPos->execute();

            $connection->commit();
        } catch (\Throwable $e) {
            $connection->rollback();
            throw new \RuntimeException("Impossibile eliminare la posizione '{$codArmadioStr}/{$codScaffaleStr}'.");
        }
    }

    public function countGiacenzeArmadio(ArmadioId $codArmadio): int {
        $connection = $this->databaseConnector->getConnection();
        $stmt = $connection->prepare(
            'SELECT COALESCE(SUM(qta), 0) AS tot FROM POS_PROD WHERE codArmadio = ?'
        );
        $codArmadioStr = (string) $codArmadio;
        $stmt->bind_param('s', $codArmadioStr);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['tot'];
    }

    public function countGiacenzePosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): int {
        $connection = $this->databaseConnector->getConnection();
        $stmt = $connection->prepare(
            'SELECT COALESCE(SUM(qta), 0) AS tot FROM POS_PROD WHERE codArmadio = ? AND codScaffale = ?'
        );
        $codArmadioStr  = (string) $codArmadio;
        $codScaffaleStr = (string) $codScaffale;
        $stmt->bind_param('ss', $codArmadioStr, $codScaffaleStr);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['tot'];
    }
}
