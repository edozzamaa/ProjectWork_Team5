<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class AttrProd
 *
 * @package src\Domain\Models
 * @property string $codProd
 * @property string $codAttr
 * @property ?string $valore
 */
class AttrProd {
    private string $codProd;
    private string $codAttr;
    private ?string $valore;

    public function __construct(string $codProd, string $codAttr, ?string $valore = null) {
        $this->codProd = $codProd;
        $this->codAttr = $codAttr;
        $this->valore = $valore;
    }

    public static function reconstituteFromDatabase(string $codProd, string $codAttr, ?string $valore): self {
        return new self($codProd, $codAttr, $valore);
    }

    public function getCodProd(): string {
        return $this->codProd;
    }

    public function setCodProd(string $codProd): void {
        $this->codProd = $codProd;
    }

    public function getCodAttr(): string {
        return $this->codAttr;
    }

    public function setCodAttr(string $codAttr): void {
        $this->codAttr = $codAttr;
    }

    public function getValore(): ?string {
        return $this->valore;
    }

    public function setValore(?string $valore): void {
        $this->valore = $valore;
    }
}
