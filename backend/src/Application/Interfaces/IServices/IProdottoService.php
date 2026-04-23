<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\ProdottoDTO;
use src\Application\DTO\AttrProdDTO;
use src\Application\DTO\ScaricoProdottoResultDTO;
use src\Application\DTO\Input\GetProdottoByCodInput;
use src\Application\DTO\Input\GetProdottoByCategoriaInput;
use src\Application\DTO\Input\CreateProdottoInput;
use src\Application\DTO\Input\UpdateProdottoInput;
use src\Application\DTO\Input\DeleteProdottoInput;
use src\Application\DTO\Input\GetAttributiDiProdottoInput;
use src\Application\DTO\Input\LoadProdottoInput;
use src\Application\DTO\Input\UnloadProdottoInput;

interface IProdottoService {

    /** @return ProdottoDTO[] */
    public function getAll(): array;

    public function getByCod(GetProdottoByCodInput $input): ?ProdottoDTO;

    /** @return ProdottoDTO[] */
    public function getByCategoria(GetProdottoByCategoriaInput $input): array;

    public function createProdotto(CreateProdottoInput $input): void;

    public function updateProdotto(UpdateProdottoInput $input): void;

    public function deleteProdotto(DeleteProdottoInput $input): void;

    /** @return AttrProdDTO[] */
    public function getAttributi(GetAttributiDiProdottoInput $input): array;

    public function loadProdotto(LoadProdottoInput $input): void;

    public function unloadProdotto(UnloadProdottoInput $input): ScaricoProdottoResultDTO;

    /** @return ProdottoDTO[] */
    public function searchWithStock(): array;
}
