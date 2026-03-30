<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\CodificaReg\CodificaRegId;
use src\Domain\ValueObjects\CodificaReg\CodificaRegDescrizione;
/**
 * Class CodificaReg
 *
 * @package src\Domain\Models
 */
class CodificaReg {
    private CodificaRegId $codReg;
    private CodificaRegDescrizione $descrizione;

    public function __construct(CodificaRegId $codReg, CodificaRegDescrizione $descrizione) {
        $this->codReg = $codReg;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(CodificaRegId $codReg, CodificaRegDescrizione $descrizione): self {
        return new self($codReg, $descrizione);
    }

    public function getCodReg(): CodificaRegId {
        return $this->codReg;
    }

    public function setCodReg(CodificaRegId $codReg): void {
        $this->codReg = $codReg;
    }

    public function getDescrizione(): CodificaRegDescrizione {
        return $this->descrizione;
    }

    public function setDescrizione(CodificaRegDescrizione $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
