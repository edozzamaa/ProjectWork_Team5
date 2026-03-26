<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObject\ID;
use src\Domain\ValuesObject\Quantita;
/**
 * Class PosProd
 *
 * @package src\Domain\Models
 * @property ID $codProd
 * @property ID $codArmadio
 * @property ID $codScaffale
 * @property Quantita $qta
 */
class PosProd {
    private ID $codProd;
    private ID $codArmadio;
    private ID $codScaffale;
    private Quantita $qta;

    public function __construct(ID $codProd, ID $codArmadio, ID $codScaffale, Quantita $qta) {
        $this->codProd = $codProd;
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->qta = $qta;
    }

    public static function reconstituteFromDatabase(ID $codProd, ID $codArmadio, ID $codScaffale, Quantita $qta): self {
        return new self($codProd, $codArmadio, $codScaffale, $qta);
    }

    public function getCodProd(): ID {
        return $this->codProd;
    }

    public function setCodProd(ID $codProd): void {
        $this->codProd = $codProd;
    }

    public function getCodArmadio(): ID {
        return $this->codArmadio;
    }

    public function setCodArmadio(ID $codArmadio): void {
        $this->codArmadio = $codArmadio;
    }

    public function getCodScaffale(): ID {
        return $this->codScaffale;
    }

    public function setCodScaffale(ID $codScaffale): void {
        $this->codScaffale = $codScaffale;
    }

    public function getQta(): Quantita {
        return $this->qta;
    }

    public function setQta(Quantita $qta): void {
        $this->qta = $qta;
    }
}
