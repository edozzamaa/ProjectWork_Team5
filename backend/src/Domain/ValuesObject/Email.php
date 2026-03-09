<?php declare(strict_types=1);
    namespace src\Domain\ValuesObjects;

    class Email {
        private string $email;

        public function __construct(string $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new DomainException("Invalid email: {$email}");
            }
            $this->email = $email;
        }

        public function getEmail(): string {
            return $this->email;
        }
    }
?>