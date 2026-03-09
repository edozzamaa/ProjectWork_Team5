<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObjects\ID;
/**
 * Class CodificaReg
 *
 * @package src\Domain\Models
 * @property ID $codReg
 * @property string $descrizione
 */
class CodificaReg {
    private ID $codReg;
    private string $descrizione;

    public function __construct(ID $codReg, string $descrizione) {
        $this->codReg = $codReg;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(ID $codReg, string $descrizione): self {
        return new self($codReg, $descrizione);
    }

    public function getCodReg(): ID {
        return $this->codReg;
    }

    public function setCodReg(ID $codReg): void {
        $this->codReg = $codReg;
    }

    public function getDescrizione(): string {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
