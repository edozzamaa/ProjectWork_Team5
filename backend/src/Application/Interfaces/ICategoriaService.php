<?php declare(strict_types=1);
namespace src\Application\Interfaces;

use src\Application\DTO\CategoriaDTO;

interface ICategoriaService {

    /** @return CategoriaDTO[] */
    public function getAll(): array;

    public function getByCod(string $codCat): ?CategoriaDTO;

    public function crea(string $codCat, string $tipo): void;

    public function aggiorna(string $codCat, string $tipo): void;

    public function elimina(string $codCat): void;
}
