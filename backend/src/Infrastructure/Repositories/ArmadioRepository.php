<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Armadio;
use src\Domain\Models\Posizione;

interface ArmadioRepository {

    public function findArmadio(string $codArmadio): ?Armadio;

    /** @return Armadio[] */
    public function findAllArmadi(): array;

    public function saveArmadio(Armadio $armadio): void;

    public function deleteArmadio(string $codArmadio): void;

    public function findPosizione(string $codArmadio, string $codScaffale): ?Posizione;

    /** @return Posizione[] */
    public function findPosizioniByArmadio(string $codArmadio): array;

    public function savePosizione(Posizione $posizione): void;

    public function deletePosizione(string $codArmadio, string $codScaffale): void;
}
