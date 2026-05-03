<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\IFiltroSalvatoService;
use src\Application\DTO\Input\CreateFiltroSalvatoInput;
use src\Application\DTO\Input\DeleteFiltroSalvatoInput;
use src\Presentation\Response\IResponse;

class FiltroSalvatoController
{
    public function __construct(
        private IFiltroSalvatoService $service,
        private IResponse $response
    ) {}

    public function getAll(): void
    {
        try {
            $filtri = $this->service->getAll();
            $this->response->success($filtri);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function create(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['nome']) || empty($data['stato'])) {
                $this->response->error('Nome e stato del filtro sono obbligatori.', 400);
                return;
            }
            $stato = is_string($data['stato']) ? $data['stato'] : json_encode($data['stato']);
            $filtro = $this->service->create(new CreateFiltroSalvatoInput($data['nome'], $stato));
            $this->response->success($filtro, 201);
        } catch (\src\Domain\Exceptions\DomainValidationException|\InvalidArgumentException $e) {
            $this->response->error($e->getMessage(), 400);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function delete(string $id): void
    {
        try {
            $this->service->delete(new DeleteFiltroSalvatoInput((int) $id));
            $this->response->success(['message' => 'Filtro eliminato con successo.']);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
