<?php include '_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-title mb-0"><i class="bi bi-box me-2"></i>Prodotti</h2>
    <button class="btn btn-primary btn-sm" onclick="openCreate()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo prodotto
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Codice</th><th scope="col">Giacenza</th><th scope="col">Soglia Riordino</th>
                <th scope="col">Posizione</th>
                <th scope="col">Categoria</th><th scope="col">Codifica Regionale</th><th scope="col">Codifica OE</th>
                <th scope="col" class="text-end">Azioni</th>
            </tr>
        </thead>
        <tbody id="tbodyProdotti">
            <tr><td colspan="7" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal crea/modifica prodotto -->
<div class="modal fade" id="modalProdotto" tabindex="-1" aria-labelledby="titleProdotto" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleProdotto">Prodotto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="fCodProd">
                <div class="mb-3">
                    <label class="form-label">Soglia riordino</label>
                    <input type="number" class="form-control" id="fQtaRiordino" min="0" value="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoria</label>
                    <select class="form-select" id="fCodCat"><option value="">— nessuna —</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Codifica regionale</label>
                    <select class="form-select" id="fCodReg"><option value="">— nessuna —</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Codifica OE</label>
                    <select class="form-select" id="fCodOE"><option value="">— nessuna —</option></select>
                </div>
                <hr>
                <h6 class="mb-2">Attributi <span class="text-muted small fw-normal">(opzionale)</span></h6>
                <div id="prodAttrContainer"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addProdAttrRow()">
                    <i class="bi bi-plus"></i> Aggiungi attributo
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitProdotto()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal carico -->
<div class="modal fade" id="modalCarico" tabindex="-1" aria-labelledby="titleCarico" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title" id="titleCarico"><i class="bi bi-arrow-down-circle me-2 text-success"></i>Carico merce — <span id="titoloCarico"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Armadio <span class="text-danger">*</span></label>
                        <select class="form-select" id="fCaricoArmadio"><option value="">— seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Scaffale <span class="text-danger">*</span></label>
                        <select class="form-select" id="fCaricoScaffale" disabled><option value="">— prima seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantità <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="fCaricoQta" min="1" value="1">
                    </div>
                </div>
                <hr>
                <h6 class="mb-2">Attributi <span class="text-muted small fw-normal">(opzionale)</span></h6>
                <div id="attrContainer"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addAttrRow()">
                    <i class="bi bi-plus"></i> Aggiungi attributo
                </button>
                <input type="hidden" id="fCaricoCodProd">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-success" onclick="submitCarico()">
                    <i class="bi bi-arrow-down-circle me-1"></i>Carica
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
                <h5 class="modal-title" id="titleScarico"><i class="bi bi-arrow-up-circle me-2 text-warning"></i>Scarico merce — <span id="titoloScarico"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Armadio <span class="text-danger">*</span></label>
                        <select class="form-select" id="fScaricoCodArmadio"><option value="">— seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Scaffale <span class="text-danger">*</span></label>
                        <select class="form-select" id="fScaricoCodScaffale" disabled><option value="">— prima seleziona armadio —</option></select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantità <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="fScaricoQta" min="1" value="1">
                    </div>
                </div>
                <input type="hidden" id="fScaricoCodProd">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-warning" onclick="submitScarico()">
                    <i class="bi bi-arrow-up-circle me-1"></i>Scarica
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
                <h5 class="modal-title" id="titleAttrProd"><i class="bi bi-list-check me-2"></i>Attributi — <span id="titoloAttrProd"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="listaAttrProd" class="mb-3">Caricamento...</div>
                <hr>
                <h6 class="mb-2">Assegna attributo</h6>
                <div class="row g-2">
                    <div class="col-5">
                        <select class="form-select form-select-sm" id="fAssignCodAttr"><option value="">— seleziona —</option></select>
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control form-control-sm" id="fAssignValore" placeholder="Valore (opz.)">
                    </div>
                    <div class="col-2">
                        <button class="btn btn-sm btn-primary w-100" onclick="assignAttr()"><i class="bi bi-plus"></i></button>
                    </div>
                </div>
                <input type="hidden" id="fAttrProdCod">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script src="/js/prodotti.js"></script>