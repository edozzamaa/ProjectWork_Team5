<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class Categoria
 *
 * @package src\Domain\Models
 * @property string $codCat
 * @property string $tipo
 */
class Categoria {
    private string $codCat;
    private string $tipo;

    public function __construct(string $codCat, string $tipo) {
        $this->codCat = $codCat;
        $this->tipo = $tipo;
    }

    public static function reconstituteFromDatabase(string $codCat, string $tipo): self {
        return new self($codCat, $tipo);
    }

    public function getCodCat(): string {
        return $this->codCat;
    }

    public function setCodCat(string $codCat): void {
        $this->codCat = $codCat;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }
}
