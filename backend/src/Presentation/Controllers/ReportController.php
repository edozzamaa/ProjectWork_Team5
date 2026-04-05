<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use Exception;
use src\Application\Interfaces\IServices\IReportService;
use src\Presentation\Response\IResponse;

class ReportController {

    public function __construct(
        private IReportService $service,
        private IResponse $response
    ) {}

    public function getProdottiSottoSoglia(): void {
        try {
            $prodotti = $this->service->getProdottiSottoSoglia();
            $this->response->success($prodotti);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function getPanoramica(): void {
        try {
            $panoramica = $this->service->getPanoramica();
            $this->response->success($panoramica);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function reportCompleto(): void {
        try {
            $report = $this->service->reportCompleto();
            $this->response->success($report);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }

    public function reportGiacenze(): void {
        try {
            $giacenze = $this->service->reportGiacenze();
            $this->response->success($giacenze);
        } catch (Exception $e) {
            $this->response->error('Operazione non disponibile al momento. Riprova.', 500);
        }
    }
}
