<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class UpdateFornitoreInput {
    /**
     * @param array<string, mixed> $fields
     */
    public function __construct(
        public string $ragSoc,
        public array $fields
    ) {}
}
