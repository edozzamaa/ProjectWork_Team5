<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Prodotto;
use src\Domain\Models\Categoria;
use src\Domain\Models\PosProd;
use src\Application\Interfaces\IReportService;
use src\Application\DTO\ProdottoDTO;
use src\Application\DTO\CategoriaDTO;
use src\Application\DTO\PanoramicaDTO;
use src\Application\DTO\ReportProdottoDTO;
use src\Application\DTO\PosProdDTO;
use src\Infrastructure\Repositories\GiacenzaRepository;
use src\Infrastructure\Repositories\ProdottoRepository;
use src\Infrastructure\Repositories\CategoriaRepository;

class ReportService implements IReportService {

    private GiacenzaRepository $giacenzaRepository;
    private ProdottoRepository $prodottoRepository;
    private CategoriaRepository $categoriaRepository;

    public function __construct(
        GiacenzaRepository $giacenzaRepository,
        ProdottoRepository $prodottoRepository,
        CategoriaRepository $categoriaRepository
    ) {
        $this->giacenzaRepository = $giacenzaRepository;
        $this->prodottoRepository = $prodottoRepository;
        $this->categoriaRepository = $categoriaRepository;
    }

    // ── Visualizza Prodotti Sotto Soglia ──

    /**
     * @return array<int, array{codProd: string, qtaRiordino: int, qtaTotale: int}>
     */
    public function getProdottiSottoSoglia(): array {
        return $this->giacenzaRepository->prodottiSottoSoglia();
    }

    // ── Visualizza Panoramica ──

    public function getPanoramica(): PanoramicaDTO {
        $prodotti = $this->prodottoRepository->findAll();
        $categorie = $this->categoriaRepository->findAll();

        $prodottiDTO = [];
        $contatoreSottoSoglia = 0;

        foreach ($prodotti as $prodotto) {
            $codProd = (string) $prodotto->getCodProd();
            $giacenzaTotale = $this->giacenzaRepository->giacenzaTotale($prodotto->getCodProd());
            $sottoSoglia = $prodotto->necessitaRiordino($giacenzaTotale);

            if ($sottoSoglia) {
                $contatoreSottoSoglia++;
            }

            $prodottiDTO[] = new ProdottoDTO(
                $codProd,
                $prodotto->getQtaRiordino(),
                $prodotto->getCodCat() !== null ? (string) $prodotto->getCodCat() : null,
                $prodotto->getCodReg() !== null ? (string) $prodotto->getCodReg() : null,
                $prodotto->getCodOE() !== null ? (string) $prodotto->getCodOE() : null,
                $giacenzaTotale
            );
        }

        $categorieDTO = array_map(
            fn(Categoria $c) => new CategoriaDTO((string) $c->getCodCat(), $c->getTipo()),
            $categorie
        );

        return new PanoramicaDTO($prodottiDTO, $categorieDTO, count($prodotti), $contatoreSottoSoglia);
    }

    // ── Esportazione Report ──

    /** @return ReportProdottoDTO[] */
    public function reportCompleto(): array {
        $prodotti = $this->prodottoRepository->findAll();
        $report = [];

        foreach ($prodotti as $prodotto) {
            $codProd = (string) $prodotto->getCodProd();
            $giacenzaTotale = $this->giacenzaRepository->giacenzaTotale($prodotto->getCodProd());

            $report[] = new ReportProdottoDTO(
                $codProd,
                $prodotto->getQtaRiordino(),
                $giacenzaTotale,
                $prodotto->necessitaRiordino($giacenzaTotale),
                $prodotto->getCodCat() !== null ? (string) $prodotto->getCodCat() : null,
                $prodotto->getCodReg() !== null ? (string) $prodotto->getCodReg() : null,
                $prodotto->getCodOE() !== null ? (string) $prodotto->getCodOE() : null
            );
        }

        return $report;
    }

    /** @return PosProdDTO[] */
    public function reportGiacenze(): array {
        return array_map(
            fn(PosProd $p) => new PosProdDTO(
                (string) $p->getCodProd(),
                (string) $p->getCodArmadio(),
                (string) $p->getCodScaffale(),
                $p->getQta()
            ),
            $this->giacenzaRepository->findAll()
        );
    }
}
