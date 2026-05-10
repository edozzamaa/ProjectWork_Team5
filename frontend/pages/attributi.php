<?php include '_header.php'; ?>

<section aria-labelledby="titoloAttributi">
<header class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="section-title mb-0" id="titoloAttributi"><i class="bi bi-list-check me-2"></i>Attributi</h1>
    <button class="btn btn-primary btn-sm" onclick="openCreate()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo attributo
    </button>
</header>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <caption class="visually-hidden">Elenco attributi</caption>
        <thead>
            <tr><th scope="col">Codice</th><th scope="col">Nome</th><th scope="col" class="text-end">Azioni</th></tr>
        </thead>
        <tbody id="tbodyAttributi">
            <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Attributo</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <form id="formAttributo" onsubmit="submitForm(); return false;" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="fCodAttr">Codice attributo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodAttr" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fNome">Nome <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fNome" maxlength="100">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Salva</button>
            </div>
        </div>
    </div>
</div>

<script src="/js/attributi.js"></script>
<?php include '_footer.php'; ?>