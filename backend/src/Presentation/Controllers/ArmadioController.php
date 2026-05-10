<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\IArmadioService;
use src\Application\DTO\Input\GetArmadioInput;
use src\Application\DTO\Input\CreateArmadioInput;
use src\Application\DTO\Input\UpdateArmadioInput;
use src\Application\DTO\Input\DeleteArmadioInput;
use src\Application\DTO\Input\GetPosizioniByArmadioInput;
use src\Application\DTO\Input\GetPosizioneInput;
use src\Application\DTO\Input\CreatePosizioneInput;
use src\Application\DTO\Input\UpdatePosizioneInput;
use src\Application\DTO\Input\DeletePosizioneInput;
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
            $armadio = $this->service->getArmadio(new GetArmadioInput($codArmadio));
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
            $this->service->createArmadio(new CreateArmadioInput($data['codArmadio'], $data['descrizione'] ?? null));
            $this->response->success(['message' => 'Armadio creato con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateArmadio(string $codArmadio): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->service->updateArmadio(new UpdateArmadioInput($codArmadio, $data['descrizione'] ?? null));
            $this->response->success(['message' => 'Armadio aggiornato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteArmadio(string $codArmadio): void {
        try {
            $this->service->deleteArmadio(new DeleteArmadioInput($codArmadio));
            $this->response->success(['message' => 'Armadio eliminato con successo.']);
        } catch (\DomainException $e) {
            $this->response->error($e->getMessage(), 409);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getPosizioniByArmadio(string $codArmadio): void {
        try {
            $posizioni = $this->service->getPosizioniByArmadio(new GetPosizioniByArmadioInput($codArmadio));
            $this->response->success($posizioni);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getPosizione(string $codArmadio, string $codScaffale): void {
        try {
            $posizione = $this->service->getPosizione(new GetPosizioneInput($codArmadio, $codScaffale));
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
            $this->service->createPosizione(new CreatePosizioneInput($codArmadio, $data['codScaffale'], $data['descrizione'] ?? null));
            $this->response->success(['message' => 'Posizione creata con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updatePosizione(string $codArmadio, string $codScaffale): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->service->updatePosizione(new UpdatePosizioneInput($codArmadio, $codScaffale, $data['descrizione'] ?? null));
            $this->response->success(['message' => 'Posizione aggiornata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deletePosizione(string $codArmadio, string $codScaffale): void {
        try {
            $this->service->deletePosizione(new DeletePosizioneInput($codArmadio, $codScaffale));
            $this->response->success(['message' => 'Posizione eliminata con successo.']);
        } catch (\DomainException $e) {
            $this->response->error($e->getMessage(), 409);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
