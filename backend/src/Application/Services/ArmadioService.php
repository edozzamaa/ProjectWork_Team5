<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Armadio;
use src\Domain\Models\Posizione;
use src\Domain\ValuesObject\ID;
use src\Application\Interfaces\IArmadioService;
use src\Application\DTO\ArmadioDTO;
use src\Application\DTO\PosizioneDTO;
use src\Infrastructure\Repositories\ArmadioRepository;

class ArmadioService implements IArmadioService {

    private ArmadioRepository $armadioRepository;

    public function __construct(ArmadioRepository $armadioRepository) {
        $this->armadioRepository = $armadioRepository;
    }

    private function armadioToDTO(Armadio $armadio): ArmadioDTO {
        return new ArmadioDTO(
            (string) $armadio->getCodArmadio(),
            $armadio->getDescrizione()
        );
    }

    private function posizioneToDTO(Posizione $posizione): PosizioneDTO {
        return new PosizioneDTO(
            (string) $posizione->getCodArmadio(),
            (string) $posizione->getCodScaffale(),
            $posizione->getDescrizione()
        );
    }

    // ── Armadio ──

    /** @return ArmadioDTO[] */
    public function getAllArmadi(): array {
        return array_map(fn(Armadio $a) => $this->armadioToDTO($a), $this->armadioRepository->findAllArmadi());
    }

    public function getArmadio(string $codArmadio): ?ArmadioDTO {
        $armadio = $this->armadioRepository->findArmadio(new ID($codArmadio));
        return $armadio !== null ? $this->armadioToDTO($armadio) : null;
    }

    public function creaArmadio(string $codArmadio, ?string $descrizione = null): void {
        if ($this->armadioRepository->findArmadio(new ID($codArmadio)) !== null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' già esistente.");
        }
        $armadio = new Armadio(new ID($codArmadio), $descrizione);
        $this->armadioRepository->saveArmadio($armadio);
    }

    public function aggiornaArmadio(string $codArmadio, ?string $descrizione): void {
        $armadio = $this->armadioRepository->findArmadio(new ID($codArmadio));
        if ($armadio === null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' non trovato.");
        }
        $armadio->setDescrizione($descrizione);
        $this->armadioRepository->saveArmadio($armadio);
    }

    public function eliminaArmadio(string $codArmadio): void {
        if ($this->armadioRepository->findArmadio(new ID($codArmadio)) === null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' non trovato.");
        }
        $this->armadioRepository->deleteArmadio(new ID($codArmadio));
    }

    // ── Posizione ──

    /** @return PosizioneDTO[] */
    public function getPosizioniByArmadio(string $codArmadio): array {
        return array_map(fn(Posizione $p) => $this->posizioneToDTO($p), $this->armadioRepository->findPosizioniByArmadio(new ID($codArmadio)));
    }

    public function getPosizione(string $codArmadio, string $codScaffale): ?PosizioneDTO {
        $posizione = $this->armadioRepository->findPosizione(new ID($codArmadio), new ID($codScaffale));
        return $posizione !== null ? $this->posizioneToDTO($posizione) : null;
    }

    public function creaPosizione(string $codArmadio, string $codScaffale, ?string $descrizione = null): void {
        if ($this->armadioRepository->findArmadio(new ID($codArmadio)) === null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' non trovato.");
        }
        if ($this->armadioRepository->findPosizione(new ID($codArmadio), new ID($codScaffale)) !== null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' già esistente.");
        }
        $posizione = new Posizione(new ID($codArmadio), new ID($codScaffale), $descrizione);
        $this->armadioRepository->savePosizione($posizione);
    }

    public function aggiornaPosizione(string $codArmadio, string $codScaffale, ?string $descrizione): void {
        $posizione = $this->armadioRepository->findPosizione(new ID($codArmadio), new ID($codScaffale));
        if ($posizione === null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' non trovata.");
        }
        $posizione->setDescrizione($descrizione);
        $this->armadioRepository->savePosizione($posizione);
    }

    public function eliminaPosizione(string $codArmadio, string $codScaffale): void {
        if ($this->armadioRepository->findPosizione(new ID($codArmadio), new ID($codScaffale)) === null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' non trovata.");
        }
        $this->armadioRepository->deletePosizione(new ID($codArmadio), new ID($codScaffale));
    }
}
