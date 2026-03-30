<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\CodificaOE\CodificaOEId;
use src\Domain\ValueObjects\CodificaOE\CodificaOEDescrizione;
use src\Domain\ValueObjects\Fornitore\FornitoreId;
/**
 * Class CodificaOE
 *
 * @package src\Domain\Models
 */
class CodificaOE {
    private CodificaOEId $codOE;
    private CodificaOEDescrizione $descrizione;
    private ?FornitoreId $ragSoc;

    public function __construct(CodificaOEId $codOE, CodificaOEDescrizione $descrizione, ?FornitoreId $ragSoc = null) {
        $this->codOE = $codOE;
        $this->descrizione = $descrizione;
        $this->ragSoc = $ragSoc;
    }

    public static function reconstituteFromDatabase(CodificaOEId $codOE, CodificaOEDescrizione $descrizione, ?FornitoreId $ragSoc): self {
        return new self($codOE, $descrizione, $ragSoc);
    }

    public function getCodOE(): CodificaOEId {
        return $this->codOE;
    }

    public function setCodOE(CodificaOEId $codOE): void {
        $this->codOE = $codOE;
    }

    public function getDescrizione(): CodificaOEDescrizione {
        return $this->descrizione;
    }

    public function setDescrizione(CodificaOEDescrizione $descrizione): void {
        $this->descrizione = $descrizione;
    }

    public function getRagSoc(): ?FornitoreId {
        return $this->ragSoc;
    }

    public function setRagSoc(?FornitoreId $ragSoc): void {
        $this->ragSoc = $ragSoc;
    }
}
