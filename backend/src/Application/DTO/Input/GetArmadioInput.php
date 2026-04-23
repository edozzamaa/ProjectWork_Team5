<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class GetArmadioInput {
    public function __construct(
        public string $codArmadio
    ) {}
}
