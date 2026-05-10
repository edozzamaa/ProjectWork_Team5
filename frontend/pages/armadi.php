<?php include '_header.php'; ?>

<section aria-labelledby="titoloArmadi">
<header class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="section-title mb-0" id="titoloArmadi"><i class="bi bi-archive me-2"></i>Armadi e Posizioni</h1>
    <button class="btn btn-primary btn-sm" onclick="openCreateArmadio()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo armadio
    </button>
</header>

<div id="armadioList">
    <p class="text-muted">Caricamento...</p>
</div>
</section>

<!-- Modal Armadio -->
<div class="modal fade" id="modalArmadio" tabindex="-1" aria-labelledby="titleArmadio" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="titleArmadio">Armadio</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <form id="formArmadio" onsubmit="submitArmadio(); return false;" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="fCodArmadio">Codice armadio <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodArmadio" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fDescArmadio">Descrizione</label>
                    <input type="text" class="form-control" id="fDescArmadio" maxlength="100">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitArmadio()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Posizione -->
<div class="modal fade" id="modalPosizione" tabindex="-1" aria-labelledby="titlePosizione" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="titlePosizione">Posizione</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <form id="formPosizione" onsubmit="submitPosizione(); return false;" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="fCodScaffale">Codice scaffale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodScaffale" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fDescPosizione">Descrizione</label>
                    <input type="text" class="form-control" id="fDescPosizione" maxlength="100">
                </div>
                <input type="hidden" id="fPosArmadio">
                <input type="hidden" id="fPosEditMode">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitPosizione()">Salva</button>
            </div>
        </div>
    </div>
</div>

<script src="/js/armadi.js?v=<?= filemtime(__DIR__ . '/js/armadi.js') ?>"></script>
<?php include '_footer.php'; ?>