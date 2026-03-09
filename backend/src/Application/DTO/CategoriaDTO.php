<?php declare(strict_types=1);
namespace src\Application\DTO;

class CategoriaDTO {

    public readonly string $codCat;
    public readonly string $tipo;

    public function __construct(string $codCat, string $tipo) {
        $this->codCat = $codCat;
        $this->tipo = $tipo;
    }
}
