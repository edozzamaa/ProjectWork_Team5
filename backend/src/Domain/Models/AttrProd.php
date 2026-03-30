<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Attributo\AttributoId;
use src\Domain\ValueObjects\AttrProd\ValoreAttributo;
/**
 * Class AttrProd
 *
 * @package src\Domain\Models
 */
class AttrProd {
    private ProdottoId $codProd;
    private AttributoId $codAttr;
    private ?ValoreAttributo $valore;

    public function __construct(ProdottoId $codProd, AttributoId $codAttr, ?ValoreAttributo $valore = null) {
        $this->codProd = $codProd;
        $this->codAttr = $codAttr;
        $this->valore = $valore;
    }

    public static function reconstituteFromDatabase(ProdottoId $codProd, AttributoId $codAttr, ?ValoreAttributo $valore): self {
        return new self($codProd, $codAttr, $valore);
    }

    public function getCodProd(): ProdottoId {
        return $this->codProd;
    }

    public function setCodProd(ProdottoId $codProd): void {
        $this->codProd = $codProd;
    }

    public function getCodAttr(): AttributoId {
        return $this->codAttr;
    }

    public function setCodAttr(AttributoId $codAttr): void {
        $this->codAttr = $codAttr;
    }

    public function getValore(): ?ValoreAttributo {
        return $this->valore;
    }

    public function setValore(?ValoreAttributo $valore): void {
        $this->valore = $valore;
    }
}
