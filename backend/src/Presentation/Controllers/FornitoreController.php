<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use src\Application\Interfaces\IServices\IFornitoreService;
use src\Presentation\Response\IResponse;

class FornitoreController {

    public function __construct(
        private IFornitoreService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
    }

    public function getByRagSoc(string $ragSoc): void {
    }

    public function crea(): void {
    }

    public function aggiorna(string $ragSoc): void {
    }

    public function elimina(string $ragSoc): void {
    }
}
