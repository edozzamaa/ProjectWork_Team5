<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Fornitore;
use src\Domain\ValueObjects\Fornitore\FornitoreId;
use src\Domain\ValueObjects\Fornitore\Email;
use src\Domain\ValueObjects\Fornitore\PartitaIVA;
use src\Domain\ValueObjects\Fornitore\Telefono;
use src\Domain\ValueObjects\Fornitore\Indirizzo;
use src\Application\Interfaces\IServices\IFornitoreService;
use src\Application\DTO\FornitoreDTO;
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

    public function getByRagSoc(string $ragSoc): ?FornitoreDTO {
        $fornitore = $this->fornitoreRepository->findByRagSoc(new FornitoreId($ragSoc));
        return $fornitore !== null ? $this->toDTO($fornitore) : null;
    }

    public function createFornitore(string $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?string $email = null): void {
        if ($this->fornitoreRepository->findByRagSoc(new FornitoreId($ragSoc)) !== null) {
            throw new \RuntimeException("Fornitore '{$ragSoc}' già esistente.");
        }
        $fornitore = new Fornitore(
            new FornitoreId($ragSoc),
            $partIVA !== null ? new PartitaIVA($partIVA) : null,
            $telefono !== null ? new Telefono($telefono) : null,
            $indirizzo !== null ? new Indirizzo($indirizzo) : null,
            $email !== null ? new Email($email) : null
        );
        $this->fornitoreRepository->save($fornitore);
    }

    public function updateFornitore(string $ragSoc, array $fields): void {
        $fornitore = $this->fornitoreRepository->findByRagSoc(new FornitoreId($ragSoc));
        if ($fornitore === null) {
            throw new \RuntimeException("Fornitore '{$ragSoc}' non trovato.");
        }
        if (array_key_exists('partIVA', $fields)) {
            $fornitore->setPartIVA($fields['partIVA'] !== null ? new PartitaIVA($fields['partIVA']) : null);
        }
        if (array_key_exists('telefono', $fields)) {
            $fornitore->setTelefono($fields['telefono'] !== null ? new Telefono($fields['telefono']) : null);
        }
        if (array_key_exists('indirizzo', $fields)) {
            $fornitore->setIndirizzo($fields['indirizzo'] !== null ? new Indirizzo($fields['indirizzo']) : null);
        }
        if (array_key_exists('email', $fields)) {
            $fornitore->setEmail($fields['email'] !== null ? new Email($fields['email']) : null);
        }
        $this->fornitoreRepository->update($fornitore, array_keys($fields));
    }

    public function deleteFornitore(string $ragSoc): void {
        if ($this->fornitoreRepository->findByRagSoc(new FornitoreId($ragSoc)) === null) {
            throw new \RuntimeException("Fornitore '{$ragSoc}' non trovato.");
        }
        $this->fornitoreRepository->delete(new FornitoreId($ragSoc));
    }
}
