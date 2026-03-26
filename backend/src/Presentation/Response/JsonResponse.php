<?php declare(strict_types=1);

namespace src\Presentation\Response;

class JsonResponse implements IResponse {
    public function success(mixed $data, int $code = 200): never {
        $message = 'Operazione completata con successo.';
        $normalizedData = $data;

        if (is_array($data)) {
            if (isset($data['message']) && is_string($data['message'])) {
                $message = $data['message'];
            }

            if (array_key_exists('data', $data)) {
                $normalizedData = $data['data'];
            } else {
                $normalizedData = $data;
                if (array_key_exists('success', $normalizedData)) {
                    unset($normalizedData['success']);
                }
                if (array_key_exists('message', $normalizedData)) {
                    unset($normalizedData['message']);
                }
            }

            if ($normalizedData === []) {
                $normalizedData = null;
            }
        }

        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $normalizedData
        ]);
        exit;
    }

    public function error(string $message, int $code = 500): never {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit;
    }
}
