<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\Armadio;
use src\Domain\Models\Posizione;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Posizione\ScaffaleId;

interface IArmadioRepository {

    public function findArmadio(ArmadioId $codArmadio): ?Armadio;

    /** @return Armadio[] */
    public function findAllArmadi(): array;

    public function saveArmadio(Armadio $armadio): void;

    public function deleteArmadio(ArmadioId $codArmadio): void;

    public function findPosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): ?Posizione;

    /** @return Posizione[] */
    public function findPosizioniByArmadio(ArmadioId $codArmadio): array;

    public function savePosizione(Posizione $posizione): void;

    public function deletePosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): void;

    public function countGiacenzeArmadio(ArmadioId $codArmadio): int;

    public function countGiacenzePosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): int;
}
