<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Posizione\ScaffaleId;
use src\Domain\ValueObjects\PosProd\Quantita;
/**
 * Class PosProd
 *
 * @package src\Domain\Models
 */
class PosProd {
    private ProdottoId $codProd;
    private ArmadioId $codArmadio;
    private ScaffaleId $codScaffale;
    private Quantita $qta;

    public function __construct(ProdottoId $codProd, ArmadioId $codArmadio, ScaffaleId $codScaffale, Quantita $qta) {
        $this->codProd = $codProd;
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->qta = $qta;
    }

    public static function reconstituteFromDatabase(ProdottoId $codProd, ArmadioId $codArmadio, ScaffaleId $codScaffale, Quantita $qta): self {
        return new self($codProd, $codArmadio, $codScaffale, $qta);
    }

    public function getCodProd(): ProdottoId {
        return $this->codProd;
    }

    public function setCodProd(ProdottoId $codProd): void {
        $this->codProd = $codProd;
    }

    public function getCodArmadio(): ArmadioId {
        return $this->codArmadio;
    }

    public function setCodArmadio(ArmadioId $codArmadio): void {
        $this->codArmadio = $codArmadio;
    }

    public function getCodScaffale(): ScaffaleId {
        return $this->codScaffale;
    }

    public function setCodScaffale(ScaffaleId $codScaffale): void {
        $this->codScaffale = $codScaffale;
    }

    public function getQta(): Quantita {
        return $this->qta;
    }

    public function setQta(Quantita $qta): void {
        $this->qta = $qta;
    }
}
