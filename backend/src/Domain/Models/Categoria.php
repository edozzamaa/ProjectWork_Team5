<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObjects\ID;
/**
 * Class Categoria
 *
 * @package src\Domain\Models
 * @property ID $codCat
 * @property string $tipo
 */
class Categoria {
    private ID $codCat;
    private string $tipo;

    public function __construct(ID $codCat, string $tipo) {
        $this->codCat = $codCat;
        $this->tipo = $tipo;
    }

    public static function reconstituteFromDatabase(ID $codCat, string $tipo): self {
        return new self($codCat, $tipo);
    }

    public function getCodCat(): ID {
        return $this->codCat;
    }

    public function setCodCat(ID $codCat): void {
        $this->codCat = $codCat;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }
}
