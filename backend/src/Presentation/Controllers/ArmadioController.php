<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use src\Application\Interfaces\IServices\IArmadioService;
use src\Presentation\Response\IResponse;

class ArmadioController {

    public function __construct(
        private IArmadioService $service,
        private IResponse $response
    ) {}

    public function getAllArmadi(): void {
    }

    public function getArmadio(string $codArmadio): void {
    }

    public function creaArmadio(): void {
    }

    public function aggiornaArmadio(string $codArmadio): void {
    }

    public function eliminaArmadio(string $codArmadio): void {
    }

    public function getPosizioniByArmadio(string $codArmadio): void {
    }

    public function getPosizione(string $codArmadio, string $codScaffale): void {
    }

    public function creaPosizione(string $codArmadio): void {
    }

    public function aggiornaPosizione(string $codArmadio, string $codScaffale): void {
    }

    public function eliminaPosizione(string $codArmadio, string $codScaffale): void {
    }
}
