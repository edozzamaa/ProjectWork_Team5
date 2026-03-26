<?php declare(strict_types=1);
namespace src\Domain\Models;
use src\Domain\ValuesObject\ID;
/**
 * Class Attributo
 *
 * @package src\Domain\Models
 * @property ID $codAttr
 * @property string $nome
 */
class Attributo {
    private ID $codAttr;
    private string $nome;

    public function __construct(ID $codAttr, string $nome) {
        $this->codAttr = $codAttr;
        $this->nome = $nome;
    }

    public static function reconstituteFromDatabase(ID $codAttr, string $nome): self {
        return new self($codAttr, $nome);
    }

    public function getCodAttr(): ID {
        return $this->codAttr;
    }

    public function setCodAttr(ID $codAttr): void {
        $this->codAttr = $codAttr;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }
}
