<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Posizione\ScaffaleId;
use src\Domain\ValueObjects\Posizione\PosizioneDescrizione;
/**
 * Class Posizione
 *
 * @package src\Domain\Models
 */
class Posizione {
    private ArmadioId $codArmadio;
    private ScaffaleId $codScaffale;
    private ?PosizioneDescrizione $descrizione;

    public function __construct(ArmadioId $codArmadio, ScaffaleId $codScaffale, ?PosizioneDescrizione $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(ArmadioId $codArmadio, ScaffaleId $codScaffale, ?PosizioneDescrizione $descrizione): self {
        return new self($codArmadio, $codScaffale, $descrizione);
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

    public function getDescrizione(): ?PosizioneDescrizione {
        return $this->descrizione;
    }

    public function setDescrizione(?PosizioneDescrizione $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
