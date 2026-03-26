<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObject\ID;
/**
 * Class AttrProd
 *
 * @package src\Domain\Models
 * @property ID $codProd
 * @property ID $codAttr
 * @property ?string $valore
 */
class AttrProd {
    private ID $codProd;
    private ID $codAttr;
    private ?string $valore;

    public function __construct(ID $codProd, ID $codAttr, ?string $valore = null) {
        $this->codProd = $codProd;
        $this->codAttr = $codAttr;
        $this->valore = $valore;
    }

    public static function reconstituteFromDatabase(ID $codProd, ID $codAttr, ?string $valore): self {
        return new self($codProd, $codAttr, $valore);
    }

    public function getCodProd(): ID {
        return $this->codProd;
    }

    public function setCodProd(ID $codProd): void {
        $this->codProd = $codProd;
    }

    public function getCodAttr(): ID {
        return $this->codAttr;
    }

    public function setCodAttr(ID $codAttr): void {
        $this->codAttr = $codAttr;
    }

    public function getValore(): ?string {
        return $this->valore;
    }

    public function setValore(?string $valore): void {
        $this->valore = $valore;
    }
}
