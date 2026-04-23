<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\IFornitoreService;
use src\Application\DTO\Input\GetFornitoreByRagSocInput;
use src\Application\DTO\Input\CreateFornitoreInput;
use src\Application\DTO\Input\UpdateFornitoreInput;
use src\Application\DTO\Input\DeleteFornitoreInput;
use src\Presentation\Response\IResponse;

class FornitoreController {

    public function __construct(
        private IFornitoreService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
        try {
            $fornitori = $this->service->getAll();
            $this->response->success($fornitori);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getByRagSoc(string $ragSoc): void {
        try {
            $fornitore = $this->service->getByRagSoc(new GetFornitoreByRagSocInput($ragSoc));
            if ($fornitore === null) {
                $this->response->error('Fornitore non trovato.', 404);
            }
            $this->response->success($fornitore);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createFornitore(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['ragSoc'])) {
                $this->response->error('La ragione sociale è obbligatoria.', 400);
            }
            $this->service->createFornitore(new CreateFornitoreInput(
                $data['ragSoc'],
                $data['partIVA'] ?? null,
                $data['telefono'] ?? null,
                $data['indirizzo'] ?? null,
                $data['email'] ?? null
            ));
            $this->response->success(['message' => 'Fornitore creato con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateFornitore(string $ragSoc): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $fields = array_intersect_key($data ?? [], array_flip(['partIVA', 'telefono', 'indirizzo', 'email']));
            if (empty($fields)) {
                $this->response->error('Nessun campo da aggiornare.', 400);
                return;
            }
            $this->service->updateFornitore(new UpdateFornitoreInput($ragSoc, $fields));
            $this->response->success(['message' => 'Fornitore aggiornato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteFornitore(string $ragSoc): void {
        try {
            $this->service->deleteFornitore(new DeleteFornitoreInput($ragSoc));
            $this->response->success(['message' => 'Fornitore eliminato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
