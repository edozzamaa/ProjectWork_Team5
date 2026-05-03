<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\Output\AttributoDTO;
use src\Application\DTO\Output\AttrProdDTO;
use src\Application\DTO\Input\GetAttributoByCodInput;
use src\Application\DTO\Input\CreateAttributoInput;
use src\Application\DTO\Input\UpdateAttributoInput;
use src\Application\DTO\Input\DeleteAttributoInput;
use src\Application\DTO\Input\AssignAttributoToProdottoInput;
use src\Application\DTO\Input\RemoveAttributoFromProdottoInput;
use src\Application\DTO\Input\GetAttributiProdottoInput;

interface IAttributoService {

    // ── CRUD Attributo ──

    /** @return AttributoDTO[] */
    public function getAll(): array;

    public function getByCod(GetAttributoByCodInput $input): ?AttributoDTO;

    public function createAttributo(CreateAttributoInput $input): void;

    public function updateAttributo(UpdateAttributoInput $input): void;

    public function deleteAttributo(DeleteAttributoInput $input): void;

    // ── Assegnazione Attributi a Prodotto ──

    public function assignToProdotto(AssignAttributoToProdottoInput $input): void;

    public function removeFromProdotto(RemoveAttributoFromProdottoInput $input): void;

    /** @return AttrProdDTO[] */
    public function getAttributiProdotto(GetAttributiProdottoInput $input): array;
}
