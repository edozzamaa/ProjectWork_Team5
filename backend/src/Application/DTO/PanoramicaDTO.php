<?php declare(strict_types=1);
namespace src\Application\DTO;

class PanoramicaDTO {

    /** @var ProdottoDTO[] */
    public readonly array $prodotti;
    /** @var CategoriaDTO[] */
    public readonly array $categorie;
    public readonly int $totaleReferenze;
    public readonly int $prodottiSottoSoglia;

    /**
     * @param ProdottoDTO[] $prodotti
     * @param CategoriaDTO[] $categorie
     */
    public function __construct(array $prodotti, array $categorie, int $totaleReferenze, int $prodottiSottoSoglia) {
        $this->prodotti = $prodotti;
        $this->categorie = $categorie;
        $this->totaleReferenze = $totaleReferenze;
        $this->prodottiSottoSoglia = $prodottiSottoSoglia;
    }
}
