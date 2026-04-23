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
use src\Application\DTO\Input\GetAttributoByCodInput;
use src\Application\DTO\Input\CreateAttributoInput;
use src\Application\DTO\Input\UpdateAttributoInput;
use src\Application\DTO\Input\DeleteAttributoInput;
use src\Application\DTO\Input\AssignAttributoToProdottoInput;
use src\Application\DTO\Input\RemoveAttributoFromProdottoInput;
use src\Application\DTO\Input\GetAttributiProdottoInput;
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

    public function getByCod(GetAttributoByCodInput $input): ?AttributoDTO {
        $attributo = $this->attributoRepository->findByCod(new AttributoId($input->codAttr));
        return $attributo !== null ? $this->toDTO($attributo) : null;
    }

    public function createAttributo(CreateAttributoInput $input): void {
        if ($this->attributoRepository->findByCod(new AttributoId($input->codAttr)) !== null) {
            throw new \RuntimeException("Attributo '{$input->codAttr}' già esistente.");
        }
        $attributo = new Attributo(new AttributoId($input->codAttr), new AttributoNome($input->nome));
        $this->attributoRepository->save($attributo);
    }

    public function updateAttributo(UpdateAttributoInput $input): void {
        $attributo = $this->attributoRepository->findByCod(new AttributoId($input->codAttr));
        if ($attributo === null) {
            throw new \RuntimeException("Attributo '{$input->codAttr}' non trovato.");
        }
        $attributo->setNome(new AttributoNome($input->nome));
        $this->attributoRepository->save($attributo);
    }

    public function deleteAttributo(DeleteAttributoInput $input): void {
        if ($this->attributoRepository->findByCod(new AttributoId($input->codAttr)) === null) {
            throw new \RuntimeException("Attributo '{$input->codAttr}' non trovato.");
        }
        $this->attributoRepository->delete(new AttributoId($input->codAttr));
    }

    // ── Assegnazione Attributi a Prodotto ──

    public function assignToProdotto(AssignAttributoToProdottoInput $input): void {
        if ($this->prodottoRepository->findByCod(new ProdottoId($input->codProd)) === null) {
            throw new \RuntimeException("Prodotto '{$input->codProd}' non trovato.");
        }
        if ($this->attributoRepository->findByCod(new AttributoId($input->codAttr)) === null) {
            throw new \RuntimeException("Attributo '{$input->codAttr}' non trovato.");
        }
        $attrProd = new AttrProd(new ProdottoId($input->codProd), new AttributoId($input->codAttr), $input->valore !== null ? new ValoreAttributo($input->valore) : null);
        $this->prodottoRepository->saveAttributo($attrProd);
    }

    public function removeFromProdotto(RemoveAttributoFromProdottoInput $input): void {
        $this->prodottoRepository->deleteAttributo(new ProdottoId($input->codProd), new AttributoId($input->codAttr));
    }

    /** @return AttrProdDTO[] */
    public function getAttributiProdotto(GetAttributiProdottoInput $input): array {
        return array_map(fn(AttrProd $a) => $this->attrProdToDTO($a), $this->prodottoRepository->getAttributi(new ProdottoId($input->codProd)));
    }
}
