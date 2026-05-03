<?php include '_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-title mb-0"><i class="bi bi-archive me-2"></i>Armadi e Posizioni</h2>
    <button class="btn btn-primary btn-sm" onclick="openCreateArmadio()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo armadio
    </button>
</div>

<div id="armadioList">
    <p class="text-muted">Caricamento...</p>
</div>

<!-- Modal Armadio -->
<div class="modal fade" id="modalArmadio" tabindex="-1" aria-labelledby="titleArmadio" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleArmadio">Armadio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice armadio <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodArmadio" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione</label>
                    <input type="text" class="form-control" id="fDescArmadio" maxlength="100">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitArmadio()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Posizione -->
<div class="modal fade" id="modalPosizione" tabindex="-1" aria-labelledby="titlePosizione" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titlePosizione">Posizione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice scaffale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodScaffale" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione</label>
                    <input type="text" class="form-control" id="fDescPosizione" maxlength="100">
                </div>
                <input type="hidden" id="fPosArmadio">
                <input type="hidden" id="fPosEditMode">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitPosizione()">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script src="/js/armadi.js?v=<?= filemtime(__DIR__ . '/js/armadi.js') ?>"></script>