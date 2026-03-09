<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Attributo;
use src\Domain\Models\AttrProd;
use src\Domain\ValuesObject\ID;
use src\Application\Interfaces\IAttributoService;
use src\Application\DTO\AttributoDTO;
use src\Application\DTO\AttrProdDTO;
use src\Infrastructure\Repositories\AttributoRepository;
use src\Infrastructure\Repositories\ProdottoRepository;

class AttributoService implements IAttributoService {

    private AttributoRepository $attributoRepository;
    private ProdottoRepository $prodottoRepository;

    public function __construct(
        AttributoRepository $attributoRepository,
        ProdottoRepository $prodottoRepository
    ) {
        $this->attributoRepository = $attributoRepository;
        $this->prodottoRepository = $prodottoRepository;
    }

    private function toDTO(Attributo $attributo): AttributoDTO {
        return new AttributoDTO(
            (string) $attributo->getCodAttr(),
            $attributo->getNome()
        );
    }

    private function attrProdToDTO(AttrProd $attrProd): AttrProdDTO {
        return new AttrProdDTO(
            (string) $attrProd->getCodProd(),
            (string) $attrProd->getCodAttr(),
            $attrProd->getValore()
        );
    }

    // ── CRUD Attributo ──

    /** @return AttributoDTO[] */
    public function getAll(): array {
        return array_map(fn(Attributo $a) => $this->toDTO($a), $this->attributoRepository->findAll());
    }

    public function getByCod(string $codAttr): ?AttributoDTO {
        $attributo = $this->attributoRepository->findByCod(new ID($codAttr));
        return $attributo !== null ? $this->toDTO($attributo) : null;
    }

    public function crea(string $codAttr, string $nome): void {
        if ($this->attributoRepository->findByCod(new ID($codAttr)) !== null) {
            throw new \RuntimeException("Attributo '{$codAttr}' già esistente.");
        }
        $attributo = new Attributo(new ID($codAttr), $nome);
        $this->attributoRepository->save($attributo);
    }

    public function aggiorna(string $codAttr, string $nome): void {
        $attributo = $this->attributoRepository->findByCod(new ID($codAttr));
        if ($attributo === null) {
            throw new \RuntimeException("Attributo '{$codAttr}' non trovato.");
        }
        $attributo->setNome($nome);
        $this->attributoRepository->save($attributo);
    }

    public function elimina(string $codAttr): void {
        if ($this->attributoRepository->findByCod(new ID($codAttr)) === null) {
            throw new \RuntimeException("Attributo '{$codAttr}' non trovato.");
        }
        $this->attributoRepository->delete(new ID($codAttr));
    }

    // ── Assegnazione Attributi a Prodotto ──

    public function assegnaAProdotto(string $codProd, string $codAttr, ?string $valore = null): void {
        if ($this->prodottoRepository->findByCod(new ID($codProd)) === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }
        if ($this->attributoRepository->findByCod(new ID($codAttr)) === null) {
            throw new \RuntimeException("Attributo '{$codAttr}' non trovato.");
        }
        $attrProd = new AttrProd(new ID($codProd), new ID($codAttr), $valore);
        $this->prodottoRepository->saveAttributo($attrProd);
    }

    public function rimuoviDaProdotto(string $codProd, string $codAttr): void {
        $this->prodottoRepository->deleteAttributo(new ID($codProd), new ID($codAttr));
    }

    /** @return AttrProdDTO[] */
    public function getAttributiProdotto(string $codProd): array {
        return array_map(fn(AttrProd $a) => $this->attrProdToDTO($a), $this->prodottoRepository->getAttributi(new ID($codProd)));
    }
}
