<?php declare(strict_types=1);
namespace src\Application\Interfaces;

use src\Application\DTO\PanoramicaDTO;
use src\Application\DTO\ReportProdottoDTO;
use src\Application\DTO\PosProdDTO;

interface IReportService {

    /**
     * @return array<int, array{codProd: string, qtaRiordino: int, qtaTotale: int}>
     */
    public function getProdottiSottoSoglia(): array;

    public function getPanoramica(): PanoramicaDTO;

    /** @return ReportProdottoDTO[] */
    public function reportCompleto(): array;

    /** @return PosProdDTO[] */
    public function reportGiacenze(): array;
}
