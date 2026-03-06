<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class CodificaOE
 *
 * @package src\Domain\Models
 * @property string $codOE
 * @property string $descrizione
 * @property ?string $ragSoc
 */
class CodificaOE {
    private string $codOE;
    private string $descrizione;
    private ?string $ragSoc;

    public function __construct(string $codOE, string $descrizione, ?string $ragSoc = null) {
        $this->codOE = $codOE;
        $this->descrizione = $descrizione;
        $this->ragSoc = $ragSoc;
    }

    public static function reconstituteFromDatabase(string $codOE, string $descrizione, ?string $ragSoc): self {
        return new self($codOE, $descrizione, $ragSoc);
    }

    public function getCodOE(): string {
        return $this->codOE;
    }

    public function setCodOE(string $codOE): void {
        $this->codOE = $codOE;
    }

    public function getDescrizione(): string {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): void {
        $this->descrizione = $descrizione;
    }

    public function getRagSoc(): ?string {
        return $this->ragSoc;
    }

    public function setRagSoc(?string $ragSoc): void {
        $this->ragSoc = $ragSoc;
    }
}
