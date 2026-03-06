<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class Posizione
 *
 * @package src\Domain\Models
 * @property string $codArmadio
 * @property string $codScaffale
 * @property ?string $descrizione
 */
class Posizione {
    private string $codArmadio;
    private string $codScaffale;
    private ?string $descrizione;

    public function __construct(string $codArmadio, string $codScaffale, ?string $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(string $codArmadio, string $codScaffale, ?string $descrizione): self {
        return new self($codArmadio, $codScaffale, $descrizione);
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

    public function getDescrizione(): ?string {
        return $this->descrizione;
    }

    public function setDescrizione(?string $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
