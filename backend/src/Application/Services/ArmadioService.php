<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Armadio;
use src\Domain\Models\Posizione;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Armadio\ArmadioDescrizione;
use src\Domain\ValueObjects\Posizione\ScaffaleId;
use src\Domain\ValueObjects\Posizione\PosizioneDescrizione;
use src\Application\Interfaces\IServices\IArmadioService;
use src\Application\DTO\Output\ArmadioDTO;
use src\Application\DTO\Output\PosizioneDTO;
use src\Application\DTO\Input\GetArmadioInput;
use src\Application\DTO\Input\CreateArmadioInput;
use src\Application\DTO\Input\UpdateArmadioInput;
use src\Application\DTO\Input\DeleteArmadioInput;
use src\Application\DTO\Input\GetPosizioniByArmadioInput;
use src\Application\DTO\Input\GetPosizioneInput;
use src\Application\DTO\Input\CreatePosizioneInput;
use src\Application\DTO\Input\UpdatePosizioneInput;
use src\Application\DTO\Input\DeletePosizioneInput;
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

    public function getArmadio(GetArmadioInput $input): ?ArmadioDTO {
        $armadio = $this->armadioRepository->findArmadio(new ArmadioId($input->codArmadio));
        return $armadio !== null ? $this->armadioToDTO($armadio) : null;
    }

    public function createArmadio(CreateArmadioInput $input): void {
        if ($this->armadioRepository->findArmadio(new ArmadioId($input->codArmadio)) !== null) {
            throw new \RuntimeException("Armadio '{$input->codArmadio}' già esistente.");
        }
        $armadio = new Armadio(new ArmadioId($input->codArmadio), $input->descrizione !== null ? new ArmadioDescrizione($input->descrizione) : null);
        $this->armadioRepository->saveArmadio($armadio);
    }

    public function updateArmadio(UpdateArmadioInput $input): void {
        $armadio = $this->armadioRepository->findArmadio(new ArmadioId($input->codArmadio));
        if ($armadio === null) {
            throw new \RuntimeException("Armadio '{$input->codArmadio}' non trovato.");
        }
        $armadio->setDescrizione($input->descrizione !== null ? new ArmadioDescrizione($input->descrizione) : null);
        $this->armadioRepository->saveArmadio($armadio);
    }

    public function deleteArmadio(DeleteArmadioInput $input): void {
        if ($this->armadioRepository->findArmadio(new ArmadioId($input->codArmadio)) === null) {
            throw new \RuntimeException("Armadio '{$input->codArmadio}' non trovato.");
        }
        $this->armadioRepository->deleteArmadio(new ArmadioId($input->codArmadio));
    }

    // ── Posizione ──

    /** @return PosizioneDTO[] */
    public function getPosizioniByArmadio(GetPosizioniByArmadioInput $input): array {
        return array_map(fn(Posizione $p) => $this->posizioneToDTO($p), $this->armadioRepository->findPosizioniByArmadio(new ArmadioId($input->codArmadio)));
    }

    public function getPosizione(GetPosizioneInput $input): ?PosizioneDTO {
        $posizione = $this->armadioRepository->findPosizione(new ArmadioId($input->codArmadio), new ScaffaleId($input->codScaffale));
        return $posizione !== null ? $this->posizioneToDTO($posizione) : null;
    }

    public function createPosizione(CreatePosizioneInput $input): void {
        if ($this->armadioRepository->findArmadio(new ArmadioId($input->codArmadio)) === null) {
            throw new \RuntimeException("Armadio '{$input->codArmadio}' non trovato.");
        }
        if ($this->armadioRepository->findPosizione(new ArmadioId($input->codArmadio), new ScaffaleId($input->codScaffale)) !== null) {
            throw new \RuntimeException("Posizione '{$input->codArmadio}/{$input->codScaffale}' già esistente.");
        }
        $posizione = new Posizione(new ArmadioId($input->codArmadio), new ScaffaleId($input->codScaffale), $input->descrizione !== null ? new PosizioneDescrizione($input->descrizione) : null);
        $this->armadioRepository->savePosizione($posizione);
    }

    public function updatePosizione(UpdatePosizioneInput $input): void {
        $posizione = $this->armadioRepository->findPosizione(new ArmadioId($input->codArmadio), new ScaffaleId($input->codScaffale));
        if ($posizione === null) {
            throw new \RuntimeException("Posizione '{$input->codArmadio}/{$input->codScaffale}' non trovata.");
        }
        $posizione->setDescrizione($input->descrizione !== null ? new PosizioneDescrizione($input->descrizione) : null);
        $this->armadioRepository->savePosizione($posizione);
    }

    public function deletePosizione(DeletePosizioneInput $input): void {
        if ($this->armadioRepository->findPosizione(new ArmadioId($input->codArmadio), new ScaffaleId($input->codScaffale)) === null) {
            throw new \RuntimeException("Posizione '{$input->codArmadio}/{$input->codScaffale}' non trovata.");
        }
        $this->armadioRepository->deletePosizione(new ArmadioId($input->codArmadio), new ScaffaleId($input->codScaffale));
    }
}
