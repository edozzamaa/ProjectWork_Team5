<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\CodificaReg;
use src\Domain\Models\CodificaOE;
use src\Domain\ValueObjects\CodificaReg\CodificaRegId;
use src\Domain\ValueObjects\CodificaReg\CodificaRegDescrizione;
use src\Domain\ValueObjects\CodificaOE\CodificaOEId;
use src\Domain\ValueObjects\CodificaOE\CodificaOEDescrizione;
use src\Domain\ValueObjects\Fornitore\FornitoreId;
use src\Application\Interfaces\IServices\ICodificaService;
use src\Application\DTO\CodificaRegDTO;
use src\Application\DTO\CodificaOEDTO;
use src\Application\Interfaces\IRepositories\ICodificaRegRepository;
use src\Application\Interfaces\IRepositories\ICodificaOERepository;

class CodificaService implements ICodificaService {

    private ICodificaRegRepository $codificaRegRepository;
    private ICodificaOERepository $codificaOERepository;

    public function __construct(
        ICodificaRegRepository $codificaRegRepository,
        ICodificaOERepository $codificaOERepository
    ) {
        $this->codificaRegRepository = $codificaRegRepository;
        $this->codificaOERepository = $codificaOERepository;
    }

    private function regToDTO(CodificaReg $codifica): CodificaRegDTO {
        return new CodificaRegDTO(
            (string) $codifica->getCodReg(),
            $codifica->getDescrizione()->value
        );
    }

    private function oeToDTO(CodificaOE $codifica): CodificaOEDTO {
        return new CodificaOEDTO(
            (string) $codifica->getCodOE(),
            $codifica->getDescrizione()->value,
            $codifica->getRagSoc() !== null ? (string) $codifica->getRagSoc() : null
        );
    }

    // ── Codifica Regionale ──

    /** @return CodificaRegDTO[] */
    public function getAllReg(): array {
        return array_map(fn(CodificaReg $c) => $this->regToDTO($c), $this->codificaRegRepository->findAll());
    }

    public function getRegByCod(string $codReg): ?CodificaRegDTO {
        $codifica = $this->codificaRegRepository->findByCod(new CodificaRegId($codReg));
        return $codifica !== null ? $this->regToDTO($codifica) : null;
    }

    public function createReg(string $codReg, string $descrizione): void {
        if ($this->codificaRegRepository->findByCod(new CodificaRegId($codReg)) !== null) {
            throw new \RuntimeException("Codifica regionale '{$codReg}' già esistente.");
        }
        $codifica = new CodificaReg(new CodificaRegId($codReg), new CodificaRegDescrizione($descrizione));
        $this->codificaRegRepository->save($codifica);
    }

    public function updateReg(string $codReg, string $descrizione): void {
        $codifica = $this->codificaRegRepository->findByCod(new CodificaRegId($codReg));
        if ($codifica === null) {
            throw new \RuntimeException("Codifica regionale '{$codReg}' non trovata.");
        }
        $codifica->setDescrizione(new CodificaRegDescrizione($descrizione));
        $this->codificaRegRepository->save($codifica);
    }

    public function deleteReg(string $codReg): void {
        if ($this->codificaRegRepository->findByCod(new CodificaRegId($codReg)) === null) {
            throw new \RuntimeException("Codifica regionale '{$codReg}' non trovata.");
        }
        $this->codificaRegRepository->delete(new CodificaRegId($codReg));
    }

    // ── Codifica OE ──

    /** @return CodificaOEDTO[] */
    public function getAllOE(): array {
        return array_map(fn(CodificaOE $c) => $this->oeToDTO($c), $this->codificaOERepository->findAll());
    }

    public function getOEByCod(string $codOE): ?CodificaOEDTO {
        $codifica = $this->codificaOERepository->findByCod(new CodificaOEId($codOE));
        return $codifica !== null ? $this->oeToDTO($codifica) : null;
    }

    /** @return CodificaOEDTO[] */
    public function getOEByFornitore(string $ragSoc): array {
        return array_map(fn(CodificaOE $c) => $this->oeToDTO($c), $this->codificaOERepository->findByFornitore(new FornitoreId($ragSoc)));
    }

    public function createOE(string $codOE, string $descrizione, ?string $ragSoc = null): void {
        if ($this->codificaOERepository->findByCod(new CodificaOEId($codOE)) !== null) {
            throw new \RuntimeException("Codifica OE '{$codOE}' già esistente.");
        }
        $codifica = new CodificaOE(
            new CodificaOEId($codOE),
            new CodificaOEDescrizione($descrizione),
            $ragSoc !== null ? new FornitoreId($ragSoc) : null
        );
        $this->codificaOERepository->save($codifica);
    }

    public function updateOE(string $codOE, string $descrizione, ?string $ragSoc = null): void {
        $codifica = $this->codificaOERepository->findByCod(new CodificaOEId($codOE));
        if ($codifica === null) {
            throw new \RuntimeException("Codifica OE '{$codOE}' non trovata.");
        }
        $codifica->setDescrizione(new CodificaOEDescrizione($descrizione));
        $codifica->setRagSoc($ragSoc !== null ? new FornitoreId($ragSoc) : null);
        $this->codificaOERepository->save($codifica);
    }

    public function deleteOE(string $codOE): void {
        if ($this->codificaOERepository->findByCod(new CodificaOEId($codOE)) === null) {
            throw new \RuntimeException("Codifica OE '{$codOE}' non trovata.");
        }
        $this->codificaOERepository->delete(new CodificaOEId($codOE));
    }
}
