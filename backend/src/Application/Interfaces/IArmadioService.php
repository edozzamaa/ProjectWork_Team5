<?php declare(strict_types=1);
namespace src\Application\Interfaces;

use src\Application\DTO\ArmadioDTO;
use src\Application\DTO\PosizioneDTO;

interface IArmadioService {

    // ── Armadio ──

    /** @return ArmadioDTO[] */
    public function getAllArmadi(): array;

    public function getArmadio(string $codArmadio): ?ArmadioDTO;

    public function creaArmadio(string $codArmadio, ?string $descrizione = null): void;

    public function aggiornaArmadio(string $codArmadio, ?string $descrizione): void;

    public function eliminaArmadio(string $codArmadio): void;

    // ── Posizione ──

    /** @return PosizioneDTO[] */
    public function getPosizioniByArmadio(string $codArmadio): array;

    public function getPosizione(string $codArmadio, string $codScaffale): ?PosizioneDTO;

    public function creaPosizione(string $codArmadio, string $codScaffale, ?string $descrizione = null): void;

    public function aggiornaPosizione(string $codArmadio, string $codScaffale, ?string $descrizione): void;

    public function eliminaPosizione(string $codArmadio, string $codScaffale): void;
}
