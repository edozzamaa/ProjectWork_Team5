<?php declare(strict_types=1);
namespace src\Application\DTO;

class AttributoDTO {

    public readonly string $codAttr;
    public readonly string $nome;

    public function __construct(string $codAttr, string $nome) {
        $this->codAttr = $codAttr;
        $this->nome = $nome;
    }
}
