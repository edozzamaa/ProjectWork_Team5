<?php declare(strict_types=1);
namespace src\Application\DTO;

class PosProdDTO {

    public readonly string $codProd;
    public readonly string $codArmadio;
    public readonly string $codScaffale;
    public readonly int $qta;

    public function __construct(string $codProd, string $codArmadio, string $codScaffale, int $qta) {
        $this->codProd = $codProd;
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->qta = $qta;
    }
}
