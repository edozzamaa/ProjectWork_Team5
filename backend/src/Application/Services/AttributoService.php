<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Attributo;
use src\Domain\Models\AttrProd;
use src\Domain\ValueObjects\Attributo\AttributoId;
use src\Domain\ValueObjects\Attributo\AttributoNome;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\AttrProd\ValoreAttributo;
use src\Application\Interfaces\IServices\IAttributoService;
use src\Application\DTO\AttributoDTO;
use src\Application\DTO\AttrProdDTO;
use src\Application\Interfaces\IRepositories\IAttributoRepository;
use src\Application\Interfaces\IRepositories\IProdottoRepository;

class AttributoService implements IAttributoService {

    private IAttributoRepository $attributoRepository;
    private IProdottoRepository $prodottoRepository;

    public function __construct(
        IAttributoRepository $attributoRepository,
        IProdottoRepository $prodottoRepository
    ) {
        $this->attributoRepository = $attributoRepository;
        $this->prodottoRepository = $prodottoRepository;
    }

    private function toDTO(Attributo $attributo): AttributoDTO {
        return new AttributoDTO(
            (string) $attributo->getCodAttr(),
            $attributo->getNome()->value
        );
    }

    private function attrProdToDTO(AttrProd $attrProd): AttrProdDTO {
        return new AttrProdDTO(
            (string) $attrProd->getCodProd(),
            (string) $attrProd->getCodAttr(),
            $attrProd->getValore()?->value
        );
    }

    // ── CRUD Attributo ──

    /** @return AttributoDTO[] */
    public function getAll(): array {
        return array_map(fn(Attributo $a) => $this->toDTO($a), $this->attributoRepository->findAll());
    }

    public function getByCod(string $codAttr): ?AttributoDTO {
        $attributo = $this->attributoRepository->findByCod(new AttributoId($codAttr));
        return $attributo !== null ? $this->toDTO($attributo) : null;
    }

    public function createAttributo(string $codAttr, string $nome): void {
        if ($this->attributoRepository->findByCod(new AttributoId($codAttr)) !== null) {
            throw new \RuntimeException("Attributo '{$codAttr}' già esistente.");
        }
        $attributo = new Attributo(new AttributoId($codAttr), new AttributoNome($nome));
        $this->attributoRepository->save($attributo);
    }

    public function updateAttributo(string $codAttr, string $nome): void {
        $attributo = $this->attributoRepository->findByCod(new AttributoId($codAttr));
        if ($attributo === null) {
            throw new \RuntimeException("Attributo '{$codAttr}' non trovato.");
        }
        $attributo->setNome(new AttributoNome($nome));
        $this->attributoRepository->save($attributo);
    }

    public function deleteAttributo(string $codAttr): void {
        if ($this->attributoRepository->findByCod(new AttributoId($codAttr)) === null) {
            throw new \RuntimeException("Attributo '{$codAttr}' non trovato.");
        }
        $this->attributoRepository->delete(new AttributoId($codAttr));
    }

    // ── Assegnazione Attributi a Prodotto ──

    public function assignToProdotto(string $codProd, string $codAttr, ?string $valore = null): void {
        if ($this->prodottoRepository->findByCod(new ProdottoId($codProd)) === null) {
            throw new \RuntimeException("Prodotto '{$codProd}' non trovato.");
        }
        if ($this->attributoRepository->findByCod(new AttributoId($codAttr)) === null) {
            throw new \RuntimeException("Attributo '{$codAttr}' non trovato.");
        }
        $attrProd = new AttrProd(new ProdottoId($codProd), new AttributoId($codAttr), $valore !== null ? new ValoreAttributo($valore) : null);
        $this->prodottoRepository->saveAttributo($attrProd);
    }

    public function removeFromProdotto(string $codProd, string $codAttr): void {
        $this->prodottoRepository->deleteAttributo(new ProdottoId($codProd), new AttributoId($codAttr));
    }

    /** @return AttrProdDTO[] */
    public function getAttributiProdotto(string $codProd): array {
        return array_map(fn(AttrProd $a) => $this->attrProdToDTO($a), $this->prodottoRepository->getAttributi(new ProdottoId($codProd)));
    }
}
