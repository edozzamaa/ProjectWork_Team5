<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObjects\ID;
/**
 * Class Armadio
 *
 * @package src\Domain\Models
 * @property ID $codArmadio
 * @property ?string $descrizione
 */
class Armadio {
    private ID $codArmadio;
    private ?string $descrizione;

    public function __construct(ID $codArmadio, ?string $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(ID $codArmadio, ?string $descrizione): self {
        return new self($codArmadio, $descrizione);
    }

    public function getCodArmadio(): ID {
        return $this->codArmadio;
    }

    public function setCodArmadio(ID $codArmadio): void {
        $this->codArmadio = $codArmadio;
    }

    public function getDescrizione(): ?string {
        return $this->descrizione;
    }

    public function setDescrizione(?string $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
