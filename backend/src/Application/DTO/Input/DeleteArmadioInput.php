<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class DeleteArmadioInput {
    public function __construct(
        public string $codArmadio
    ) {}
}
