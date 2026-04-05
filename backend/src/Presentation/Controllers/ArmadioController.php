<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\IArmadioService;
use src\Presentation\Response\IResponse;

class ArmadioController {

    public function __construct(
        private IArmadioService $service,
        private IResponse $response
    ) {}

    public function getAllArmadi(): void {
        try {
            $armadi = $this->service->getAllArmadi();
            $this->response->success($armadi);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getArmadio(string $codArmadio): void {
        try {
            $armadio = $this->service->getArmadio($codArmadio);
            if ($armadio === null) {
                $this->response->error('Armadio non trovato.', 404);
            }
            $this->response->success($armadio);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createArmadio(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codArmadio'])) {
                $this->response->error('Il codice armadio è obbligatorio.', 400);
            }
            $this->service->createArmadio($data['codArmadio'], $data['descrizione'] ?? null);
            $this->response->success(['message' => 'Armadio creato con successo.'], 201);
        } catch (\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 409);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateArmadio(string $codArmadio): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->service->updateArmadio($codArmadio, $data['descrizione'] ?? null);
            $this->response->success(['message' => 'Armadio aggiornato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteArmadio(string $codArmadio): void {
        try {
            $this->service->deleteArmadio($codArmadio);
            $this->response->success(['message' => 'Armadio eliminato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getPosizioniByArmadio(string $codArmadio): void {
        try {
            $posizioni = $this->service->getPosizioniByArmadio($codArmadio);
            $this->response->success($posizioni);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getPosizione(string $codArmadio, string $codScaffale): void {
        try {
            $posizione = $this->service->getPosizione($codArmadio, $codScaffale);
            if ($posizione === null) {
                $this->response->error('Posizione non trovata.', 404);
            }
            $this->response->success($posizione);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createPosizione(string $codArmadio): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codScaffale'])) {
                $this->response->error('Il codice scaffale è obbligatorio.', 400);
            }
            $this->service->createPosizione($codArmadio, $data['codScaffale'], $data['descrizione'] ?? null);
            $this->response->success(['message' => 'Posizione creata con successo.'], 201);
        } catch (\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 409);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updatePosizione(string $codArmadio, string $codScaffale): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->service->updatePosizione($codArmadio, $codScaffale, $data['descrizione'] ?? null);
            $this->response->success(['message' => 'Posizione aggiornata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deletePosizione(string $codArmadio, string $codScaffale): void {
        try {
            $this->service->deletePosizione($codArmadio, $codScaffale);
            $this->response->success(['message' => 'Posizione eliminata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
