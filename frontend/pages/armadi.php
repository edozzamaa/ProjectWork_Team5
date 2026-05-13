<?php include '_header.php'; ?>

<section aria-labelledby="titoloArmadi">
<header class="d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="section-title mb-1" id="titoloArmadi"><i class="bi bi-archive me-2" aria-hidden="true"></i>Armadi e Posizioni</h1>
        <p class="text-muted small mb-0">Gestisci la struttura fisica del magazzino: armadi e scaffali.</p>
    </div>
    <button class="btn btn-primary btn-sm" id="btnNuovoArmadio">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuovo armadio
    </button>
</header>

<div id="armadioList" aria-live="polite">
    <p class="text-muted">Caricamento...</p>
</div>
</section>

<!-- Modal Armadio -->
<div class="modal fade" id="modalArmadio" tabindex="-1" aria-labelledby="titleArmadio" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="titleArmadio">Armadio</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formArmadio" novalidate>
            <fieldset>
                <legend class="visually-hidden">Dati armadio</legend>
                <div class="mb-3">
                    <label class="form-label" for="fCodArmadio">Codice armadio <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodArmadio" maxlength="20" aria-required="true">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fDescArmadio">Descrizione</label>
                    <input type="text" class="form-control" id="fDescArmadio" maxlength="100">
                </div>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" id="btnSalvaArmadio">Salva</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal Posizione -->
<div class="modal fade" id="modalPosizione" tabindex="-1" aria-labelledby="titlePosizione" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="titlePosizione">Posizione</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formPosizione" novalidate>
            <fieldset>
                <legend class="visually-hidden">Dati scaffale</legend>
                <div class="mb-3">
                    <label class="form-label" for="fCodScaffale">Codice scaffale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodScaffale" maxlength="20" aria-required="true">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fDescPosizione">Descrizione</label>
                    <input type="text" class="form-control" id="fDescPosizione" maxlength="100">
                </div>
                <input type="hidden" id="fPosArmadio">
                <input type="hidden" id="fPosEditMode">
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" id="btnSalvaPosizione">Salva</button>
        </div>
        </div>
    </div>
</div>

<script src="/js/armadi.js?v=<?= filemtime(__DIR__ . '/js/armadi.js') ?>"></script>
<?php include '_footer.php'; ?>