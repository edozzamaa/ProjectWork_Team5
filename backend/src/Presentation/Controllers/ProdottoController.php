<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\IProdottoService;
use src\Application\DTO\Input\GetProdottoByCodInput;
use src\Application\DTO\Input\GetProdottoByCategoriaInput;
use src\Application\DTO\Input\CreateProdottoInput;
use src\Application\DTO\Input\UpdateProdottoInput;
use src\Application\DTO\Input\DeleteProdottoInput;
use src\Application\DTO\Input\GetAttributiDiProdottoInput;
use src\Application\DTO\Input\LoadProdottoInput;
use src\Application\DTO\Input\UnloadProdottoInput;
use src\Presentation\Response\IResponse;

class ProdottoController {

    public function __construct(
        private IProdottoService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
        try {
            $prodotti = $this->service->getAll();
            $this->response->success($prodotti);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getByCod(string $codProd): void {
        try {
            $prodotto = $this->service->getByCod(new GetProdottoByCodInput($codProd));
            if ($prodotto === null) {
                $this->response->error('Prodotto non trovato.', 404);
            }
            $this->response->success($prodotto);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getByCategoria(string $codCat): void {
        try {
            $prodotti = $this->service->getByCategoria(new GetProdottoByCategoriaInput($codCat));
            $this->response->success($prodotti);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function searchWithStock(): void {
        try {
            $prodotti = $this->service->searchWithStock();
            $this->response->success($prodotti);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createProdotto(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $newCodProd = $this->service->createProdotto(new CreateProdottoInput(
                (int) ($data['qtaRiordino'] ?? 0),
                $data['codCat'] ?? null,
                $data['codReg'] ?? null,
                $data['codOE'] ?? null
            ));
            $this->response->success(['message' => 'Prodotto creato con successo.', 'codProd' => $newCodProd], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateProdotto(string $codProd): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $fields = array_intersect_key($data ?? [], array_flip(['qtaRiordino', 'codCat', 'codReg', 'codOE']));
            if (empty($fields)) {
                $this->response->error('Nessun campo da aggiornare.', 400);
                return;
            }
            $this->service->updateProdotto(new UpdateProdottoInput($codProd, $fields));
            $this->response->success(['message' => 'Prodotto aggiornato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteProdotto(string $codProd): void {
        try {
            $this->service->deleteProdotto(new DeleteProdottoInput($codProd));
            $this->response->success(['message' => 'Prodotto eliminato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getAttributi(string $codProd): void {
        try {
            $attributi = $this->service->getAttributi(new GetAttributiDiProdottoInput($codProd));
            $this->response->success($attributi);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function loadProdotto(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codProd']) || empty($data['codArmadio']) || empty($data['codScaffale']) || !isset($data['qta'])) {
                $this->response->error('Codice prodotto, armadio, scaffale e quantità sono obbligatori.', 400);
            }
            $this->service->loadProdotto(new LoadProdottoInput(
                $data['codProd'],
                $data['codArmadio'],
                $data['codScaffale'],
                (int) $data['qta'],
                $data['attributi'] ?? []
            ));
            $this->response->success(['message' => 'Carico effettuato con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function unloadProdotto(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codProd']) || empty($data['codArmadio']) || empty($data['codScaffale']) || !isset($data['qta'])) {
                $this->response->error('Codice prodotto, armadio, scaffale e quantità sono obbligatori.', 400);
            }
            $result = $this->service->unloadProdotto(new UnloadProdottoInput(
                $data['codProd'],
                $data['codArmadio'],
                $data['codScaffale'],
                (int) $data['qta']
            ));
            $this->response->success(['message' => 'Scarico effettuato con successo.', 'data' => $result]);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
