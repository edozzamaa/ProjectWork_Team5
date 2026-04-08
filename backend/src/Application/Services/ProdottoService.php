<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Prodotto;
use src\Domain\Models\AttrProd;
use src\Domain\Models\PosProd;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Prodotto\QuantitaRiordino;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\CodificaReg\CodificaRegId;
use src\Domain\ValueObjects\CodificaOE\CodificaOEId;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Posizione\ScaffaleId;
use src\Domain\ValueObjects\Attributo\AttributoId;
use src\Domain\ValueObjects\AttrProd\ValoreAttributo;
use src\Domain\ValueObjects\PosProd\Quantita;
use src\Application\Interfaces\IServices\IProdottoService;
use src\Application\DTO\ProdottoDTO;
use src\Application\DTO\AttrProdDTO;
use src\Application\DTO\ScaricoProdottoResultDTO;
use src\Application\Interfaces\IRepositories\IProdottoRepository;
use src\Application\Interfaces\IRepositories\IArmadioRepository;
use src\Application\Interfaces\IRepositories\IGiacenzaRepository;

class ProdottoService implements IProdottoService {

    private IProdottoRepository $prodottoRepository;
    private IArmadioRepository $armadioRepository;
    private IGiacenzaRepository $giacenzaRepository;

    public function __construct(
        IProdottoRepository $prodottoRepository,
        IArmadioRepository $armadioRepository,
        IGiacenzaRepository $giacenzaRepository
    ) {
        $this->prodottoRepository = $prodottoRepository;
        $this->armadioRepository = $armadioRepository;
        $this->giacenzaRepository = $giacenzaRepository;
    }

    /** @param AttrProdDTO[] $attributi */
    private function toDTO(Prodotto $prodotto, ?int $giacenzaTotale = null, array $attributi = []): ProdottoDTO {
        return new ProdottoDTO(
            (string) $prodotto->getCodProd(),
            $prodotto->getQtaRiordino()->value,
            $prodotto->getCodCat() !== null ? (string) $prodotto->getCodCat() : null,
            $prodotto->getCodReg() !== null ? (string) $prodotto->getCodReg() : null,
            $prodotto->getCodOE() !== null ? (string) $prodotto->getCodOE() : null,
            $giacenzaTotale,
            $attributi
        );
    }

    private function attrProdToDTO(AttrProd $attrProd): AttrProdDTO {
        return new AttrProdDTO(
            (string) $attrProd->getCodProd(),
            (string) $attrProd->getCodAttr(),
            $attrProd->getValore()?->value
        );
    }

    /** @return array<string, AttrProdDTO[]> */
    private function groupAttributiByProdotto(array $attrList): array {
        $map = [];
        foreach ($attrList as $attr) {
            $dto = $this->attrProdToDTO($attr);
            $map[$dto->codProd][] = $dto;
        }
        return $map;
    }

    // ── CRUD Prodotto ──

    /** @return ProdottoDTO[] */
    public function getAll(): array {
        $prodotti = $this->prodottoRepository->findAll();
        $attrMap = $this->groupAttributiByProdotto($this->prodottoRepository->findAllAttributi());
        return array_map(fn(Prodotto $p) => $this->toDTO($p, null, $attrMap[(string) $p->getCodProd()] ?? []), $prodotti);
    }

    public function getByCod(string $codProd): ?ProdottoDTO {
        $prodotto = $this->prodottoRepository->findByCod(new ProdottoId($codProd));
        if ($prodotto === null) return null;
        $attributi = array_map(fn($a) => $this->attrProdToDTO($a), $this->prodottoRepository->getAttributi(new ProdottoId($codProd)));
        return $this->toDTO($prodotto, null, $attributi);
    }

    /** @return ProdottoDTO[] */
    public function getByCategoria(string $codCat): array {
        $prodotti = $this->prodottoRepository->findByCategoria(new CategoriaId($codCat));
        $attrMap = $this->groupAttributiByProdotto($this->prodottoRepository->findAllAttributi());
        return array_map(fn(Prodotto $p) => $this->toDTO($p, null, $attrMap[(string) $p->getCodProd()] ?? []), $prodotti);
    }

    public function createProdotto(string $codProd, int $qtaRiordino = 0, ?string $codCat = null, ?string $codReg = null, ?string $codOE = null): void {
        if ($this->prodottoRepository->findByCod(new ProdottoId($codProd)) !== null) {
            throw new \RuntimeException("Prodotto '{$codProd}' già esistente.");
        }
        $prodotto = new Prodotto(
            new ProdottoId($codProd),
            new QuantitaRiordino($qtaRiordino),
            $codCat !== null ? new CategoriaId($codCat) : null,
            $codReg !== null ? new CodificaRegId($codReg) : null,
            $codOE !== null ? new CodificaOEId($codOE) : null
        );
        $this->prodottoRepository->save($prodotto);
    }

