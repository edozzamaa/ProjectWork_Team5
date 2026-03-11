<?php declare(strict_types=1);
    namespace src\Domain\ValuesObjects;

    class PartitaIVA {
        private string $partitaIVA;

        public function __construct(string $partitaIVA) {
            $cleaned = preg_replace('/\s+/', '', $partitaIVA);
            if (!preg_match('/^\d{11}$/', $cleaned)) {
                throw new \DomainException("Partita IVA non valida: deve essere composta da 11 cifre numeriche.");
            }
            $this->partitaIVA = $cleaned;
        }

        public function getPartitaIVA(): string {
            return $this->partitaIVA;
        }

        public function equals(PartitaIVA $other): bool {
            return $this->partitaIVA === $other->partitaIVA;
        }

        public function __toString(): string {
            return $this->partitaIVA;
        }
    }
?>
