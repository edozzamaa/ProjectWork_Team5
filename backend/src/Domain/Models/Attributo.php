<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValueObjects\Attributo\AttributoId;
use src\Domain\ValueObjects\Attributo\AttributoNome;
/**
 * Class Attributo
 *
 * @package src\Domain\Models
 */
class Attributo {
    private AttributoId $codAttr;
    private AttributoNome $nome;

    public function __construct(AttributoId $codAttr, AttributoNome $nome) {
        $this->codAttr = $codAttr;
        $this->nome = $nome;
    }

    public static function reconstituteFromDatabase(AttributoId $codAttr, AttributoNome $nome): self {
        return new self($codAttr, $nome);
    }

    public function getCodAttr(): AttributoId {
        return $this->codAttr;
    }

    public function setCodAttr(AttributoId $codAttr): void {
        $this->codAttr = $codAttr;
    }

    public function getNome(): AttributoNome {
        return $this->nome;
    }

    public function setNome(AttributoNome $nome): void {
        $this->nome = $nome;
    }
}
