<?php include '_header.php'; ?>

<section aria-labelledby="titoloFornitori">
<header class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="section-title mb-0" id="titoloFornitori"><i class="bi bi-truck me-2"></i>Fornitori</h1>
    <button class="btn btn-primary btn-sm" onclick="openCreate()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo fornitore
    </button>
</header>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <caption class="visually-hidden">Elenco fornitori</caption>
        <thead>
            <tr><th scope="col">Ragione Sociale</th><th scope="col">P.IVA</th><th scope="col">Telefono</th><th scope="col">Email</th><th scope="col">Indirizzo</th><th scope="col" class="text-end">Azioni</th></tr>
        </thead>
        <tbody id="tbodyFornitori">
            <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Fornitore</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <form id="formFornitore" onsubmit="submitForm(); return false;" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="fRagSoc">Ragione sociale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fRagSoc" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fPartIVA">Partita IVA</label>
                    <input type="text" class="form-control" id="fPartIVA" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fTelefono">Telefono</label>
                    <input type="tel" class="form-control" id="fTelefono" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fEmail">Email</label>
                    <input type="email" class="form-control" id="fEmail" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fIndirizzo">Indirizzo</label>
                    <input type="text" class="form-control" id="fIndirizzo" maxlength="200">
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

<script src="/js/fornitori.js"></script>
<?php include '_footer.php'; ?>