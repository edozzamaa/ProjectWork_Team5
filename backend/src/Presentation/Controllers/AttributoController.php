<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\IAttributoService;
use src\Application\DTO\Input\GetAttributoByCodInput;
use src\Application\DTO\Input\CreateAttributoInput;
use src\Application\DTO\Input\UpdateAttributoInput;
use src\Application\DTO\Input\DeleteAttributoInput;
use src\Application\DTO\Input\AssignAttributoToProdottoInput;
use src\Application\DTO\Input\RemoveAttributoFromProdottoInput;
use src\Application\DTO\Input\GetAttributiProdottoInput;
use src\Presentation\Response\IResponse;

class AttributoController {

    public function __construct(
        private IAttributoService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
        try {
            $attributi = $this->service->getAll();
            $this->response->success($attributi);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getByCod(string $codAttr): void {
        try {
            $attributo = $this->service->getByCod(new GetAttributoByCodInput($codAttr));
            if ($attributo === null) {
                $this->response->error('Attributo non trovato.', 404);
            }
            $this->response->success($attributo);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createAttributo(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codAttr']) || empty($data['nome'])) {
                $this->response->error('Codice attributo e nome sono obbligatori.', 400);
            }
            $this->service->createAttributo(new CreateAttributoInput($data['codAttr'], $data['nome']));
            $this->response->success(['message' => 'Attributo creato con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateAttributo(string $codAttr): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['nome'])) {
                $this->response->error('Il nome è obbligatorio.', 400);
            }
            $this->service->updateAttributo(new UpdateAttributoInput($codAttr, $data['nome']));
            $this->response->success(['message' => 'Attributo aggiornato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteAttributo(string $codAttr): void {
        try {
            $this->service->deleteAttributo(new DeleteAttributoInput($codAttr));
            $this->response->success(['message' => 'Attributo eliminato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function assignToProdotto(string $codProd): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codAttr'])) {
                $this->response->error('Il codice attributo è obbligatorio.', 400);
            }
            $this->service->assignToProdotto(new AssignAttributoToProdottoInput($codProd, $data['codAttr'], $data['valore'] ?? null));
            $this->response->success(['message' => 'Attributo assegnato al prodotto con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function removeFromProdotto(string $codProd, string $codAttr): void {
        try {
            $this->service->removeFromProdotto(new RemoveAttributoFromProdottoInput($codProd, $codAttr));
            $this->response->success(['message' => 'Attributo rimosso dal prodotto con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getAttributiProdotto(string $codProd): void {
        try {
            $attributi = $this->service->getAttributiProdotto(new GetAttributiProdottoInput($codProd));
            $this->response->success($attributi);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
