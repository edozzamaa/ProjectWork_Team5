<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Fornitore;
use src\Domain\ValuesObject\ID;
use src\Domain\ValuesObjects\Email;
use src\Application\Interfaces\IFornitoreService;
use src\Application\DTO\FornitoreDTO;
use src\Infrastructure\Repositories\FornitoreRepository;

class FornitoreService implements IFornitoreService {

    private FornitoreRepository $fornitoreRepository;

    public function __construct(FornitoreRepository $fornitoreRepository) {
        $this->fornitoreRepository = $fornitoreRepository;
    }

    private function toDTO(Fornitore $fornitore): FornitoreDTO {
        return new FornitoreDTO(
            (string) $fornitore->getRagSoc(),
            $fornitore->getPartIVA(),
            $fornitore->getTelefono(),
            $fornitore->getIndirizzo(),
            $fornitore->getEmail() !== null ? (string) $fornitore->getEmail()->getEmail() : null
        );
    }

    /** @return FornitoreDTO[] */
    public function getAll(): array {
        return array_map(fn(Fornitore $f) => $this->toDTO($f), $this->fornitoreRepository->findAll());
    }

    public function getByRagSoc(string $ragSoc): ?FornitoreDTO {
        $fornitore = $this->fornitoreRepository->findByRagSoc(new ID($ragSoc));
        return $fornitore !== null ? $this->toDTO($fornitore) : null;
    }

    public function crea(string $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?string $email = null): void {
        if ($this->fornitoreRepository->findByRagSoc(new ID($ragSoc)) !== null) {
            throw new \RuntimeException("Fornitore '{$ragSoc}' già esistente.");
        }
        $fornitore = new Fornitore(
            new ID($ragSoc),
            $partIVA,
            $telefono,
            $indirizzo,
            $email !== null ? new Email($email) : null
        );
        $this->fornitoreRepository->save($fornitore);
    }

    public function aggiorna(string $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?string $email = null): void {
        $fornitore = $this->fornitoreRepository->findByRagSoc(new ID($ragSoc));
        if ($fornitore === null) {
            throw new \RuntimeException("Fornitore '{$ragSoc}' non trovato.");
        }
        $fornitore->setPartIVA($partIVA);
        $fornitore->setTelefono($telefono);
        $fornitore->setIndirizzo($indirizzo);
        $fornitore->setEmail($email !== null ? new Email($email) : null);
        $this->fornitoreRepository->save($fornitore);
    }

    public function elimina(string $ragSoc): void {
        if ($this->fornitoreRepository->findByRagSoc(new ID($ragSoc)) === null) {
            throw new \RuntimeException("Fornitore '{$ragSoc}' non trovato.");
        }
        $this->fornitoreRepository->delete(new ID($ragSoc));
    }
}
