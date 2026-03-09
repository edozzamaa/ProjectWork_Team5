<?php declare(strict_types=1);
    namespace src\Application\Services;

    use src\Domain\Models\Prodotto;
    use src\Domain\ValuesObject\ID;
    use src\Domain\ValuesObjects\Email;
    use src\Application\DTO\ProdottiDTO;
    use src\Application\Interfaces\IProdottoRepository;

    class ProdottoService {
        private IProdottoRepository $prodottoRepository;

        public function __construct(IProdottoRepository $prodottoRepository) {
            $this->prodottoRepository = $prodottoRepository;
        }

        public function getAllProdotti(): array {
            return $this->prodottoRepository->findAll();
        }

        public function getProdottoById(ID $codProd): ?Prodotto {
            return $this->prodottoRepository->findById($codProd);
        }

        public function createProdotto(Prodotto $prodotto): void {
            $this->prodottoRepository->save($prodotto);
        }

        public function updateProdotto(Prodotto $prodotto): void {
            $this->prodottoRepository->update($prodotto);
        }

        public function deleteProdotto(ID $codProd): void {
            $this->prodottoRepository->delete($codProd);
        }

        public function getProdottiByCategoria(ID $codCat): array {
            return $this->prodottoRepository->findByCategoria($codCat);
        }

        public function getProdottiByRegione(ID $codReg): array {
            return $this->prodottoRepository->findByRegione($codReg);
        }

        public function getProdottiByOE(ID $codOE): array {
            return $this->prodottoRepository->findByOE($codOE);
        }

        public function getProdottiByAttr(ID $codAttr, string $valore): array {
            return $this->prodottoRepository->findByAttr($codAttr, $valore);
        }

        public function getProdottiByMultipleCriteria(?ID $codCat, ?ID $codReg, ?ID $codOE, ?array $attributes): array {
            return $this->prodottoRepository->findByMultipleCriteria($codCat, $codReg, $codOE, $attributes);
        }

        public function getProdottiByQtaRiordino(int $qtaRiordino): array {
            return $this->prodottoRepository->findByQtaRiordino($qtaRiordino);
        }
    }