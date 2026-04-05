<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\ArmadioDTO;
use src\Application\DTO\PosizioneDTO;

interface IArmadioService {

    /** @return ArmadioDTO[] */
    public function getAllArmadi(): array;

    public function getArmadio(string $codArmadio): ?ArmadioDTO;

    public function createArmadio(string $codArmadio, ?string $descrizione = null): void;

    public function updateArmadio(string $codArmadio, ?string $descrizione): void;

    public function deleteArmadio(string $codArmadio): void;

    /** @return PosizioneDTO[] */
    public function getPosizioniByArmadio(string $codArmadio): array;

    public function getPosizione(string $codArmadio, string $codScaffale): ?PosizioneDTO;

    public function createPosizione(string $codArmadio, string $codScaffale, ?string $descrizione = null): void;

    public function updatePosizione(string $codArmadio, string $codScaffale, ?string $descrizione): void;

    public function deletePosizione(string $codArmadio, string $codScaffale): void;
}
