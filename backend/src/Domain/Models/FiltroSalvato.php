<?php declare(strict_types=1);

namespace src\Domain\Models;

use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoId;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoNome;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoStato;

class FiltroSalvato
{
    private FiltroSalvatoId $id;
    private FiltroSalvatoNome $nome;
    private FiltroSalvatoStato $stato;

    public function __construct(FiltroSalvatoId $id, FiltroSalvatoNome $nome, FiltroSalvatoStato $stato)
    {
        $this->id    = $id;
        $this->nome  = $nome;
        $this->stato = $stato;
    }

    public static function reconstituteFromDatabase(
        FiltroSalvatoId $id,
        FiltroSalvatoNome $nome,
        FiltroSalvatoStato $stato
    ): self {
        return new self($id, $nome, $stato);
    }

    public function getId(): FiltroSalvatoId   { return $this->id; }
    public function getNome(): FiltroSalvatoNome  { return $this->nome; }
    public function getStato(): FiltroSalvatoStato { return $this->stato; }

    public function setNome(FiltroSalvatoNome $nome): void   { $this->nome  = $nome; }
    public function setStato(FiltroSalvatoStato $stato): void { $this->stato = $stato; }
}
