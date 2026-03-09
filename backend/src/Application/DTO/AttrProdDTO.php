<?php declare(strict_types=1);
namespace src\Application\DTO;

class AttrProdDTO {

    public readonly string $codProd;
    public readonly string $codAttr;
    public readonly ?string $valore;

    public function __construct(string $codProd, string $codAttr, ?string $valore = null) {
        $this->codProd = $codProd;
        $this->codAttr = $codAttr;
        $this->valore = $valore;
    }
}
