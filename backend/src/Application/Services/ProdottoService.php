<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Prodotto;
use src\Domain\Models\AttrProd;
use src\Domain\Models\PosProd;
use src\Domain\ValuesObject\ID;
use src\Application\Interfaces\IProdottoService;
use src\Application\DTO\ProdottoDTO;
use src\Application\DTO\AttrProdDTO;
use src\Application\DTO\ScaricoProdottoResultDTO;
use src\Infrastructure\Repositories\ProdottoRepository;
use src\Infrastructure\Repositories\ArmadioRepository;
use src\Infrastructure\Repositories\GiacenzaRepository;

class ProdottoService implements IProdottoService {

    private ProdottoRepository $prodottoRepository;
    private ArmadioRepository $armadioRepository;
    private GiacenzaRepository $giacenzaRepository;

    public function __construct(
        ProdottoRepository $prodottoRepository,
        ArmadioRepository $armadioRepository,
        GiacenzaRepository $giacenzaRepository
    ) {
        $this->prodottoRepository = $prodottoRepository;
        $this->armadioRepository = $armadioRepository;
        $this->giacenzaRepository = $giacenzaRepository;
    }

    private function toDTO(Prodotto $prodotto, ?int $giacenzaTotale = null): ProdottoDTO {
        return new ProdottoDTO(
            (string) $prodotto->getCodProd(),
            $prodotto->getQtaRiordino(),
            $prodotto->getCodCat() !== null ? (string) $prodotto->getCodCat() : null,
            $prodotto->getCodReg() !== null ? (string) $prodotto->getCodReg() : null,
            $prodotto->getCodOE() !== null ? (string) $prodotto->getCodOE() : null,
            $giacenzaTotale
        );
    }

    private function attrProdToDTO(AttrProd $attrProd): AttrProdDTO {
        return new AttrProdDTO(
            (string) $attrProd->getCodProd(),
            (string) $attrProd->getCodAttr(),
            $attrProd->getValore()
        );
    }

    // ── CRUD Prodotto ──

    /** @return ProdottoDTO[] */
    public function getAll(): array {
        return array_map(fn(Prodotto $p) => $this->toDTO($p), $this->prodottoRepository->findAll());
    }

    public function getByCod(string $codProd): ?ProdottoDTO {
        $prodotto = $this->prodottoRepository->findByCod(new ID($codProd));
        return $prodotto !== null ? $this->toDTO($prodotto) : null;
    }

    /** @return ProdottoDTO[] */
    public function getByCategoria(string $codCat): array {
        return array_map(fn(Prodotto $p) => $this->toDTO($p), $this->prodottoRepository->findByCategoria(new ID($codCat)));
    }

    public function crea(string $codProd, int $qtaRiordino = 0, ?string $codCat = null, ?string $codReg = null, ?string $codOE = null): void {
        if ($this->prodottoRepository->findByCod(new ID($codProd)) !== null) {
            throw new \RuntimeException("Prodotto '{$codProd}' già esistente.");
        }
        $prodotto = new Prodotto(
            new ID($codProd),
            $qtaRiordino,
            $codCat !== null ? new ID($codCat) : null,
            $codReg !== null ? new ID($codReg) : null,
            $codOE !== null ? new ID($codOE) : null
        );
        $this->prodottoRepository->save($prodotto);
    }

    public function aggiorna(string $codProd, int $qtaRiordino, ?string $codCat = null, ?string $codReg = null, ?string $codOE = null): void {
        $prodotto = $this->prodottoRepository->findByCod(new ID($codProd));
        if ($prodotto === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }
        $prodotto->setQtaRiordino($qtaRiordino);
        $prodotto->setCodCat($codCat !== null ? new ID($codCat) : null);
        $prodotto->setCodReg($codReg !== null ? new ID($codReg) : null);
        $prodotto->setCodOE($codOE !== null ? new ID($codOE) : null);
        $this->prodottoRepository->save($prodotto);
    }

    public function elimina(string $codProd): void {
        if ($this->prodottoRepository->findByCod(new ID($codProd)) === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }
        $this->prodottoRepository->delete(new ID($codProd));
    }

    /** @return AttrProdDTO[] */
    public function getAttributi(string $codProd): array {
        return array_map(fn(AttrProd $a) => $this->attrProdToDTO($a), $this->prodottoRepository->getAttributi(new ID($codProd)));
    }

    // ── Carico Prodotto ──

    /**
     * @param array<string, string> $attributi
     */
    public function carico(string $codProd, string $codArmadio, string $codScaffale, int $qta, array $attributi = []): void {
        if ($qta <= 0) {
            throw new \InvalidArgumentException("La quantità deve essere maggiore di zero.");
        }

        $prodotto = $this->prodottoRepository->findByCod(new ID($codProd));
        if ($prodotto === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }

        $posizione = $this->armadioRepository->findPosizione(new ID($codArmadio), new ID($codScaffale));
        if ($posizione === null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' non trovata.");
        }

        $giacenza = $this->giacenzaRepository->find(new ID($codProd), new ID($codArmadio), new ID($codScaffale));

        if ($giacenza !== null) {
            $giacenza->setQta($giacenza->getQta() + $qta);
            $this->giacenzaRepository->save($giacenza);
        } else {
            $nuovaGiacenza = new PosProd(
                new ID($codProd),
                new ID($codArmadio),
                new ID($codScaffale),
                $qta
            );
            $this->giacenzaRepository->save($nuovaGiacenza);
        }

        foreach ($attributi as $codAttr => $valore) {
            $attrProd = new AttrProd(new ID($codProd), new ID($codAttr), $valore);
            $this->prodottoRepository->saveAttributo($attrProd);
        }
    }

    // ── Scarico Prodotto ──

    public function scarico(string $codProd, string $codArmadio, string $codScaffale, int $qta): ScaricoProdottoResultDTO {
        if ($qta <= 0) {
            throw new \InvalidArgumentException("La quantità deve essere maggiore di zero.");
        }

        $prodotto = $this->prodottoRepository->findByCod(new ID($codProd));
        if ($prodotto === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }

        $giacenza = $this->giacenzaRepository->find(new ID($codProd), new ID($codArmadio), new ID($codScaffale));
        if ($giacenza === null) {
            throw new \RuntimeException("Nessuna giacenza trovata per '{$codProd}' nella posizione '{$codArmadio}/{$codScaffale}'.");
        }

        if ($giacenza->getQta() < $qta) {
            throw new \RuntimeException(
                "Quantità insufficiente: disponibile {$giacenza->getQta()}, richiesta {$qta}."
            );
        }

        $nuovaQta = $giacenza->getQta() - $qta;
        if ($nuovaQta === 0) {
            $this->giacenzaRepository->delete(new ID($codProd), new ID($codArmadio), new ID($codScaffale));
        } else {
            $giacenza->setQta($nuovaQta);
            $this->giacenzaRepository->save($giacenza);
        }

        $giacenzaTotale = $this->giacenzaRepository->giacenzaTotale(new ID($codProd));
        $sottoSoglia = $prodotto->necessitaRiordino($giacenzaTotale);

        return new ScaricoProdottoResultDTO($sottoSoglia, $prodotto->getQtaRiordino(), $giacenzaTotale);
    }

    // ── Ricerca Prodotto ──

    /** @return ProdottoDTO[] */
    public function cercaConGiacenza(): array {
        $prodotti = $this->prodottoRepository->findAll();
        $risultati = [];

        foreach ($prodotti as $prodotto) {
            $risultati[] = $this->toDTO($prodotto, $this->giacenzaRepository->giacenzaTotale($prodotto->getCodProd()));
        }

        return $risultati;
    }
}