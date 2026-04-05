<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\FornitoreDTO;

interface IFornitoreService {

    /** @return FornitoreDTO[] */
    public function getAll(): array;

    public function getByRagSoc(string $ragSoc): ?FornitoreDTO;

    public function createFornitore(string $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?string $email = null): void;

    /** @param array<string, mixed> $fields */
    public function updateFornitore(string $ragSoc, array $fields): void;

    public function deleteFornitore(string $ragSoc): void;
}
