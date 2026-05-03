<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Prodotto;
use src\Domain\Models\Categoria;
use src\Domain\Models\PosProd;
use src\Application\Interfaces\IServices\IReportService;
use src\Application\DTO\Output\ProdottoDTO;
use src\Application\DTO\Output\CategoriaDTO;
use src\Application\DTO\Output\PanoramicaDTO;
use src\Application\DTO\Output\ReportProdottoDTO;
use src\Application\DTO\Output\PosProdDTO;
use src\Application\Interfaces\IRepositories\IGiacenzaRepository;
use src\Application\Interfaces\IRepositories\IProdottoRepository;
use src\Application\Interfaces\IRepositories\ICategoriaRepository;

class ReportService implements IReportService {

    private IGiacenzaRepository $giacenzaRepository;
    private IProdottoRepository $prodottoRepository;
    private ICategoriaRepository $categoriaRepository;

    public function __construct(
        IGiacenzaRepository $giacenzaRepository,
        IProdottoRepository $prodottoRepository,
        ICategoriaRepository $categoriaRepository
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
                $prodotto->getQtaRiordino()->value,
                $prodotto->getCodCat() !== null ? (string) $prodotto->getCodCat() : null,
                $prodotto->getCodReg() !== null ? (string) $prodotto->getCodReg() : null,
                $prodotto->getCodOE() !== null ? (string) $prodotto->getCodOE() : null,
                $giacenzaTotale
            );
        }

        $categorieDTO = array_map(
            fn(Categoria $c) => new CategoriaDTO((string) $c->getCodCat(), $c->getTipo()->value),
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
                $prodotto->getQtaRiordino()->value,
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
                $p->getQta()->value
            ),
            $this->giacenzaRepository->findAll()
        );
    }
}
