<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObjects\ID;
/**
 * Class Posizione
 *
 * @package src\Domain\Models
 * @property ID $codArmadio
 * @property ID $codScaffale
 * @property ?string $descrizione
 */
class Posizione {
    private ID $codArmadio;
    private ID $codScaffale;
    private ?string $descrizione;

    public function __construct(ID $codArmadio, ID $codScaffale, ?string $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(ID $codArmadio, ID $codScaffale, ?string $descrizione): self {
        return new self($codArmadio, $codScaffale, $descrizione);
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

    public function getDescrizione(): ?string {
        return $this->descrizione;
    }

    public function setDescrizione(?string $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
