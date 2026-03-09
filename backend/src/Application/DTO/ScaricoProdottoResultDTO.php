<?php declare(strict_types=1);
namespace src\Application\DTO;

class ScaricoProdottoResultDTO {

    public readonly bool $sottoSoglia;
    public readonly int $qtaRiordino;
    public readonly int $giacenzaTotale;

    public function __construct(bool $sottoSoglia, int $qtaRiordino, int $giacenzaTotale) {
        $this->sottoSoglia = $sottoSoglia;
        $this->qtaRiordino = $qtaRiordino;
        $this->giacenzaTotale = $giacenzaTotale;
    }
}
