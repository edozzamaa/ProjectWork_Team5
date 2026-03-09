<?php declare(strict_types=1);
namespace src\Domain\Models;

use src\Domain\ValuesObjects\Email;
use src\Domain\ValuesObjects\ID;
/**
 * Class Fornitore
 *
 * @package src\Domain\Models
 * @property ID $ragSoc
 * @property ?string $partIVA
 * @property ?string $telefono
 * @property ?string $indirizzo
 * @property ?Email $email
 */
class Fornitore {
    private ID $ragSoc;
    private ?string $partIVA;
    private ?string $telefono;
    private ?string $indirizzo;
    private ?Email $email;

    public function __construct(ID $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?Email $email = null) {
        $this->ragSoc = $ragSoc;
        $this->partIVA = $partIVA;
        $this->telefono = $telefono;
        $this->indirizzo = $indirizzo;
        $this->email = $email;
    }

    public static function reconstituteFromDatabase(ID $ragSoc, ?string $partIVA, ?string $telefono, ?string $indirizzo, ?Email $email): self {
        return new self($ragSoc, $partIVA, $telefono, $indirizzo, $email);
    }

    public function getRagSoc(): ID {
        return $this->ragSoc;
    }

    public function setRagSoc(ID $ragSoc): void {
        $this->ragSoc = $ragSoc;
    }

    public function getPartIVA(): ?string {
        return $this->partIVA;
    }

    public function setPartIVA(?string $partIVA): void {
        $this->partIVA = $partIVA;
    }

    public function getTelefono(): ?string {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): void {
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
