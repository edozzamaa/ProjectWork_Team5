<?php include '_header.php'; ?>

<h2 class="section-title mb-4"><i class="bi bi-upc-scan me-2"></i>Codifiche</h2>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tabReg">Codifiche Regionali</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tabOE">Codifiche OE</a>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB REG -->
    <div class="tab-pane fade show active" id="tabReg">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary btn-sm" onclick="openCreateReg()"><i class="bi bi-plus-lg me-1"></i>Nuova codifica reg.</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Codice</th><th>Descrizione</th><th class="text-end">Azioni</th></tr></thead>
                <tbody id="tbodyReg">
                    <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB OE -->
    <div class="tab-pane fade" id="tabOE">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary btn-sm" onclick="openCreateOE()"><i class="bi bi-plus-lg me-1"></i>Nuova codifica OE</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Codice</th><th>Descrizione</th><th>Fornitore</th><th class="text-end">Azioni</th></tr></thead>
                <tbody id="tbodyOE">
                    <tr><td colspan="4" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Reg -->
<div class="modal fade" id="modalReg" tabindex="-1" aria-labelledby="titleReg" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleReg">Codifica Regionale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodReg" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fDescReg" maxlength="100">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitReg()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal OE -->
<div class="modal fade" id="modalOE" tabindex="-1" aria-labelledby="titleOE" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleOE">Codifica OE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodOE" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fDescOE" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fornitore (ragione sociale)</label>
                    <input type="text" class="form-control" id="fRagSocOE" maxlength="100" placeholder="opzionale">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitOE()">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script src="/js/codifiche.js"></script>