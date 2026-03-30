<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use src\Application\Services\ArmadioService;
use src\Application\Services\CategoriaService;
use src\Application\Services\ProdottoService;
use src\Application\Services\FornitoreService;
use src\Application\Services\CodificaService;
use src\Application\Services\AttributoService;
use src\Application\Services\ReportService;
use src\Infrastructure\Repositories\ArmadioRepository;
use src\Infrastructure\Repositories\CategoriaRepository;
use src\Infrastructure\Repositories\ProdottoRepository;
use src\Infrastructure\Repositories\FornitoreRepository;
use src\Infrastructure\Repositories\CodificaRegRepository;
use src\Infrastructure\Repositories\CodificaOERepository;
use src\Infrastructure\Repositories\AttributoRepository;
use src\Infrastructure\Repositories\GiacenzaRepository;
use src\Presentation\Controllers\ArmadioController;
use src\Presentation\Controllers\CategoriaController;
use src\Presentation\Controllers\ProdottoController;
use src\Presentation\Controllers\FornitoreController;
use src\Presentation\Controllers\CodificaController;
use src\Presentation\Controllers\AttributoController;
use src\Presentation\Controllers\ReportController;
use src\Presentation\Response\JsonResponse;

header('Content-Type: application/json');

$response = new JsonResponse();

function denyAccess(): never {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Risorsa non trovata']);
    exit;
}

function jsonInput(): array {
    $payload = json_decode(file_get_contents('php://input'), true);
    return is_array($payload) ? $payload : [];
}

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$normalizedPath = rtrim($requestPath, '/');
if ($normalizedPath === '') {
    $normalizedPath = '/';
}

if (str_starts_with($normalizedPath, '/index.php')) {
    $normalizedPath = substr($normalizedPath, strlen('/index.php')) ?: '/';
}

$isApiRoute = str_starts_with($normalizedPath, '/api/');
if (!$isApiRoute) {
    denyAccess();
}

$armadioRepo     = new ArmadioRepository();
$categoriaRepo   = new CategoriaRepository();
$prodottoRepo    = new ProdottoRepository();
$fornitoreRepo   = new FornitoreRepository();
$codificaRegRepo = new CodificaRegRepository();
$codificaOERepo  = new CodificaOERepository();
$attributoRepo   = new AttributoRepository();
$giacenzaRepo    = new GiacenzaRepository();

$armadioService   = new ArmadioService($armadioRepo);
$categoriaService = new CategoriaService($categoriaRepo);
$prodottoService  = new ProdottoService($prodottoRepo, $armadioRepo, $giacenzaRepo);
$fornitoreService = new FornitoreService($fornitoreRepo);
$codificaService  = new CodificaService($codificaRegRepo, $codificaOERepo);
$attributoService = new AttributoService($attributoRepo, $prodottoRepo);
$reportService    = new ReportService($giacenzaRepo, $prodottoRepo, $categoriaRepo);

$armadioCtrl   = new ArmadioController($armadioService, $response);
$categoriaCtrl = new CategoriaController($categoriaService, $response);
$prodottoCtrl  = new ProdottoController($prodottoService, $response);
$fornitoreCtrl = new FornitoreController($fornitoreService, $response);
$codificaCtrl  = new CodificaController($codificaService, $response);
$attributoCtrl = new AttributoController($attributoService, $response);
$reportCtrl    = new ReportController($reportService, $response);

$segments = explode('/', trim($normalizedPath, '/'));

try {
    switch (true) {

        case $normalizedPath === '/api/armadi' && $method === 'GET':
            break;

        case $normalizedPath === '/api/armadi' && $method === 'POST':
            break;

        case preg_match('#^/api/armadi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/armadi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/armadi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni$#', $normalizedPath, $m) === 1 && $method === 'POST':
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case $normalizedPath === '/api/categorie' && $method === 'GET':
            break;

        case $normalizedPath === '/api/categorie' && $method === 'POST':
            break;

        case preg_match('#^/api/categorie/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/categorie/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/categorie/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case $normalizedPath === '/api/prodotti' && $method === 'GET':
            break;

        case $normalizedPath === '/api/prodotti/giacenza' && $method === 'GET':
            break;

        case $normalizedPath === '/api/prodotti' && $method === 'POST':
            break;

        case $normalizedPath === '/api/prodotti/carico' && $method === 'POST':
            break;

        case $normalizedPath === '/api/prodotti/scarico' && $method === 'POST':
            break;

        case preg_match('#^/api/prodotti/categoria/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/prodotti/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/prodotti/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/prodotti/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case preg_match('#^/api/prodotti/([^/]+)/attributi$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/prodotti/([^/]+)/attributi$#', $normalizedPath, $m) === 1 && $method === 'POST':
            break;

        case preg_match('#^/api/prodotti/([^/]+)/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case $normalizedPath === '/api/fornitori' && $method === 'GET':
            break;

        case $normalizedPath === '/api/fornitori' && $method === 'POST':
            break;

        case preg_match('#^/api/fornitori/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/fornitori/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/fornitori/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case $normalizedPath === '/api/codifiche/reg' && $method === 'GET':
            break;

        case $normalizedPath === '/api/codifiche/reg' && $method === 'POST':
            break;

        case preg_match('#^/api/codifiche/reg/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/codifiche/reg/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/codifiche/reg/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case $normalizedPath === '/api/codifiche/oe' && $method === 'GET':
            break;

        case $normalizedPath === '/api/codifiche/oe' && $method === 'POST':
            break;

        case preg_match('#^/api/codifiche/oe/fornitore/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/codifiche/oe/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/codifiche/oe/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/codifiche/oe/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case $normalizedPath === '/api/attributi' && $method === 'GET':
            break;

        case $normalizedPath === '/api/attributi' && $method === 'POST':
            break;

        case preg_match('#^/api/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            break;

        case preg_match('#^/api/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            break;

        case preg_match('#^/api/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            break;

        case $normalizedPath === '/api/report/sotto-soglia' && $method === 'GET':
            break;

        case $normalizedPath === '/api/report/panoramica' && $method === 'GET':
            break;

        case $normalizedPath === '/api/report/completo' && $method === 'GET':
            break;

        case $normalizedPath === '/api/report/giacenze' && $method === 'GET':
            break;

        default:
            denyAccess();
    }
} catch (Throwable $exception) {
    $response->error('Si è verificato un problema temporaneo. Riprova tra poco.', 500);
}
