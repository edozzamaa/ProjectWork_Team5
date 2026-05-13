<?php include '_header.php'; ?>

<section aria-labelledby="titoloFornitori">
<header class="d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="section-title mb-1" id="titoloFornitori"><i class="bi bi-truck me-2" aria-hidden="true"></i>Fornitori</h1>
        <p class="text-muted small mb-0">Gestisci i fornitori associati ai prodotti del magazzino.</p>
    </div>
    <button class="btn btn-primary btn-sm" id="btnNuovoFornitore">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuovo fornitore
    </button>
</header>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <caption class="visually-hidden">Elenco fornitori</caption>
        <thead>
            <tr><th scope="col" id="thForRagSoc">Ragione Sociale</th><th scope="col" id="thForPIVA">P.IVA</th><th scope="col" id="thForTel">Telefono</th><th scope="col" id="thForEmail">Email</th><th scope="col" id="thForInd">Indirizzo</th><th scope="col" class="text-end" id="thForAzioni">Azioni</th></tr>
        </thead>
        <tbody id="tbodyFornitori" aria-live="polite">
            <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">Fornitore</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formFornitore" novalidate>
            <fieldset>
                <legend class="visually-hidden">Dati fornitore</legend>
                <div class="mb-3">
                    <label class="form-label" for="fRagSoc">Ragione sociale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fRagSoc" maxlength="100" aria-required="true">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fPartIVA">Partita IVA</label>
                    <input type="text" class="form-control" id="fPartIVA" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fTelefono">Telefono</label>
                    <input type="tel" class="form-control" id="fTelefono" maxlength="20" autocomplete="tel">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fEmail">Email</label>
                    <input type="email" class="form-control" id="fEmail" maxlength="100" autocomplete="email">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fIndirizzo">Indirizzo</label>
                    <input type="text" class="form-control" id="fIndirizzo" maxlength="200">
                </div>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" id="btnSalvaFornitore">Salva</button>
        </div>
        </div>
    </div>
</div>

<script src="/js/fornitori.js"></script>
<?php include '_footer.php'; ?>