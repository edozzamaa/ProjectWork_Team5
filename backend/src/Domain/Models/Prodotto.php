<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Prodotto\QuantitaRiordino;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\CodificaReg\CodificaRegId;
use src\Domain\ValueObjects\CodificaOE\CodificaOEId;
/**
 * Class Prodotto
 *
 * @package src\Domain\Models
 */
class Prodotto {
    private ProdottoId $codProd;
    private QuantitaRiordino $qtaRiordino;
    private ?CategoriaId $codCat;
    private ?CodificaRegId $codReg;
    private ?CodificaOEId $codOE;

    public function __construct(ProdottoId $codProd, QuantitaRiordino $qtaRiordino, ?CategoriaId $codCat = null, ?CodificaRegId $codReg = null, ?CodificaOEId $codOE = null) {
        $this->codProd = $codProd;
        $this->qtaRiordino = $qtaRiordino;
        $this->codCat = $codCat;
        $this->codReg = $codReg;
        $this->codOE = $codOE;
    }

    public static function reconstituteFromDatabase(ProdottoId $codProd, QuantitaRiordino $qtaRiordino, ?CategoriaId $codCat, ?CodificaRegId $codReg, ?CodificaOEId $codOE): self {
        return new self($codProd, $qtaRiordino, $codCat, $codReg, $codOE);
    }

    public function getCodProd(): ProdottoId {
        return $this->codProd;
    }

    public function setCodProd(ProdottoId $codProd): void {
        $this->codProd = $codProd;
    }

    public function getQtaRiordino(): QuantitaRiordino {
        return $this->qtaRiordino;
    }

    public function setQtaRiordino(QuantitaRiordino $qtaRiordino): void {
        $this->qtaRiordino = $qtaRiordino;
    }

    public function getCodCat(): ?CategoriaId {
        return $this->codCat;
    }

    public function setCodCat(?CategoriaId $codCat): void {
        $this->codCat = $codCat;
    }

    public function getCodReg(): ?CodificaRegId {
        return $this->codReg;
    }

    public function setCodReg(?CodificaRegId $codReg): void {
        $this->codReg = $codReg;
    }

    public function getCodOE(): ?CodificaOEId {
        return $this->codOE;
    }

    public function setCodOE(?CodificaOEId $codOE): void {
        $this->codOE = $codOE;
    }

    public function necessitaRiordino(int $giacenzaTotale): bool {
        return $giacenzaTotale < $this->qtaRiordino->value;
    }
}
