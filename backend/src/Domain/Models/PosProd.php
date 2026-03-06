<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class PosProd
 *
 * @package src\Domain\Models
 * @property string $codProd
 * @property string $codArmadio
 * @property string $codScaffale
 * @property int $qta
 */
class PosProd {
    private string $codProd;
    private string $codArmadio;
    private string $codScaffale;
    private int $qta;

    public function __construct(string $codProd, string $codArmadio, string $codScaffale, int $qta = 0) {
        $this->codProd = $codProd;
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->qta = $qta;
    }

    public static function reconstituteFromDatabase(string $codProd, string $codArmadio, string $codScaffale, int $qta): self {
        return new self($codProd, $codArmadio, $codScaffale, $qta);
    }

    public function getCodProd(): string {
        return $this->codProd;
    }

    public function setCodProd(string $codProd): void {
        $this->codProd = $codProd;
    }

    public function getCodArmadio(): string {
        return $this->codArmadio;
    }

    public function setCodArmadio(string $codArmadio): void {
        $this->codArmadio = $codArmadio;
    }

    public function getCodScaffale(): string {
        return $this->codScaffale;
    }

    public function setCodScaffale(string $codScaffale): void {
        $this->codScaffale = $codScaffale;
    }

    public function getQta(): int {
        return $this->qta;
    }

    public function setQta(int $qta): void {
        $this->qta = $qta;
    }
}
