<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObject\ID;
/**
 * Class CodificaOE
 *
 * @package src\Domain\Models
 * @property ID $codOE
 * @property string $descrizione
 * @property ?ID $ragSoc
 */
class CodificaOE {
    private ID $codOE;
    private string $descrizione;
    private ?ID $ragSoc;

    public function __construct(ID $codOE, string $descrizione, ?ID $ragSoc = null) {
        $this->codOE = $codOE;
        $this->descrizione = $descrizione;
        $this->ragSoc = $ragSoc;
    }

    public static function reconstituteFromDatabase(ID $codOE, string $descrizione, ?ID $ragSoc): self {
        return new self($codOE, $descrizione, $ragSoc);
    }

    public function getCodOE(): ID {
        return $this->codOE;
    }

    public function setCodOE(ID $codOE): void {
        $this->codOE = $codOE;
    }

    public function getDescrizione(): string {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): void {
        $this->descrizione = $descrizione;
    }

    public function getRagSoc(): ?ID {
        return $this->ragSoc;
    }

    public function setRagSoc(?ID $ragSoc): void {
        $this->ragSoc = $ragSoc;
    }
}
