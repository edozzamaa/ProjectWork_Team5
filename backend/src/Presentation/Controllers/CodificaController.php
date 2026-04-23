<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\ICodificaService;
use src\Application\DTO\Input\GetCodificaRegByCodInput;
use src\Application\DTO\Input\CreateCodificaRegInput;
use src\Application\DTO\Input\UpdateCodificaRegInput;
use src\Application\DTO\Input\DeleteCodificaRegInput;
use src\Application\DTO\Input\GetCodificaOEByCodInput;
use src\Application\DTO\Input\GetCodificaOEByFornitoreInput;
use src\Application\DTO\Input\CreateCodificaOEInput;
use src\Application\DTO\Input\UpdateCodificaOEInput;
use src\Application\DTO\Input\DeleteCodificaOEInput;
use src\Presentation\Response\IResponse;

class CodificaController {

    public function __construct(
        private ICodificaService $service,
        private IResponse $response
    ) {}

    public function getAllReg(): void {
        try {
            $codifiche = $this->service->getAllReg();
            $this->response->success($codifiche);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getRegByCod(string $codReg): void {
        try {
            $codifica = $this->service->getRegByCod(new GetCodificaRegByCodInput($codReg));
            if ($codifica === null) {
                $this->response->error('Codifica regionale non trovata.', 404);
            }
            $this->response->success($codifica);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createReg(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codReg']) || empty($data['descrizione'])) {
                $this->response->error('Codice e descrizione sono obbligatori.', 400);
            }
            $this->service->createReg(new CreateCodificaRegInput($data['codReg'], $data['descrizione']));
            $this->response->success(['message' => 'Codifica regionale creata con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateReg(string $codReg): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['descrizione'])) {
                $this->response->error('La descrizione è obbligatoria.', 400);
            }
            $this->service->updateReg(new UpdateCodificaRegInput($codReg, $data['descrizione']));
            $this->response->success(['message' => 'Codifica regionale aggiornata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteReg(string $codReg): void {
        try {
            $this->service->deleteReg(new DeleteCodificaRegInput($codReg));
            $this->response->success(['message' => 'Codifica regionale eliminata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getAllOE(): void {
        try {
            $codifiche = $this->service->getAllOE();
            $this->response->success($codifiche);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getOEByCod(string $codOE): void {
        try {
            $codifica = $this->service->getOEByCod(new GetCodificaOEByCodInput($codOE));
            if ($codifica === null) {
                $this->response->error('Codifica OE non trovata.', 404);
            }
            $this->response->success($codifica);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getOEByFornitore(string $ragSoc): void {
        try {
            $codifiche = $this->service->getOEByFornitore(new GetCodificaOEByFornitoreInput($ragSoc));
            $this->response->success($codifiche);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createOE(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codOE']) || empty($data['descrizione'])) {
                $this->response->error('Codice e descrizione sono obbligatori.', 400);
            }
            $this->service->createOE(new CreateCodificaOEInput($data['codOE'], $data['descrizione'], $data['ragSoc'] ?? null));
            $this->response->success(['message' => 'Codifica OE creata con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateOE(string $codOE): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['descrizione'])) {
                $this->response->error('La descrizione è obbligatoria.', 400);
            }
            $this->service->updateOE(new UpdateCodificaOEInput($codOE, $data['descrizione'], $data['ragSoc'] ?? null));
            $this->response->success(['message' => 'Codifica OE aggiornata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteOE(string $codOE): void {
        try {
            $this->service->deleteOE(new DeleteCodificaOEInput($codOE));
            $this->response->success(['message' => 'Codifica OE eliminata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
