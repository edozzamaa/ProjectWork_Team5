<?php include '_header.php'; ?>

<section aria-labelledby="titoloReport">
<h1 class="section-title mb-4" id="titoloReport"><i class="bi bi-graph-up me-2" aria-hidden="true"></i>Report</h1>

<div class="row g-3 mb-4">
    <!-- Sotto soglia -->
    <div class="col-lg-6">
        <article class="card shadow-sm h-100" aria-labelledby="titoloSoglia">
            <header class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span id="titoloSoglia"><i class="bi bi-exclamation-triangle-fill text-danger me-2" aria-hidden="true"></i>Prodotti sotto soglia di riordino</span>
                <div class="d-flex gap-2">
                    <button id="btnCsvSoglia" class="btn btn-sm btn-outline-secondary" disabled aria-label="Scarica CSV sotto soglia"><i class="bi bi-download me-1" aria-hidden="true"></i>CSV</button>
                    <button id="btnExcelSoglia" class="btn btn-sm btn-outline-success" aria-label="Scarica Excel sotto soglia"><i class="bi bi-file-earmark-excel me-1" aria-hidden="true"></i>Excel</button>
                </div>
            </header>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <caption class="visually-hidden">Prodotti sotto soglia di riordino</caption>
                        <thead><tr><th scope="col" id="thRptSogCod">Codice</th><th scope="col" id="thRptSogDesc">Descrizione</th><th scope="col" id="thRptSogSoglia">Soglia</th><th scope="col" id="thRptSogGiac">Giacenza</th><th scope="col" id="thRptSogCat">Categoria</th></tr></thead>
                        <tbody id="tbodySoglia" aria-live="polite">
                            <tr><td colspan="5" class="text-center text-muted py-3">Caricamento...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </div>

    <!-- Panoramica -->
    <div class="col-lg-6">
        <article class="card shadow-sm h-100" aria-label="Panoramica magazzino">
            <header class="card-header fw-semibold">
                <i class="bi bi-bar-chart-fill text-primary me-2" aria-hidden="true"></i>Panoramica magazzino
            </header>
            <div class="card-body d-flex align-items-center" id="panBody">
                <p class="text-muted mb-0">Caricamento...</p>
            </div>
        </article>
    </div>
</div>

<!-- Giacenze dettagliate -->
<article class="card shadow-sm mb-4" aria-labelledby="titoloGiacenze">
    <header class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span id="titoloGiacenze"><i class="bi bi-table me-2" aria-hidden="true"></i>Giacenze dettagliate per posizione</span>
        <button id="btnExcelGiacenze" class="btn btn-sm btn-outline-success" aria-label="Scarica Excel giacenze"><i class="bi bi-file-earmark-excel me-1" aria-hidden="true"></i>Excel</button>
    </header>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <caption class="visually-hidden">Giacenze dettagliate per posizione</caption>
                <thead><tr><th scope="col" id="thRptGiacArm">Armadio</th><th scope="col" id="thRptGiacSca">Scaffale</th><th scope="col" id="thRptGiacQta">Quantità</th></tr></thead>
                <tbody id="tbodyGiacenze" aria-live="polite">
                    <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</article>

<!-- Report completo -->
<article class="card shadow-sm mb-4" aria-labelledby="titoloCompleto">
    <header class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span id="titoloCompleto"><i class="bi bi-file-earmark-text me-2" aria-hidden="true"></i>Report completo prodotti</span>
                <div class="d-flex gap-2">
                    <button id="btnCsvCompleto" class="btn btn-sm btn-outline-secondary" disabled aria-label="Scarica CSV report completo"><i class="bi bi-download me-1" aria-hidden="true"></i>CSV</button>
                    <button id="btnExcelCompleto" class="btn btn-sm btn-outline-success" aria-label="Scarica Excel report completo"><i class="bi bi-file-earmark-excel me-1" aria-hidden="true"></i>Excel</button>
                </div>
    </header>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <caption class="visually-hidden">Report completo prodotti</caption>
                <thead>
                    <tr><th scope="col" id="thRptCmpCod">Codice</th><th scope="col" id="thRptCmpDesc">Descrizione</th><th scope="col" id="thRptCmpGiac">Giacenza</th><th scope="col" id="thRptCmpSoglia">Soglia</th><th scope="col" id="thRptCmpSotto">Sotto Soglia</th><th scope="col" id="thRptCmpCat">Cat.</th></tr>
                </thead>
                <tbody id="tbodyCompleto" aria-live="polite">
                    <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</article>
</section>

<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>
<script src="/js/report.js?v=<?= filemtime(__DIR__ . '/js/report.js') ?>"></script>
<?php include '_footer.php'; ?>