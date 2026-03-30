<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\Categoria\CategoriaTipo;
/**
 * Class Categoria
 *
 * @package src\Domain\Models
 */
class Categoria {
    private CategoriaId $codCat;
    private CategoriaTipo $tipo;

    public function __construct(CategoriaId $codCat, CategoriaTipo $tipo) {
        $this->codCat = $codCat;
        $this->tipo = $tipo;
    }

    public static function reconstituteFromDatabase(CategoriaId $codCat, CategoriaTipo $tipo): self {
        return new self($codCat, $tipo);
    }

    public function getCodCat(): CategoriaId {
        return $this->codCat;
    }

    public function setCodCat(CategoriaId $codCat): void {
        $this->codCat = $codCat;
    }

    public function getTipo(): CategoriaTipo {
        return $this->tipo;
    }

    public function setTipo(CategoriaTipo $tipo): void {
        $this->tipo = $tipo;
    }
}
