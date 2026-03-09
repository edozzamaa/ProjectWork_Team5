<?php declare(strict_types=1);
namespace src\Application\DTO;

class CodificaOEDTO {

    public readonly string $codOE;
    public readonly string $descrizione;
    public readonly ?string $ragSoc;

    public function __construct(string $codOE, string $descrizione, ?string $ragSoc = null) {
        $this->codOE = $codOE;
        $this->descrizione = $descrizione;
        $this->ragSoc = $ragSoc;
    }
}
