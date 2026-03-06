<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class Fornitore
 *
 * @package src\Domain\Models
 * @property string $ragSoc
 * @property ?string $partIVA
 * @property ?string $telefono
 * @property ?string $indirizzo
 * @property ?string $email
 */
class Fornitore {
    private string $ragSoc;
    private ?string $partIVA;
    private ?string $telefono;
    private ?string $indirizzo;
    private ?string $email;

    public function __construct(string $ragSoc, ?string $partIVA = null, ?string $telefono = null, ?string $indirizzo = null, ?string $email = null) {
        $this->ragSoc = $ragSoc;
        $this->partIVA = $partIVA;
        $this->telefono = $telefono;
        $this->indirizzo = $indirizzo;
        $this->email = $email;
    }

    public static function reconstituteFromDatabase(string $ragSoc, ?string $partIVA, ?string $telefono, ?string $indirizzo, ?string $email): self {
        return new self($ragSoc, $partIVA, $telefono, $indirizzo, $email);
    }

    public function getRagSoc(): string {
        return $this->ragSoc;
    }

    public function setRagSoc(string $ragSoc): void {
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

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }
}
