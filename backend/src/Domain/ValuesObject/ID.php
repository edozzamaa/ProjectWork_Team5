<?php declare(strict_types=1);
    namespace src\Domain\ValuesObject;

    class ID {
        private string $id;

        public function __construct(string $id) {
            if (empty(trim($id))) {
                throw new \DomainException("Invalid ID: cannot be empty");
            }
            $this->id = $id;
        }

        public function getId(): string {
            return $this->id;
        }

        public function equals(ID $other): bool {
            return $this->id === $other->id;
        }

        public function __toString(): string {
            return $this->id;
        }
    }
?>