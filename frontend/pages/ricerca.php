<?php include '_header.php'; ?>

<h2 class="section-title mb-4"><i class="bi bi-search me-2"></i>Ricerca prodotti</h2>

<!-- Filtri salvati -->
<div class="card shadow-sm mb-4 d-none" id="cardFiltriSalvati">
    <div class="card-header fw-semibold d-flex align-items-center justify-content-between"
         role="button" data-bs-toggle="collapse" data-bs-target="#filtriSalvatiCollapse"
         aria-expanded="true" aria-controls="filtriSalvatiCollapse" style="cursor:pointer;">
        <span><i class="bi bi-bookmark-star me-2"></i>Filtri salvati</span>
        <i class="bi bi-chevron-up transition-icon" id="filtriSalvatiChevron"></i>
    </div>
    <div class="collapse show" id="filtriSalvatiCollapse">
        <div class="card-body py-2" id="filtriSalvatiContainer"></div>
    </div>
</div>

<!-- Pannello filtri -->
<div class="card shadow-sm mb-4">
    <div class="card-header fw-semibold">
        <i class="bi bi-funnel me-2"></i>Filtri
    </div>
    <div class="card-body">
        <!-- Filtri base -->
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small fw-semibold" for="fFiltCat">Categoria</label>
                <select class="form-select form-select-sm" id="fFiltCat">
                    <option value="">— tutte —</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small fw-semibold" for="fFiltReg">Codifica Regionale</label>
                <select class="form-select form-select-sm" id="fFiltReg">
                    <option value="">— tutte —</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small fw-semibold" for="fFiltOE">Codifica OE</label>
                <select class="form-select form-select-sm" id="fFiltOE">
                    <option value="">— tutte —</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3 d-flex align-items-end pb-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="fFiltSoglia">
                    <label class="form-check-label small fw-semibold" for="fFiltSoglia">Solo sotto soglia</label>
                </div>
            </div>
        </div>

        <!-- Filtri attributi dinamici -->
        <div class="mb-3">
            <label class="form-label small fw-semibold">Filtri per attributo</label>
            <div id="filtriAttrContainer"></div>
            <button class="btn btn-sm btn-outline-secondary mt-1" onclick="addAttrFiltroRow()">
                <i class="bi bi-plus-lg me-1"></i>Aggiungi filtro attributo
            </button>
        </div>

        <!-- Azioni -->
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalSalvaFiltro">
                <i class="bi bi-bookmark-plus me-1"></i>Salva filtro
            </button>
            <button class="btn btn-primary btn-sm" onclick="eseguiRicerca()">
                <i class="bi bi-search me-1"></i>Cerca
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="resetFiltri()">
                <i class="bi bi-x-circle me-1"></i>Reset
            </button>
        </div>
    </div>
</div>

<!-- Risultati -->
<div class="card shadow-sm">
    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span id="lblRisultati"><i class="bi bi-table me-2"></i>Risultati</span>
        <button id="btnExcelRicerca" class="btn btn-sm btn-outline-success d-none" onclick="exportExcelRicerca()">
            <i class="bi bi-file-earmark-excel me-1"></i>Excel
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Codice</th>
                        <th scope="col">Giacenza</th>
                        <th scope="col">Soglia</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Codifica Regionale</th>
                        <th scope="col">Codifica OE</th>
                        <th scope="col">Attributi</th>
                    </tr>
                </thead>
                <tbody id="tbodyRicerca">
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-search me-1"></i>Imposta i filtri e premi <span class="fw-bold">Cerca</span>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal salva filtro -->
<div class="modal fade" id="modalSalvaFiltro" tabindex="-1" aria-labelledby="modalSalvaFiltroLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSalvaFiltroLabel"><i class="bi bi-bookmark-plus me-2"></i>Salva filtro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <label class="form-label small fw-semibold" for="nomeFiltroInput">Nome del filtro</label>
                <input type="text" class="form-control form-control-sm" id="nomeFiltroInput"
                    placeholder="es. Sotto soglia cat. A" maxlength="60" autocomplete="off">
                <div class="invalid-feedback">Inserisci un nome.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="salvaFiltroCorrente()">
                    <i class="bi bi-bookmark-check me-1"></i>Salva
                </button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>
<script src="/js/ricerca.js?v=<?= filemtime(__DIR__ . '/js/ricerca.js') ?>"></script>
<script>
(function () {
    const collapseEl = document.getElementById('filtriSalvatiCollapse');
    const chevron    = document.getElementById('filtriSalvatiChevron');
    if (!collapseEl || !chevron) return;
    collapseEl.addEventListener('show.bs.collapse',  () => chevron.style.transform = 'rotate(0deg)');
    collapseEl.addEventListener('hide.bs.collapse',  () => chevron.style.transform = 'rotate(180deg)');
    chevron.style.transition = 'transform .25s ease';
})();
</script>
