<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\ICategoriaService;
use src\Application\DTO\Input\GetCategoriaByCodInput;
use src\Application\DTO\Input\CreateCategoriaInput;
use src\Application\DTO\Input\UpdateCategoriaInput;
use src\Application\DTO\Input\DeleteCategoriaInput;
use src\Presentation\Response\IResponse;

class CategoriaController {

    public function __construct(
        private ICategoriaService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
        try {
            $categorie = $this->service->getAll();
            $this->response->success($categorie);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getByCod(string $codCat): void {
        try {
            $categoria = $this->service->getByCod(new GetCategoriaByCodInput($codCat));
            if ($categoria === null) {
                $this->response->error('Categoria non trovata.', 404);
            }
            $this->response->success($categoria);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function createCategoria(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['codCat']) || empty($data['tipo'])) {
                $this->response->error('Codice categoria e tipo sono obbligatori.', 400);
            }
            $this->service->createCategoria(new CreateCategoriaInput($data['codCat'], $data['tipo']));
            $this->response->success(['message' => 'Categoria creata con successo.'], 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function updateCategoria(string $codCat): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['tipo'])) {
                $this->response->error('Il tipo è obbligatorio.', 400);
            }
            $this->service->updateCategoria(new UpdateCategoriaInput($codCat, $data['tipo']));
            $this->response->success(['message' => 'Categoria aggiornata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function deleteCategoria(string $codCat): void {
        try {
            $this->service->deleteCategoria(new DeleteCategoriaInput($codCat));
            $this->response->success(['message' => 'Categoria eliminata con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
