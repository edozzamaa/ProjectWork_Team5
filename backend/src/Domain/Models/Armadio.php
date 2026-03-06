<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class Armadio
 *
 * @package src\Domain\Models
 * @property string $codArmadio
 * @property ?string $descrizione
 */
class Armadio {
    private string $codArmadio;
    private ?string $descrizione;

    public function __construct(string $codArmadio, ?string $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(string $codArmadio, ?string $descrizione): self {
        return new self($codArmadio, $descrizione);
    }

    public function getCodArmadio(): string {
        return $this->codArmadio;
    }

    public function setCodArmadio(string $codArmadio): void {
        $this->codArmadio = $codArmadio;
    }

    public function getDescrizione(): ?string {
        return $this->descrizione;
    }

    public function setDescrizione(?string $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
