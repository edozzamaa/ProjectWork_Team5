<?php include '_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-title mb-0"><i class="bi bi-truck me-2"></i>Fornitori</h2>
    <button class="btn btn-primary btn-sm" onclick="openCreate()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo fornitore
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr><th scope="col">Ragione Sociale</th><th scope="col">P.IVA</th><th scope="col">Telefono</th><th scope="col">Email</th><th scope="col">Indirizzo</th><th scope="col" class="text-end">Azioni</th></tr>
        </thead>
        <tbody id="tbodyFornitori">
            <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Fornitore</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Ragione sociale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fRagSoc" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Partita IVA</label>
                    <input type="text" class="form-control" id="fPartIVA" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefono</label>
                    <input type="tel" class="form-control" id="fTelefono" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="fEmail" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Indirizzo</label>
                    <input type="text" class="form-control" id="fIndirizzo" maxlength="200">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script src="/js/fornitori.js"></script>