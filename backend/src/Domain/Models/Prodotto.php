<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class Prodotto
 *
 * @package src\Domain\Models
 * @property string $codProd
 * @property int $qtaRiordino
 * @property ?string $codCat
 * @property ?string $codReg
 * @property ?string $codOE
 */
class Prodotto {
    private string $codProd;
    private int $qtaRiordino;
    private ?string $codCat;
    private ?string $codReg;
    private ?string $codOE;

    public function __construct(string $codProd, int $qtaRiordino = 0, ?string $codCat = null, ?string $codReg = null, ?string $codOE = null) {
        $this->codProd = $codProd;
        $this->qtaRiordino = $qtaRiordino;
        $this->codCat = $codCat;
        $this->codReg = $codReg;
        $this->codOE = $codOE;
    }

    public static function reconstituteFromDatabase(string $codProd, int $qtaRiordino, ?string $codCat, ?string $codReg, ?string $codOE): self {
        return new self($codProd, $qtaRiordino, $codCat, $codReg, $codOE);
    }

    public function getCodProd(): string {
        return $this->codProd;
    }

    public function setCodProd(string $codProd): void {
        $this->codProd = $codProd;
    }

    public function getQtaRiordino(): int {
        return $this->qtaRiordino;
    }

    public function setQtaRiordino(int $qtaRiordino): void {
        $this->qtaRiordino = $qtaRiordino;
    }

    public function getCodCat(): ?string {
        return $this->codCat;
    }

    public function setCodCat(?string $codCat): void {
        $this->codCat = $codCat;
    }

    public function getCodReg(): ?string {
        return $this->codReg;
    }

    public function setCodReg(?string $codReg): void {
        $this->codReg = $codReg;
    }

    public function getCodOE(): ?string {
        return $this->codOE;
    }

    public function setCodOE(?string $codOE): void {
        $this->codOE = $codOE;
    }

    public function necessitaRiordino(int $giacenzaTotale): bool {
        return $giacenzaTotale < $this->qtaRiordino;
    }
}
