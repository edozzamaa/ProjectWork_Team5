<?php declare(strict_types=1);
    namespace src\Domain\ValuesObjects;

    class Telefono {
        private string $telefono;

        public function __construct(string $telefono) {
            $cleaned = preg_replace('/[\s\-\.\/]/', '', $telefono);
            if (!preg_match('/^\+?\d{6,15}$/', $cleaned)) {
                throw new \DomainException("Numero di telefono non valido: '{$telefono}'. Deve contenere tra 6 e 15 cifre.");
            }
            $this->telefono = $cleaned;
        }

        public function getTelefono(): string {
            return $this->telefono;
        }

        public function equals(Telefono $other): bool {
            return $this->telefono === $other->telefono;
        }

        public function __toString(): string {
            return $this->telefono;
        }
    }
?>
