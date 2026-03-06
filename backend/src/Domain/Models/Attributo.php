<?php declare(strict_types=1);
namespace src\Domain\Models;

/**
 * Class Attributo
 *
 * @package src\Domain\Models
 * @property string $codAttr
 * @property string $nome
 */
class Attributo {
    private string $codAttr;
    private string $nome;

    public function __construct(string $codAttr, string $nome) {
        $this->codAttr = $codAttr;
        $this->nome = $nome;
    }

    public static function reconstituteFromDatabase(string $codAttr, string $nome): self {
        return new self($codAttr, $nome);
    }

    public function getCodAttr(): string {
        return $this->codAttr;
    }

    public function setCodAttr(string $codAttr): void {
        $this->codAttr = $codAttr;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }
}
