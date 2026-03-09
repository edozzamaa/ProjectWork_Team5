<?php declare(strict_types=1);
namespace src\Application\DTO;

class PosizioneDTO {

    public readonly string $codArmadio;
    public readonly string $codScaffale;
    public readonly ?string $descrizione;

    public function __construct(string $codArmadio, string $codScaffale, ?string $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->codScaffale = $codScaffale;
        $this->descrizione = $descrizione;
    }
}
