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
use src\Application\DTO\Input\GetCodificaRegByCodInput;
use src\Application\DTO\Input\CreateCodificaRegInput;
use src\Application\DTO\Input\UpdateCodificaRegInput;
use src\Application\DTO\Input\DeleteCodificaRegInput;
use src\Application\DTO\Input\GetCodificaOEByCodInput;
use src\Application\DTO\Input\GetCodificaOEByFornitoreInput;
use src\Application\DTO\Input\CreateCodificaOEInput;
use src\Application\DTO\Input\UpdateCodificaOEInput;
use src\Application\DTO\Input\DeleteCodificaOEInput;
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

    public function getRegByCod(GetCodificaRegByCodInput $input): ?CodificaRegDTO {
        $codifica = $this->codificaRegRepository->findByCod(new CodificaRegId($input->codReg));
        return $codifica !== null ? $this->regToDTO($codifica) : null;
    }

    public function createReg(CreateCodificaRegInput $input): void {
        if ($this->codificaRegRepository->findByCod(new CodificaRegId($input->codReg)) !== null) {
            throw new \RuntimeException("Codifica regionale '{$input->codReg}' già esistente.");
        }
        $codifica = new CodificaReg(new CodificaRegId($input->codReg), new CodificaRegDescrizione($input->descrizione));
        $this->codificaRegRepository->save($codifica);
    }

    public function updateReg(UpdateCodificaRegInput $input): void {
        $codifica = $this->codificaRegRepository->findByCod(new CodificaRegId($input->codReg));
        if ($codifica === null) {
            throw new \RuntimeException("Codifica regionale '{$input->codReg}' non trovata.");
        }
        $codifica->setDescrizione(new CodificaRegDescrizione($input->descrizione));
        $this->codificaRegRepository->save($codifica);
    }

    public function deleteReg(DeleteCodificaRegInput $input): void {
        if ($this->codificaRegRepository->findByCod(new CodificaRegId($input->codReg)) === null) {
            throw new \RuntimeException("Codifica regionale '{$input->codReg}' non trovata.");
        }
        $this->codificaRegRepository->delete(new CodificaRegId($input->codReg));
    }

    // ── Codifica OE ──

    /** @return CodificaOEDTO[] */
    public function getAllOE(): array {
        return array_map(fn(CodificaOE $c) => $this->oeToDTO($c), $this->codificaOERepository->findAll());
    }

    public function getOEByCod(GetCodificaOEByCodInput $input): ?CodificaOEDTO {
        $codifica = $this->codificaOERepository->findByCod(new CodificaOEId($input->codOE));
        return $codifica !== null ? $this->oeToDTO($codifica) : null;
    }

    /** @return CodificaOEDTO[] */
    public function getOEByFornitore(GetCodificaOEByFornitoreInput $input): array {
        return array_map(fn(CodificaOE $c) => $this->oeToDTO($c), $this->codificaOERepository->findByFornitore(new FornitoreId($input->ragSoc)));
    }

    public function createOE(CreateCodificaOEInput $input): void {
        if ($this->codificaOERepository->findByCod(new CodificaOEId($input->codOE)) !== null) {
            throw new \RuntimeException("Codifica OE '{$input->codOE}' già esistente.");
        }
        $codifica = new CodificaOE(
            new CodificaOEId($input->codOE),
            new CodificaOEDescrizione($input->descrizione),
            $input->ragSoc !== null ? new FornitoreId($input->ragSoc) : null
        );
        $this->codificaOERepository->save($codifica);
    }

    public function updateOE(UpdateCodificaOEInput $input): void {
        $codifica = $this->codificaOERepository->findByCod(new CodificaOEId($input->codOE));
        if ($codifica === null) {
            throw new \RuntimeException("Codifica OE '{$input->codOE}' non trovata.");
        }
        $codifica->setDescrizione(new CodificaOEDescrizione($input->descrizione));
        $codifica->setRagSoc($input->ragSoc !== null ? new FornitoreId($input->ragSoc) : null);
        $this->codificaOERepository->save($codifica);
    }

    public function deleteOE(DeleteCodificaOEInput $input): void {
        if ($this->codificaOERepository->findByCod(new CodificaOEId($input->codOE)) === null) {
            throw new \RuntimeException("Codifica OE '{$input->codOE}' non trovata.");
        }
        $this->codificaOERepository->delete(new CodificaOEId($input->codOE));
    }
}
