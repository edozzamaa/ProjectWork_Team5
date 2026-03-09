<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\CodificaReg;
use src\Domain\Models\CodificaOE;
use src\Domain\ValuesObject\ID;
use src\Application\Interfaces\ICodificaService;
use src\Application\DTO\CodificaRegDTO;
use src\Application\DTO\CodificaOEDTO;
use src\Infrastructure\Repositories\CodificaRegRepository;
use src\Infrastructure\Repositories\CodificaOERepository;

class CodificaService implements ICodificaService {

    private CodificaRegRepository $codificaRegRepository;
    private CodificaOERepository $codificaOERepository;

    public function __construct(
        CodificaRegRepository $codificaRegRepository,
        CodificaOERepository $codificaOERepository
    ) {
        $this->codificaRegRepository = $codificaRegRepository;
        $this->codificaOERepository = $codificaOERepository;
    }

    private function regToDTO(CodificaReg $codifica): CodificaRegDTO {
        return new CodificaRegDTO(
            (string) $codifica->getCodReg(),
            $codifica->getDescrizione()
        );
    }

    private function oeToDTO(CodificaOE $codifica): CodificaOEDTO {
        return new CodificaOEDTO(
            (string) $codifica->getCodOE(),
            $codifica->getDescrizione(),
            $codifica->getRagSoc() !== null ? (string) $codifica->getRagSoc() : null
        );
    }

    // ── Codifica Regionale ──

    /** @return CodificaRegDTO[] */
    public function getAllReg(): array {
        return array_map(fn(CodificaReg $c) => $this->regToDTO($c), $this->codificaRegRepository->findAll());
    }

    public function getRegByCod(string $codReg): ?CodificaRegDTO {
        $codifica = $this->codificaRegRepository->findByCod(new ID($codReg));
        return $codifica !== null ? $this->regToDTO($codifica) : null;
    }

    public function creaReg(string $codReg, string $descrizione): void {
        if ($this->codificaRegRepository->findByCod(new ID($codReg)) !== null) {
            throw new \RuntimeException("Codifica regionale '{$codReg}' già esistente.");
        }
        $codifica = new CodificaReg(new ID($codReg), $descrizione);
        $this->codificaRegRepository->save($codifica);
    }

    public function aggiornaReg(string $codReg, string $descrizione): void {
        $codifica = $this->codificaRegRepository->findByCod(new ID($codReg));
        if ($codifica === null) {
            throw new \RuntimeException("Codifica regionale '{$codReg}' non trovata.");
        }
        $codifica->setDescrizione($descrizione);
        $this->codificaRegRepository->save($codifica);
    }

    public function eliminaReg(string $codReg): void {
        if ($this->codificaRegRepository->findByCod(new ID($codReg)) === null) {
            throw new \RuntimeException("Codifica regionale '{$codReg}' non trovata.");
        }
        $this->codificaRegRepository->delete(new ID($codReg));
    }

    // ── Codifica OE ──

    /** @return CodificaOEDTO[] */
    public function getAllOE(): array {
        return array_map(fn(CodificaOE $c) => $this->oeToDTO($c), $this->codificaOERepository->findAll());
    }

    public function getOEByCod(string $codOE): ?CodificaOEDTO {
        $codifica = $this->codificaOERepository->findByCod(new ID($codOE));
        return $codifica !== null ? $this->oeToDTO($codifica) : null;
    }

    /** @return CodificaOEDTO[] */
    public function getOEByFornitore(string $ragSoc): array {
        return array_map(fn(CodificaOE $c) => $this->oeToDTO($c), $this->codificaOERepository->findByFornitore(new ID($ragSoc)));
    }

    public function creaOE(string $codOE, string $descrizione, ?string $ragSoc = null): void {
        if ($this->codificaOERepository->findByCod(new ID($codOE)) !== null) {
            throw new \RuntimeException("Codifica OE '{$codOE}' già esistente.");
        }
        $codifica = new CodificaOE(
            new ID($codOE),
            $descrizione,
            $ragSoc !== null ? new ID($ragSoc) : null
        );
        $this->codificaOERepository->save($codifica);
    }

    public function aggiornaOE(string $codOE, string $descrizione, ?string $ragSoc = null): void {
        $codifica = $this->codificaOERepository->findByCod(new ID($codOE));
        if ($codifica === null) {
            throw new \RuntimeException("Codifica OE '{$codOE}' non trovata.");
        }
        $codifica->setDescrizione($descrizione);
        $codifica->setRagSoc($ragSoc !== null ? new ID($ragSoc) : null);
        $this->codificaOERepository->save($codifica);
    }

    public function eliminaOE(string $codOE): void {
        if ($this->codificaOERepository->findByCod(new ID($codOE)) === null) {
            throw new \RuntimeException("Codifica OE '{$codOE}' non trovata.");
        }
        $this->codificaOERepository->delete(new ID($codOE));
    }
}
