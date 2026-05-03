<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\Output\CategoriaDTO;
use src\Application\DTO\Input\GetCategoriaByCodInput;
use src\Application\DTO\Input\CreateCategoriaInput;
use src\Application\DTO\Input\UpdateCategoriaInput;
use src\Application\DTO\Input\DeleteCategoriaInput;

interface ICategoriaService {

    /** @return CategoriaDTO[] */
    public function getAll(): array;

    public function getByCod(GetCategoriaByCodInput $input): ?CategoriaDTO;

    public function createCategoria(CreateCategoriaInput $input): void;

    public function updateCategoria(UpdateCategoriaInput $input): void;

    public function deleteCategoria(DeleteCategoriaInput $input): void;
}
