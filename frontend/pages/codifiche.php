<?php include '_header.php'; ?>

<section aria-labelledby="titoloCodifiche">
<h1 class="section-title mb-4" id="titoloCodifiche"><i class="bi bi-upc-scan me-2" aria-hidden="true"></i>Codifiche</h1>

<ul class="nav nav-tabs mb-4" role="tablist" aria-label="Tipo codifiche">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="tabRegLink" data-bs-toggle="tab" href="#tabReg" role="tab" aria-controls="tabReg" aria-selected="true">Codifiche Regionali</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="tabOELink" data-bs-toggle="tab" href="#tabOE" role="tab" aria-controls="tabOE" aria-selected="false">Codifiche OE</a>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB REG -->
    <section class="tab-pane fade show active" id="tabReg" role="tabpanel" aria-labelledby="tabRegLink">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary btn-sm" id="btnNuovaReg"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuova codifica reg.</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th scope="col" id="thRegCod">Codice</th><th scope="col" id="thRegDesc">Descrizione</th><th scope="col" class="text-end" id="thRegAzioni">Azioni</th></tr></thead>
                <tbody id="tbodyReg" aria-live="polite">
                    <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- TAB OE -->
    <section class="tab-pane fade" id="tabOE" role="tabpanel" aria-labelledby="tabOELink">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary btn-sm" id="btnNuovaOE"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuova codifica OE</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th scope="col" id="thOECod">Codice</th><th scope="col" id="thOEDesc">Descrizione</th><th scope="col" id="thOEFor">Fornitore</th><th scope="col" class="text-end" id="thOEAzioni">Azioni</th></tr></thead>
                <tbody id="tbodyOE" aria-live="polite">
                    <tr><td colspan="4" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
</section>

<!-- Modal Reg -->
<div class="modal fade" id="modalReg" tabindex="-1" aria-labelledby="titleReg" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="titleReg">Codifica Regionale</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formReg" novalidate>
            <fieldset>
                <legend class="visually-hidden">Dati codifica regionale</legend>
                <div class="mb-3">
                    <label class="form-label" for="fCodReg">Codice <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodReg" maxlength="20" aria-required="true">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fDescReg">Descrizione <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fDescReg" maxlength="100" aria-required="true">
                </div>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" id="btnSalvaReg">Salva</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal OE -->
<div class="modal fade" id="modalOE" tabindex="-1" aria-labelledby="titleOE" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="titleOE">Codifica OE</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formOE" novalidate>
            <fieldset>
                <legend class="visually-hidden">Dati codifica OE</legend>
                <div class="mb-3">
                    <label class="form-label" for="fCodOE">Codice <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodOE" maxlength="20" aria-required="true">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fDescOE">Descrizione <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fDescOE" maxlength="100" aria-required="true">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fRagSocOE">Fornitore (ragione sociale)</label>
                    <input type="text" class="form-control" id="fRagSocOE" maxlength="100" placeholder="opzionale">
                </div>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" id="btnSalvaOE">Salva</button>
        </div>
        </div>
    </div>
</div>

<script src="/js/codifiche.js"></script>
<?php include '_footer.php'; ?>