<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use src\Application\Interfaces\IServices\IReportService;
use src\Presentation\Response\IResponse;

class ReportController {

    public function __construct(
        private IReportService $service,
        private IResponse $response
    ) {}

    public function getProdottiSottoSoglia(): void {
    }

    public function getPanoramica(): void {
    }

    public function reportCompleto(): void {
    }

    public function reportGiacenze(): void {
    }
}
