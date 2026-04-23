<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\FornitoreDTO;
use src\Application\DTO\Input\GetFornitoreByRagSocInput;
use src\Application\DTO\Input\CreateFornitoreInput;
use src\Application\DTO\Input\UpdateFornitoreInput;
use src\Application\DTO\Input\DeleteFornitoreInput;

interface IFornitoreService {

    /** @return FornitoreDTO[] */
    public function getAll(): array;

    public function getByRagSoc(GetFornitoreByRagSocInput $input): ?FornitoreDTO;

    public function createFornitore(CreateFornitoreInput $input): void;

    public function updateFornitore(UpdateFornitoreInput $input): void;

    public function deleteFornitore(DeleteFornitoreInput $input): void;
}
