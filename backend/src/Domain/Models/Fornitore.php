<?php declare(strict_types=1);
namespace src\Domain\Models;

use src\Domain\ValueObjects\Fornitore\FornitoreId;
use src\Domain\ValueObjects\Fornitore\PartitaIVA;
use src\Domain\ValueObjects\Fornitore\Telefono;
use src\Domain\ValueObjects\Fornitore\Indirizzo;
use src\Domain\ValueObjects\Fornitore\Email;
/**
 * Class Fornitore
 *
 * @package src\Domain\Models
 */
class Fornitore {
    private FornitoreId $ragSoc;
    private ?PartitaIVA $partIVA;
    private ?Telefono $telefono;
    private ?Indirizzo $indirizzo;
    private ?Email $email;

    public function __construct(FornitoreId $ragSoc, ?PartitaIVA $partIVA = null, ?Telefono $telefono = null, ?Indirizzo $indirizzo = null, ?Email $email = null) {
        $this->ragSoc = $ragSoc;
        $this->partIVA = $partIVA;
        $this->telefono = $telefono;
        $this->indirizzo = $indirizzo;
        $this->email = $email;
    }

    public static function reconstituteFromDatabase(FornitoreId $ragSoc, ?PartitaIVA $partIVA, ?Telefono $telefono, ?Indirizzo $indirizzo, ?Email $email): self {
        return new self($ragSoc, $partIVA, $telefono, $indirizzo, $email);
    }

    public function getRagSoc(): FornitoreId {
        return $this->ragSoc;
    }

    public function setRagSoc(FornitoreId $ragSoc): void {
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

    public function getIndirizzo(): ?Indirizzo {
        return $this->indirizzo;
    }

    public function setIndirizzo(?Indirizzo $indirizzo): void {
        $this->indirizzo = $indirizzo;
    }

    public function getEmail(): ?Email {
        return $this->email;
    }

    public function setEmail(?Email $email): void {
        $this->email = $email;
    }
}
