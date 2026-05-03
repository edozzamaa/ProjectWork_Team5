<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Fornitore;
use src\Domain\ValueObjects\Fornitore\FornitoreId;
use src\Domain\ValueObjects\Fornitore\Email;
use src\Domain\ValueObjects\Fornitore\PartitaIVA;
use src\Domain\ValueObjects\Fornitore\Telefono;
use src\Domain\ValueObjects\Fornitore\Indirizzo;
use src\Application\Interfaces\IServices\IFornitoreService;
use src\Application\DTO\Output\FornitoreDTO;
use src\Application\DTO\Input\GetFornitoreByRagSocInput;
use src\Application\DTO\Input\CreateFornitoreInput;
use src\Application\DTO\Input\UpdateFornitoreInput;
use src\Application\DTO\Input\DeleteFornitoreInput;
use src\Application\Interfaces\IRepositories\IFornitoreRepository;

class FornitoreService implements IFornitoreService {

    private IFornitoreRepository $fornitoreRepository;

    public function __construct(IFornitoreRepository $fornitoreRepository) {
        $this->fornitoreRepository = $fornitoreRepository;
    }

    private function toDTO(Fornitore $fornitore): FornitoreDTO {
        return new FornitoreDTO(
            (string) $fornitore->getRagSoc(),
            $fornitore->getPartIVA() !== null ? (string) $fornitore->getPartIVA() : null,
            $fornitore->getTelefono() !== null ? (string) $fornitore->getTelefono() : null,
            $fornitore->getIndirizzo()?->value,
            $fornitore->getEmail() !== null ? $fornitore->getEmail()->value : null
        );
    }

    /** @return FornitoreDTO[] */
    public function getAll(): array {
        return array_map(fn(Fornitore $f) => $this->toDTO($f), $this->fornitoreRepository->findAll());
    }

    public function getByRagSoc(GetFornitoreByRagSocInput $input): ?FornitoreDTO {
        $fornitore = $this->fornitoreRepository->findByRagSoc(new FornitoreId($input->ragSoc));
        return $fornitore !== null ? $this->toDTO($fornitore) : null;
    }

    public function createFornitore(CreateFornitoreInput $input): void {
        if ($this->fornitoreRepository->findByRagSoc(new FornitoreId($input->ragSoc)) !== null) {
            throw new \RuntimeException("Fornitore '{$input->ragSoc}' già esistente.");
        }
        $fornitore = new Fornitore(
            new FornitoreId($input->ragSoc),
            $input->partIVA !== null ? new PartitaIVA($input->partIVA) : null,
            $input->telefono !== null ? new Telefono($input->telefono) : null,
            $input->indirizzo !== null ? new Indirizzo($input->indirizzo) : null,
            $input->email !== null ? new Email($input->email) : null
        );
        $this->fornitoreRepository->save($fornitore);
    }

    public function updateFornitore(UpdateFornitoreInput $input): void {
        $fornitore = $this->fornitoreRepository->findByRagSoc(new FornitoreId($input->ragSoc));
        if ($fornitore === null) {
            throw new \RuntimeException("Fornitore '{$input->ragSoc}' non trovato.");
        }
        if (array_key_exists('partIVA', $input->fields)) {
            $fornitore->setPartIVA($input->fields['partIVA'] !== null ? new PartitaIVA($input->fields['partIVA']) : null);
        }
        if (array_key_exists('telefono', $input->fields)) {
            $fornitore->setTelefono($input->fields['telefono'] !== null ? new Telefono($input->fields['telefono']) : null);
        }
        if (array_key_exists('indirizzo', $input->fields)) {
            $fornitore->setIndirizzo($input->fields['indirizzo'] !== null ? new Indirizzo($input->fields['indirizzo']) : null);
        }
        if (array_key_exists('email', $input->fields)) {
            $fornitore->setEmail($input->fields['email'] !== null ? new Email($input->fields['email']) : null);
        }
        $this->fornitoreRepository->update($fornitore, array_keys($input->fields));
    }

    public function deleteFornitore(DeleteFornitoreInput $input): void {
        if ($this->fornitoreRepository->findByRagSoc(new FornitoreId($input->ragSoc)) === null) {
            throw new \RuntimeException("Fornitore '{$input->ragSoc}' non trovato.");
        }
        $this->fornitoreRepository->delete(new FornitoreId($input->ragSoc));
    }
}
