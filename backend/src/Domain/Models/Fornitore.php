<?php declare(strict_types=1);
namespace src\Domain\Models;

use src\Domain\ValuesObjects\Email;
use src\Domain\ValuesObjects\ID;
use src\Domain\ValuesObjects\PartitaIVA;
use src\Domain\ValuesObjects\Telefono;
/**
 * Class Fornitore
 *
 * @package src\Domain\Models
 * @property ID $ragSoc
 * @property ?PartitaIVA $partIVA
 * @property ?Telefono $telefono
 * @property ?string $indirizzo
 * @property ?Email $email
 */
class Fornitore {
    private ID $ragSoc;
    private ?PartitaIVA $partIVA;
    private ?Telefono $telefono;
    private ?string $indirizzo;
    private ?Email $email;

    public function __construct(ID $ragSoc, ?PartitaIVA $partIVA = null, ?Telefono $telefono = null, ?string $indirizzo = null, ?Email $email = null) {
        $this->ragSoc = $ragSoc;
        $this->partIVA = $partIVA;
        $this->telefono = $telefono;
        $this->indirizzo = $indirizzo;
        $this->email = $email;
    }

    public static function reconstituteFromDatabase(ID $ragSoc, ?PartitaIVA $partIVA, ?Telefono $telefono, ?string $indirizzo, ?Email $email): self {
        return new self($ragSoc, $partIVA, $telefono, $indirizzo, $email);
    }

    public function getRagSoc(): ID {
        return $this->ragSoc;
    }

    public function setRagSoc(ID $ragSoc): void {
        $this->ragSoc = $ragSoc;
    }

    public function getPartIVA(): ?PartitaIVA {
        return $this->partIVA;
    }

    public function setPartIVA(?PartitaIVA $partIVA): void {
        $this->partIVA = $partIVA;
    }

    public function getTelefono(): ?Telefono {
        return $this->telefono;
    }

    public function setTelefono(?Telefono $telefono): void {
        $this->telefono = $telefono;
    }

    public function getIndirizzo(): ?string {
        return $this->indirizzo;
    }

    public function setIndirizzo(?string $indirizzo): void {
        $this->indirizzo = $indirizzo;
    }

    public function getEmail(): ?Email {
        return $this->email;
    }

    public function setEmail(?Email $email): void {
        $this->email = $email;
    }
}
