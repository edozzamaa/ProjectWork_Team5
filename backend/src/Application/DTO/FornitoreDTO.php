<?php declare(strict_types=1);
namespace src\Application\DTO;

class FornitoreDTO {

    public readonly string $ragSoc;
    public readonly ?string $partIVA;
    public readonly ?string $telefono;
    public readonly ?string $indirizzo;
    public readonly ?string $email;

    public function __construct(
        string $ragSoc,
        ?string $partIVA = null,
        ?string $telefono = null,
        ?string $indirizzo = null,
        ?string $email = null
    ) {
        $this->ragSoc = $ragSoc;
        $this->partIVA = $partIVA;
        $this->telefono = $telefono;
        $this->indirizzo = $indirizzo;
        $this->email = $email;
    }
}
