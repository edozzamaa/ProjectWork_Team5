<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Armadio\ArmadioDescrizione;
/**
 * Class Armadio
 *
 * @package src\Domain\Models
 */
class Armadio {
    private ArmadioId $codArmadio;
    private ?ArmadioDescrizione $descrizione;

    public function __construct(ArmadioId $codArmadio, ?ArmadioDescrizione $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(ArmadioId $codArmadio, ?ArmadioDescrizione $descrizione): self {
        return new self($codArmadio, $descrizione);
    }

    public function getCodArmadio(): ArmadioId {
        return $this->codArmadio;
    }

    public function setCodArmadio(ArmadioId $codArmadio): void {
        $this->codArmadio = $codArmadio;
    }

    public function getDescrizione(): ?ArmadioDescrizione {
        return $this->descrizione;
    }

    public function setDescrizione(?ArmadioDescrizione $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
