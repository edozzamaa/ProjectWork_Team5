<?php declare(strict_types=1);

namespace src\Presentation\Response;

interface IResponse {
    public function success(mixed $data, int $code = 200): never;

    public function error(string $message, int $code = 500): never;
}