    public function updateProdotto(string $codProd, array $fields): void {
        $prodotto = $this->prodottoRepository->findByCod(new ProdottoId($codProd));
        if ($prodotto === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }
        if (array_key_exists('qtaRiordino', $fields)) {
            $prodotto->setQtaRiordino(new QuantitaRiordino((int) $fields['qtaRiordino']));
        }
        if (array_key_exists('codCat', $fields)) {
            $prodotto->setCodCat($fields['codCat'] !== null ? new CategoriaId($fields['codCat']) : null);
        }
        if (array_key_exists('codReg', $fields)) {
            $prodotto->setCodReg($fields['codReg'] !== null ? new CodificaRegId($fields['codReg']) : null);
        }
        if (array_key_exists('codOE', $fields)) {
            $prodotto->setCodOE($fields['codOE'] !== null ? new CodificaOEId($fields['codOE']) : null);
        }
        $this->prodottoRepository->update($prodotto, array_keys($fields));
    }

    public function deleteProdotto(string $codProd): void {
        if ($this->prodottoRepository->findByCod(new ProdottoId($codProd)) === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }
        $this->prodottoRepository->delete(new ProdottoId($codProd));
    }

    /** @return AttrProdDTO[] */
    public function getAttributi(string $codProd): array {
        return array_map(fn(AttrProd $a) => $this->attrProdToDTO($a), $this->prodottoRepository->getAttributi(new ProdottoId($codProd)));
    }

    // ── Carico Prodotto ──

    /**
     * @param array<string, string> $attributi
     */
    public function loadProdotto(string $codProd, string $codArmadio, string $codScaffale, int $qta, array $attributi = []): void {
        if ($qta <= 0) {
            throw new \InvalidArgumentException("La quantità deve essere maggiore di zero.");
        }

        $prodotto = $this->prodottoRepository->findByCod(new ProdottoId($codProd));
        if ($prodotto === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }

        $posizione = $this->armadioRepository->findPosizione(new ArmadioId($codArmadio), new ScaffaleId($codScaffale));
        if ($posizione === null) {
            throw new \RuntimeException("Posizione '{$codArmadio}/{$codScaffale}' non trovata.");
        }

        $giacenza = $this->giacenzaRepository->find(new ProdottoId($codProd), new ArmadioId($codArmadio), new ScaffaleId($codScaffale));

        if ($giacenza !== null) {
            $giacenza->setQta($giacenza->getQta()->aggiungi($qta));
            $this->giacenzaRepository->save($giacenza);
        } else {
            $nuovaGiacenza = new PosProd(
                new ProdottoId($codProd),
                new ArmadioId($codArmadio),
                new ScaffaleId($codScaffale),
                new Quantita($qta)
            );
            $this->giacenzaRepository->save($nuovaGiacenza);
        }

        foreach ($attributi as $codAttr => $valore) {
            $attrProd = new AttrProd(new ProdottoId($codProd), new AttributoId($codAttr), $valore !== null ? new ValoreAttributo($valore) : null);
            $this->prodottoRepository->saveAttributo($attrProd);
        }
    }

    // ── Scarico Prodotto ──

    public function unloadProdotto(string $codProd, string $codArmadio, string $codScaffale, int $qta): ScaricoProdottoResultDTO {
        if ($qta <= 0) {
            throw new \InvalidArgumentException("La quantità deve essere maggiore di zero.");
        }

        $prodotto = $this->prodottoRepository->findByCod(new ProdottoId($codProd));
        if ($prodotto === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }

        $giacenza = $this->giacenzaRepository->find(new ProdottoId($codProd), new ArmadioId($codArmadio), new ScaffaleId($codScaffale));
        if ($giacenza === null) {
            throw new \RuntimeException("Nessuna giacenza trovata per '{$codProd}' nella posizione '{$codArmadio}/{$codScaffale}'.");
        }

        $nuovaQta = $giacenza->getQta()->sottrai($qta);
        if ($nuovaQta->isZero()) {
            $this->giacenzaRepository->delete(new ProdottoId($codProd), new ArmadioId($codArmadio), new ScaffaleId($codScaffale));
        } else {
            $giacenza->setQta($nuovaQta);
            $this->giacenzaRepository->save($giacenza);
        }

        $giacenzaTotale = $this->giacenzaRepository->giacenzaTotale(new ProdottoId($codProd));
        $sottoSoglia = $prodotto->necessitaRiordino($giacenzaTotale);

        return new ScaricoProdottoResultDTO($sottoSoglia, $prodotto->getQtaRiordino()->value, $giacenzaTotale);
    }

    // ── Ricerca Prodotto ──

    /** @return ProdottoDTO[] */
    public function searchWithStock(): array {
        $prodotti = $this->prodottoRepository->findAll();
        $risultati = [];

        foreach ($prodotti as $prodotto) {
            $risultati[] = $this->toDTO($prodotto, $this->giacenzaRepository->giacenzaTotale($prodotto->getCodProd()));
        }

        return $risultati;
    }
}