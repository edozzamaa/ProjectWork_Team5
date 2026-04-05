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
            $armadioCtrl->getAllArmadi();
            break;

        case $normalizedPath === '/api/armadi' && $method === 'POST':
            $armadioCtrl->createArmadio();
            break;

        case preg_match('#^/api/armadi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $armadioCtrl->getArmadio(urldecode($m[1]));
            break;

        case preg_match('#^/api/armadi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $armadioCtrl->updateArmadio(urldecode($m[1]));
            break;

        case preg_match('#^/api/armadi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $armadioCtrl->deleteArmadio(urldecode($m[1]));
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $armadioCtrl->getPosizioniByArmadio(urldecode($m[1]));
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni$#', $normalizedPath, $m) === 1 && $method === 'POST':
            $armadioCtrl->createPosizione(urldecode($m[1]));
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $armadioCtrl->getPosizione(urldecode($m[1]), urldecode($m[2]));
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $armadioCtrl->updatePosizione(urldecode($m[1]), urldecode($m[2]));
            break;

        case preg_match('#^/api/armadi/([^/]+)/posizioni/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $armadioCtrl->deletePosizione(urldecode($m[1]), urldecode($m[2]));
            break;

        case $normalizedPath === '/api/categorie' && $method === 'GET':
            $categoriaCtrl->getAll();
            break;

        case $normalizedPath === '/api/categorie' && $method === 'POST':
            $categoriaCtrl->createCategoria();
            break;

        case preg_match('#^/api/categorie/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $categoriaCtrl->getByCod(urldecode($m[1]));
            break;

        case preg_match('#^/api/categorie/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $categoriaCtrl->updateCategoria(urldecode($m[1]));
            break;

        case preg_match('#^/api/categorie/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $categoriaCtrl->deleteCategoria(urldecode($m[1]));
            break;

        case $normalizedPath === '/api/prodotti' && $method === 'GET':
            $prodottoCtrl->getAll();
            break;

        case $normalizedPath === '/api/prodotti/giacenza' && $method === 'GET':
            $prodottoCtrl->searchWithStock();
            break;

        case $normalizedPath === '/api/prodotti' && $method === 'POST':
            $prodottoCtrl->createProdotto();
            break;

        case $normalizedPath === '/api/prodotti/carico' && $method === 'POST':
            $prodottoCtrl->loadProdotto();
            break;

        case $normalizedPath === '/api/prodotti/scarico' && $method === 'POST':
            $prodottoCtrl->unloadProdotto();
            break;

        case preg_match('#^/api/prodotti/categoria/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $prodottoCtrl->getByCategoria(urldecode($m[1]));
            break;

        case preg_match('#^/api/prodotti/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $prodottoCtrl->getByCod(urldecode($m[1]));
            break;

        case preg_match('#^/api/prodotti/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $prodottoCtrl->updateProdotto(urldecode($m[1]));
            break;

        case preg_match('#^/api/prodotti/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $prodottoCtrl->deleteProdotto(urldecode($m[1]));
            break;

        case preg_match('#^/api/prodotti/([^/]+)/attributi$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $prodottoCtrl->getAttributi(urldecode($m[1]));
            break;

        case preg_match('#^/api/prodotti/([^/]+)/attributi$#', $normalizedPath, $m) === 1 && $method === 'POST':
            $attributoCtrl->assignToProdotto(urldecode($m[1]));
            break;

        case preg_match('#^/api/prodotti/([^/]+)/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $attributoCtrl->removeFromProdotto(urldecode($m[1]), urldecode($m[2]));
            break;

        case $normalizedPath === '/api/fornitori' && $method === 'GET':
            $fornitoreCtrl->getAll();
            break;

        case $normalizedPath === '/api/fornitori' && $method === 'POST':
            $fornitoreCtrl->createFornitore();
            break;

        case preg_match('#^/api/fornitori/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $fornitoreCtrl->getByRagSoc(urldecode($m[1]));
            break;

        case preg_match('#^/api/fornitori/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $fornitoreCtrl->updateFornitore(urldecode($m[1]));
            break;

        case preg_match('#^/api/fornitori/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $fornitoreCtrl->deleteFornitore(urldecode($m[1]));
            break;

        case $normalizedPath === '/api/codifiche/reg' && $method === 'GET':
            $codificaCtrl->getAllReg();
            break;

        case $normalizedPath === '/api/codifiche/reg' && $method === 'POST':
            $codificaCtrl->createReg();
            break;

        case preg_match('#^/api/codifiche/reg/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $codificaCtrl->getRegByCod(urldecode($m[1]));
            break;

        case preg_match('#^/api/codifiche/reg/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $codificaCtrl->updateReg(urldecode($m[1]));
            break;

        case preg_match('#^/api/codifiche/reg/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $codificaCtrl->deleteReg(urldecode($m[1]));
            break;

        case $normalizedPath === '/api/codifiche/oe' && $method === 'GET':
            $codificaCtrl->getAllOE();
            break;

        case $normalizedPath === '/api/codifiche/oe' && $method === 'POST':
            $codificaCtrl->createOE();
            break;

        case preg_match('#^/api/codifiche/oe/fornitore/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $codificaCtrl->getOEByFornitore(urldecode($m[1]));
            break;

        case preg_match('#^/api/codifiche/oe/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $codificaCtrl->getOEByCod(urldecode($m[1]));
            break;

        case preg_match('#^/api/codifiche/oe/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $codificaCtrl->updateOE(urldecode($m[1]));
            break;

        case preg_match('#^/api/codifiche/oe/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $codificaCtrl->deleteOE(urldecode($m[1]));
            break;

        case $normalizedPath === '/api/attributi' && $method === 'GET':
            $attributoCtrl->getAll();
            break;

        case $normalizedPath === '/api/attributi' && $method === 'POST':
            $attributoCtrl->createAttributo();
            break;

        case preg_match('#^/api/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'GET':
            $attributoCtrl->getByCod(urldecode($m[1]));
            break;

        case preg_match('#^/api/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'PUT':
            $attributoCtrl->updateAttributo(urldecode($m[1]));
            break;

        case preg_match('#^/api/attributi/([^/]+)$#', $normalizedPath, $m) === 1 && $method === 'DELETE':
            $attributoCtrl->deleteAttributo(urldecode($m[1]));
            break;

        case $normalizedPath === '/api/report/sotto-soglia' && $method === 'GET':
            $reportCtrl->getProdottiSottoSoglia();
            break;

        case $normalizedPath === '/api/report/panoramica' && $method === 'GET':
            $reportCtrl->getPanoramica();
            break;

        case $normalizedPath === '/api/report/completo' && $method === 'GET':
            $reportCtrl->reportCompleto();
            break;

        case $normalizedPath === '/api/report/giacenze' && $method === 'GET':
            $reportCtrl->reportGiacenze();
            break;

        default:
            denyAccess();
    }
} catch (Throwable $exception) {
    $response->error('Si è verificato un problema. Riprova tra poco.', 500);
}
