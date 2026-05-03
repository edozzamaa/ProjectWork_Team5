<?php include '_header.php'; ?>

<h2 class="section-title mb-4"><i class="bi bi-graph-up me-2"></i>Report</h2>

<div class="row g-3 mb-4">
    <!-- Sotto soglia -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Prodotti sotto soglia di riordino</span>
                <div class="d-flex gap-2">
                    <button id="btnCsvSoglia" class="btn btn-sm btn-outline-secondary" disabled onclick="csvSoglia()" aria-label="Scarica CSV sotto soglia"><i class="bi bi-download me-1"></i>CSV</button>
                    <button id="btnExcelSoglia" class="btn btn-sm btn-outline-success" onclick="exportExcelSoglia()" aria-label="Scarica Excel sotto soglia"><i class="bi bi-file-earmark-excel me-1"></i>Excel</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th scope="col">Codice</th><th scope="col">Descrizione</th><th scope="col">Soglia</th><th scope="col">Giacenza</th><th scope="col">Categoria</th></tr></thead>
                        <tbody id="tbodySoglia">
                            <tr><td colspan="5" class="text-center text-muted py-3">Caricamento...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Panoramica -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">
                <i class="bi bi-bar-chart-fill text-primary me-2"></i>Panoramica magazzino
            </div>
            <div class="card-body d-flex align-items-center" id="panBody">
                <p class="text-muted mb-0">Caricamento...</p>
            </div>
        </div>
    </div>
</div>

<!-- Giacenze dettagliate -->
<div class="card shadow-sm mb-4">
    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table me-2"></i>Giacenze dettagliate per posizione</span>
        <button id="btnExcelGiacenze" class="btn btn-sm btn-outline-success" onclick="exportExcelGiacenze()" aria-label="Scarica Excel giacenze"><i class="bi bi-file-earmark-excel me-1"></i>Excel</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th scope="col">Armadio</th><th scope="col">Scaffale</th><th scope="col">Quantità</th></tr></thead>
                <tbody id="tbodyGiacenze">
                    <tr><td colspan="4" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Report completo -->
<div class="card shadow-sm mb-4">
    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text me-2"></i>Report completo prodotti</span>
                <div class="d-flex gap-2">
                    <button id="btnCsvCompleto" class="btn btn-sm btn-outline-secondary" disabled onclick="csvCompleto()" aria-label="Scarica CSV report completo"><i class="bi bi-download me-1"></i>CSV</button>
                    <button id="btnExcelCompleto" class="btn btn-sm btn-outline-success" onclick="exportExcel()" aria-label="Scarica Excel report completo"><i class="bi bi-file-earmark-excel me-1"></i>Excel</button>
                </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr><th scope="col">Codice</th><th scope="col">Descrizione</th><th scope="col">Giacenza</th><th scope="col">Soglia</th><th scope="col">Sotto Soglia</th><th scope="col">Cat.</th></tr>
                </thead>
                <tbody id="tbodyCompleto">
                    <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>
<script src="/js/report.js?v=<?= filemtime(__DIR__ . '/js/report.js') ?>"></script>