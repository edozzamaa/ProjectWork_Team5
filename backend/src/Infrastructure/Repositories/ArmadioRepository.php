<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Armadio;
use src\Domain\Models\Posizione;
use src\Domain\ValuesObject\ID;

interface ArmadioRepository {

    public function findArmadio(ID $codArmadio): ?Armadio;

    /** @return Armadio[] */
    public function findAllArmadi(): array;

    public function saveArmadio(Armadio $armadio): void;

    public function deleteArmadio(ID $codArmadio): void;

    public function findPosizione(ID $codArmadio, ID $codScaffale): ?Posizione;

    /** @return Posizione[] */
    public function findPosizioniByArmadio(ID $codArmadio): array;

    public function savePosizione(Posizione $posizione): void;

    public function deletePosizione(ID $codArmadio, ID $codScaffale): void;
}
