<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObject\ID;
use src\Domain\ValuesObject\Quantita;
/**
 * Class Prodotto
 *
 * @package src\Domain\Models
 * @property ID $codProd
 * @property Quantita $qtaRiordino
 * @property ?ID $codCat
 * @property ?ID $codReg
 * @property ?ID $codOE
 */
class Prodotto {
    private ID $codProd;
    private Quantita $qtaRiordino;
    private ?ID $codCat;
    private ?ID $codReg;
    private ?ID $codOE;

    public function __construct(ID $codProd, Quantita $qtaRiordino, ?ID $codCat = null, ?ID $codReg = null, ?ID $codOE = null) {
        $this->codProd = $codProd;
        $this->qtaRiordino = $qtaRiordino;
        $this->codCat = $codCat;
        $this->codReg = $codReg;
        $this->codOE = $codOE;
    }

    public static function reconstituteFromDatabase(ID $codProd, Quantita $qtaRiordino, ?ID $codCat, ?ID $codReg, ?ID $codOE): self {
        return new self($codProd, $qtaRiordino, $codCat, $codReg, $codOE);
    }

    public function getCodProd(): ID {
        return $this->codProd;
    }

    public function setCodProd(ID $codProd): void {
        $this->codProd = $codProd;
    }

    public function getQtaRiordino(): Quantita {
        return $this->qtaRiordino;
    }

    public function setQtaRiordino(Quantita $qtaRiordino): void {
        $this->qtaRiordino = $qtaRiordino;
    }

    public function getCodCat(): ?ID {
        return $this->codCat;
    }

    public function setCodCat(?ID $codCat): void {
        $this->codCat = $codCat;
    }

    public function getCodReg(): ?ID {
        return $this->codReg;
    }

    public function setCodReg(?ID $codReg): void {
        $this->codReg = $codReg;
    }

    public function getCodOE(): ?ID {
        return $this->codOE;
    }

    public function setCodOE(?ID $codOE): void {
        $this->codOE = $codOE;
    }

    public function necessitaRiordino(int $giacenzaTotale): bool {
        return $giacenzaTotale < $this->qtaRiordino->getValore();
    }
}
