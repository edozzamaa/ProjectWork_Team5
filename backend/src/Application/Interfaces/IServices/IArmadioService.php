<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\Output\ArmadioDTO;
use src\Application\DTO\Output\PosizioneDTO;
use src\Application\DTO\Input\GetArmadioInput;
use src\Application\DTO\Input\CreateArmadioInput;
use src\Application\DTO\Input\UpdateArmadioInput;
use src\Application\DTO\Input\DeleteArmadioInput;
use src\Application\DTO\Input\GetPosizioniByArmadioInput;
use src\Application\DTO\Input\GetPosizioneInput;
use src\Application\DTO\Input\CreatePosizioneInput;
use src\Application\DTO\Input\UpdatePosizioneInput;
use src\Application\DTO\Input\DeletePosizioneInput;

interface IArmadioService {

    /** @return ArmadioDTO[] */
    public function getAllArmadi(): array;

    public function getArmadio(GetArmadioInput $input): ?ArmadioDTO;

    public function createArmadio(CreateArmadioInput $input): void;

    public function updateArmadio(UpdateArmadioInput $input): void;

    public function deleteArmadio(DeleteArmadioInput $input): void;

    /** @return PosizioneDTO[] */
    public function getPosizioniByArmadio(GetPosizioniByArmadioInput $input): array;

    public function getPosizione(GetPosizioneInput $input): ?PosizioneDTO;

    public function createPosizione(CreatePosizioneInput $input): void;

    public function updatePosizione(UpdatePosizioneInput $input): void;

    public function deletePosizione(DeletePosizioneInput $input): void;
}
