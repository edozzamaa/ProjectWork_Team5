<?php declare(strict_types=1);
namespace src\Application\Interfaces;

use src\Application\DTO\FornitoreDTO;

interface IFornitoreService {

    /** @return FornitoreDTO[] */
    public function getAll(): array;

    public function getByRagSoc(string $ragSoc): ?FornitoreDTO;

    public function crea(string $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?string $email = null): void;

    public function aggiorna(string $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?string $email = null): void;

    public function elimina(string $ragSoc): void;
}
