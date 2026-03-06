<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class CodificaReg
 *
 * @package src\Domain\Models
 * @property string $codReg
 * @property string $descrizione
 */
class CodificaReg {
    private string $codReg;
    private string $descrizione;

    public function __construct(string $codReg, string $descrizione) {
        $this->codReg = $codReg;
        $this->descrizione = $descrizione;
    }

    public static function reconstituteFromDatabase(string $codReg, string $descrizione): self {
        return new self($codReg, $descrizione);
    }

    public function getCodReg(): string {
        return $this->codReg;
    }

    public function setCodReg(string $codReg): void {
        $this->codReg = $codReg;
    }

    public function getDescrizione(): string {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): void {
        $this->descrizione = $descrizione;
    }
}
