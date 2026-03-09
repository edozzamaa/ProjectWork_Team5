<?php declare(strict_types=1);
namespace src\Application\DTO;

class ArmadioDTO {

    public readonly string $codArmadio;
    public readonly ?string $descrizione;

    public function __construct(string $codArmadio, ?string $descrizione = null) {
        $this->codArmadio = $codArmadio;
        $this->descrizione = $descrizione;
    }
}
