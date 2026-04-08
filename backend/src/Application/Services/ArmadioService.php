<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Armadio;
use src\Domain\Models\Posizione;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Armadio\ArmadioDescrizione;
use src\Domain\ValueObjects\Posizione\ScaffaleId;
use src\Domain\ValueObjects\Posizione\PosizioneDescrizione;
use src\Application\Interfaces\IServices\IArmadioService;
use src\Application\DTO\ArmadioDTO;
use src\Application\DTO\PosizioneDTO;
use src\Application\Interfaces\IRepositories\IArmadioRepository;

class ArmadioService implements IArmadioService {

    private IArmadioRepository $armadioRepository;

    public function __construct(IArmadioRepository $armadioRepository) {
        $this->armadioRepository = $armadioRepository;
    }

    private function armadioToDTO(Armadio $armadio): ArmadioDTO {
        return new ArmadioDTO(
            (string) $armadio->getCodArmadio(),
            $armadio->getDescrizione()?->value
        );
    }

    private function posizioneToDTO(Posizione $posizione): PosizioneDTO {
        return new PosizioneDTO(
            (string) $posizione->getCodArmadio(),
            (string) $posizione->getCodScaffale(),
            $posizione->getDescrizione()?->value
        );
    }

    // ── CRUD Armadio ──

    /** @return ArmadioDTO[] */
    public function getAllArmadi(): array {
        return array_map(fn(Armadio $a) => $this->armadioToDTO($a), $this->armadioRepository->findAllArmadi());
    }

    public function getArmadio(string $codArmadio): ?ArmadioDTO {
        $armadio = $this->armadioRepository->findArmadio(new ArmadioId($codArmadio));
        return $armadio !== null ? $this->armadioToDTO($armadio) : null;
    }

    public function createArmadio(string $codArmadio, ?string $descrizione = null): void {
        if ($this->armadioRepository->findArmadio(new ArmadioId($codArmadio)) !== null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' già esistente.");
        }
        $armadio = new Armadio(new ArmadioId($codArmadio), $descrizione !== null ? new ArmadioDescrizione($descrizione) : null);
        $this->armadioRepository->saveArmadio($armadio);
    }

    public function updateArmadio(string $codArmadio, ?string $descrizione): void {
        $armadio = $this->armadioRepository->findArmadio(new ArmadioId($codArmadio));
        if ($armadio === null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' non trovato.");
        }
        $armadio->setDescrizione($descrizione !== null ? new ArmadioDescrizione($descrizione) : null);
        $this->armadioRepository->saveArmadio($armadio);
    }

    public function deleteArmadio(string $codArmadio): void {
        if ($this->armadioRepository->findArmadio(new ArmadioId($codArmadio)) === null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' non trovato.");
        }
        $this->armadioRepository->deleteArmadio(new ArmadioId($codArmadio));
    }

    // ── Posizione ──

    /** @return PosizioneDTO[] */
    public function getPosizioniByArmadio(string $codArmadio): array {
        return array_map(fn(Posizione $p) => $this->posizioneToDTO($p), $this->armadioRepository->findPosizioniByArmadio(new ArmadioId($codArmadio)));
    }

    public function getPosizione(string $codArmadio, string $codScaffale): ?PosizioneDTO {
        $posizione = $this->armadioRepository->findPosizione(new ArmadioId($codArmadio), new ScaffaleId($codScaffale));
        return $posizione !== null ? $this->posizioneToDTO($posizione) : null;
    }

    public function createPosizione(string $codArmadio, string $codScaffale, ?string $descrizione = null): void {
        if ($this->armadioRepository->findArmadio(new ArmadioId($codArmadio)) === null) {
            throw new \RuntimeException("Armadio '{$codArmadio}' non trovato.");
        }
        if ($this->armadioRepository->findPosizione(new ArmadioId($codArmadio), new ScaffaleId($codScaffale)) !== null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' già esistente.");
        }
        $posizione = new Posizione(new ArmadioId($codArmadio), new ScaffaleId($codScaffale), $descrizione !== null ? new PosizioneDescrizione($descrizione) : null);
        $this->armadioRepository->savePosizione($posizione);
    }

    public function updatePosizione(string $codArmadio, string $codScaffale, ?string $descrizione): void {
        $posizione = $this->armadioRepository->findPosizione(new ArmadioId($codArmadio), new ScaffaleId($codScaffale));
        if ($posizione === null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' non trovata.");
        }
        $posizione->setDescrizione($descrizione !== null ? new PosizioneDescrizione($descrizione) : null);
        $this->armadioRepository->savePosizione($posizione);
    }

    public function deletePosizione(string $codArmadio, string $codScaffale): void {
        if ($this->armadioRepository->findPosizione(new ArmadioId($codArmadio), new ScaffaleId($codScaffale)) === null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' non trovata.");
        }
        $this->armadioRepository->deletePosizione(new ArmadioId($codArmadio), new ScaffaleId($codScaffale));
    }
}
