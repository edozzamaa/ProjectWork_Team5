<?php declare(strict_types=1);
namespace src\Application\DTO;

class ReportProdottoDTO {

    public readonly string $codProd;
    public readonly int $qtaRiordino;
    public readonly int $giacenzaTotale;
    public readonly bool $sottoSoglia;
    public readonly ?string $codCat;
    public readonly ?string $codReg;
    public readonly ?string $codOE;

    public function __construct(
        string $codProd,
        int $qtaRiordino,
        int $giacenzaTotale,
        bool $sottoSoglia,
        ?string $codCat = null,
        ?string $codReg = null,
        ?string $codOE = null
    ) {
        $this->codProd = $codProd;
        $this->qtaRiordino = $qtaRiordino;
        $this->giacenzaTotale = $giacenzaTotale;
        $this->sottoSoglia = $sottoSoglia;
        $this->codCat = $codCat;
        $this->codReg = $codReg;
        $this->codOE = $codOE;
    }
}
