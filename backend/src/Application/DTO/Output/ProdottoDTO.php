<?php declare(strict_types=1);
namespace src\Application\DTO\Output;

class ProdottoDTO {

    public readonly string $codProd;
    public readonly int $qtaRiordino;
    public readonly ?string $codCat;
    public readonly ?string $codReg;
    public readonly ?string $codOE;
    public readonly ?int $giacenzaTotale;
    /** @var AttrProdDTO[] */
    public readonly array $attributi;

    /** @param AttrProdDTO[] $attributi */
    public function __construct(
        string $codProd,
        int $qtaRiordino,
        ?string $codCat = null,
        ?string $codReg = null,
        ?string $codOE = null,
        ?int $giacenzaTotale = null,
        array $attributi = []
    ) {
        $this->codProd = $codProd;
        $this->qtaRiordino = $qtaRiordino;
        $this->codCat = $codCat;
        $this->codReg = $codReg;
        $this->codOE = $codOE;
        $this->giacenzaTotale = $giacenzaTotale;
        $this->attributi = $attributi;
    }
}
