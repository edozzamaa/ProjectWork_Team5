<?php include '_header.php'; ?>

<section aria-labelledby="titoloProdotti">
<header class="d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="section-title mb-1" id="titoloProdotti"><i class="bi bi-box me-2" aria-hidden="true"></i>Prodotti</h1>
        <p class="text-muted small mb-0">Visualizza e gestisci i prodotti in magazzino.</p>
    </div>
    <button class="btn btn-primary btn-sm" id="btnNuovoProdotto">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuovo prodotto
    </button>
</header>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <caption class="visually-hidden">Elenco prodotti</caption>
        <thead>
            <tr>
                <th scope="col" id="thProdToggle"></th>
                <th scope="col" id="thProdCod">Codice</th><th scope="col" id="thProdGiac">Giacenza</th><th scope="col" id="thProdSoglia">Soglia Riordino</th>
                <th scope="col" id="thProdPos">Posizione</th>
                <th scope="col" id="thProdCat">Categoria</th><th scope="col" id="thProdReg">Codifica Regionale</th><th scope="col" id="thProdOE">Codifica OE</th>
                <th scope="col" class="text-end" id="thProdAzioni">Azioni</th>
            </tr>
        </thead>
        <tbody id="tbodyProdotti" aria-live="polite">
            <tr><td colspan="7" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>
</section>

<!-- Modal crea/modifica prodotto -->
<div class="modal fade" id="modalProdotto" tabindex="-1" aria-labelledby="titleProdotto" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="titleProdotto">Prodotto</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formProdotto" novalidate>
            <input type="hidden" id="fCodProd">
            <fieldset>
                <legend class="visually-hidden">Dati prodotto</legend>
                <div class="mb-3">
                    <label class="form-label" for="fQtaRiordino">Soglia riordino</label>
                    <input type="number" class="form-control" id="fQtaRiordino" min="0" value="0" inputmode="numeric">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fCodCat">Categoria <span class="text-danger" id="fCodCatReq">*</span></label>
                    <select class="form-select" id="fCodCat" aria-required="true"><option value="">— nessuna —</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fCodReg">Codifica regionale</label>
                    <select class="form-select" id="fCodReg"><option value="">— nessuna —</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fCodOE">Codifica OE</label>
                    <select class="form-select" id="fCodOE"><option value="">— nessuna —</option></select>
                </div>
            </fieldset>
            <hr>
            <fieldset>
                <legend class="fs-6 fw-semibold mb-2">Attributi <span class="text-muted small fw-normal">(opzionale)</span></legend>
                <div id="prodAttrContainer"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btnAddProdAttr">
                    <i class="bi bi-plus" aria-hidden="true"></i> Aggiungi attributo
                </button>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" id="btnSalvaProdotto">Salva</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal carico -->
<div class="modal fade" id="modalCarico" tabindex="-1" aria-labelledby="titleCarico" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header bg-success-subtle">
            <h2 class="modal-title" id="titleCarico"><i class="bi bi-arrow-down-circle me-2 text-success" aria-hidden="true"></i>Carico merce — <span id="titoloCarico"></span></h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formCarico" novalidate>
            <fieldset>
                <legend class="visually-hidden">Posizione di carico</legend>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label" for="fCaricoArmadio">Armadio <span class="text-danger">*</span></label>
                        <select class="form-select" id="fCaricoArmadio" aria-required="true"><option value="">— seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label" for="fCaricoScaffale">Scaffale <span class="text-danger">*</span></label>
                        <select class="form-select" id="fCaricoScaffale" disabled aria-required="true"><option value="">— prima seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="fCaricoQta">Quantità <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="fCaricoQta" min="1" value="1" inputmode="numeric" aria-required="true">
                    </div>
                </div>
            </fieldset>
            <hr>
            <fieldset>
                <legend class="fs-6 fw-semibold mb-2">Attributi <span class="text-muted small fw-normal">(opzionale)</span></legend>
                <div id="attrContainer"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btnAddAttrCarico">
                    <i class="bi bi-plus" aria-hidden="true"></i> Aggiungi attributo
                </button>
            </fieldset>
            <input type="hidden" id="fCaricoCodProd">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-success" id="btnSalvaCarico">
                <i class="bi bi-arrow-down-circle me-1" aria-hidden="true"></i>Carica
            </button>
        </div>
        </div>
    </div>
</div>

<!-- Modal scarico -->
<div class="modal fade" id="modalScarico" tabindex="-1" aria-labelledby="titleScarico" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-warning-subtle">
            <h2 class="modal-title" id="titleScarico"><i class="bi bi-arrow-up-circle me-2 text-warning" aria-hidden="true"></i>Scarico merce — <span id="titoloScarico"></span></h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <form id="formScarico" novalidate>
            <fieldset>
                <legend class="visually-hidden">Posizione di scarico</legend>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label" for="fScaricoCodArmadio">Armadio <span class="text-danger">*</span></label>
                        <select class="form-select" id="fScaricoCodArmadio" aria-required="true"><option value="">— seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label" for="fScaricoCodScaffale">Scaffale <span class="text-danger">*</span></label>
                        <select class="form-select" id="fScaricoCodScaffale" disabled aria-required="true"><option value="">— prima seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="fScaricoQta">Quantità <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="fScaricoQta" min="1" value="1" inputmode="numeric" aria-required="true">
                    </div>
                </div>
            </fieldset>
            <input type="hidden" id="fScaricoCodProd">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-warning" id="btnSalvaScarico">
                <i class="bi bi-arrow-up-circle me-1" aria-hidden="true"></i>Scarica
            </button>
        </div>
        </div>
    </div>
</div>

<!-- Modal attributi prodotto -->
<div class="modal fade" id="modalAttrProd" tabindex="-1" aria-labelledby="titleAttrProd" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="titleAttrProd"><i class="bi bi-list-check me-2" aria-hidden="true"></i>Attributi — <span id="titoloAttrProd"></span></h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <div id="listaAttrProd" class="mb-3" aria-live="polite">Caricamento...</div>
            <hr>
            <fieldset>
                <legend class="fs-6 fw-semibold mb-2">Assegna attributo</legend>
                <div class="row g-2">
                    <div class="col-5">
                        <label class="visually-hidden" for="fAssignCodAttr">Attributo</label>
                        <select class="form-select form-select-sm" id="fAssignCodAttr"><option value="">— seleziona —</option></select>
                    </div>
                    <div class="col-5">
                        <label class="visually-hidden" for="fAssignValore">Valore</label>
                        <input type="text" class="form-control form-control-sm" id="fAssignValore" placeholder="Valore (opz.)">
                    </div>
                    <div class="col-2">
                        <button class="btn btn-sm btn-primary w-100" id="btnAssignAttr" aria-label="Aggiungi attributo"><i class="bi bi-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
            </fieldset>
            <input type="hidden" id="fAttrProdCod">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
        </div>
        </div>
    </div>
</div>

<script src="/js/prodotti.js"></script>
<?php include '_footer.php'; ?>