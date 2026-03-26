<?php declare(strict_types=1);
    namespace src\Domain\ValuesObject;

    class Quantita {
        private int $valore;

        public function __construct(int $valore) {
            if ($valore < 0) {
                throw new \DomainException("Quantità non valida: non può essere negativa (valore: {$valore}).");
            }
            $this->valore = $valore;
        }

        public function getValore(): int {
            return $this->valore;
        }

        public function aggiungi(int $qta): self {
            if ($qta <= 0) {
                throw new \DomainException("La quantità da aggiungere deve essere maggiore di zero.");
            }
            return new self($this->valore + $qta);
        }

        public function sottrai(int $qta): self {
            if ($qta <= 0) {
                throw new \DomainException("La quantità da sottrarre deve essere maggiore di zero.");
            }
            if ($qta > $this->valore) {
                throw new \DomainException("Quantità insufficiente: disponibile {$this->valore}, richiesta {$qta}.");
            }
            return new self($this->valore - $qta);
        }

        public function isZero(): bool {
            return $this->valore === 0;
        }

        public function equals(Quantita $other): bool {
            return $this->valore === $other->valore;
        }

        public function __toString(): string {
            return (string) $this->valore;
        }
    }
?>
