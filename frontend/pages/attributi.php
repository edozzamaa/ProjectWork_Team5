<?php include '_header.php'; ?>

<section aria-labelledby="titoloAttributi">
<header class="d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="section-title mb-1" id="titoloAttributi"><i class="bi bi-list-check me-2" aria-hidden="true"></i>Attributi</h1>
        <p class="text-muted small mb-0">Definisci gli attributi da assegnare ai prodotti.</p>
    </div>
    <button class="btn btn-primary btn-sm" id="btnNuovoAttributo">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuovo attributo
    </button>
</header>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <caption class="visually-hidden">Elenco attributi</caption>
        <thead>
            <tr><th scope="col" id="thAttrCod">Codice</th><th scope="col" id="thAttrNome">Nome</th><th scope="col" class="text-end" id="thAttrAzioni">Azioni</th></tr>
        </thead>
        <tbody id="tbodyAttributi" aria-live="polite">
            <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">Attributo</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formAttributo" novalidate>
            <fieldset>
                <legend class="visually-hidden">Dati attributo</legend>
                <div class="mb-3">
                    <label class="form-label" for="fCodAttr">Codice attributo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodAttr" maxlength="20" aria-required="true">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fNome">Nome <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fNome" maxlength="100" aria-required="true">
                </div>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" id="btnSalvaAttributo">Salva</button>
        </div>
        </div>
    </div>
</div>

<script src="/js/attributi.js"></script>
<?php include '_footer.php'; ?>