<?php declare(strict_types=1);
namespace src\Application\DTO;

class CodificaRegDTO {

    public readonly string $codReg;
    public readonly string $descrizione;

    public function __construct(string $codReg, string $descrizione) {
        $this->codReg = $codReg;
        $this->descrizione = $descrizione;
    }
}
