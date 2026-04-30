<?php include '_header.php'; ?>

<h2 class="section-title mb-4"><i class="bi bi-graph-up me-2"></i>Report</h2>

<div class="row g-3 mb-4">
    <!-- Sotto soglia -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Prodotti sotto soglia di riordino</span>
                <button id="btnCsvSoglia" class="btn btn-sm btn-outline-secondary" disabled onclick="csvSoglia()" aria-label="Scarica CSV sotto soglia"><i class="bi bi-download me-1"></i>CSV</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th scope="col">Cod. Prodotto</th><th scope="col">Soglia</th><th scope="col">Giacenza</th><th scope="col">Categoria</th></tr></thead>
                        <tbody id="tbodySoglia">
                            <tr><td colspan="4" class="text-center text-muted py-3">Caricamento...</td></tr>
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
        <button id="btnCsvGiacenze" class="btn btn-sm btn-outline-secondary" disabled onclick="csvGiacenze()" aria-label="Scarica CSV giacenze"><i class="bi bi-download me-1"></i>CSV</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th scope="col">Cod. Prodotto</th><th scope="col">Armadio</th><th scope="col">Scaffale</th><th scope="col">Quantità</th></tr></thead>
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
        <button id="btnCsvCompleto" class="btn btn-sm btn-outline-secondary" disabled onclick="csvCompleto()" aria-label="Scarica CSV report completo"><i class="bi bi-download me-1"></i>CSV</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr><th scope="col">Cod. Prodotto</th><th scope="col">Giacenza Totale</th><th scope="col">Soglia</th><th scope="col">Sotto Soglia</th><th scope="col">Categoria</th><th scope="col">Cod. Reg.</th><th scope="col">Cod. OE</th></tr>
                </thead>
                <tbody id="tbodyCompleto">
                    <tr><td colspan="7" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script>
let dataSoglia = [], dataGiacenze = [], dataCompleto = [];

async function loadReport() {
    const [sotto, pan, giacenze, completo] = await Promise.allSettled([
        apiCall('/report/sotto-soglia'),
        apiCall('/report/panoramica'),
        apiCall('/report/giacenze'),
        apiCall('/report/completo'),
    ]);

    // — Sotto soglia —
    const ts = document.getElementById('tbodySoglia');
    if (sotto.status === 'fulfilled' && Array.isArray(sotto.value)) {
        dataSoglia = sotto.value;
        document.getElementById('btnCsvSoglia').disabled = false;
        ts.innerHTML = sotto.value.length === 0
            ? '<tr><td colspan="4" class="text-center text-success py-2"><i class="bi bi-check-circle"></i> Nessun prodotto sotto soglia</td></tr>'
            : sotto.value.map(p => `
                <tr>
                    <td><strong>${escHtml(p.codProd)}</strong></td>
                    <td>${escHtml(String(p.qtaRiordino))}</td>
                    <td><span class="badge text-bg-danger">${escHtml(String(p.giacenzaTotale))}</span></td>
                    <td>${escHtml(p.codCat ?? '—')}</td>
                </tr>`).join('');
    } else ts.innerHTML = '<tr><td colspan="4" class="text-danger text-center py-2">Errore caricamento</td></tr>';

    // — Panoramica —
    const panEl = document.getElementById('panBody');
    if (pan.status === 'fulfilled' && pan.value) {
        const p = pan.value;
        panEl.innerHTML = `
            <div class="row text-center w-100">
                <div class="col-6 col-md-3 mb-3">
                    <div class="fs-2 fw-bold text-primary">${escHtml(String(p.totaleReferenze ?? '—'))}</div>
                    <div class="text-muted small">Referenze</div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="fs-2 fw-bold text-success">${Array.isArray(p.categorie) ? p.categorie.length : '—'}</div>
                    <div class="text-muted small">Categorie</div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="fs-2 fw-bold text-warning">${Array.isArray(p.prodotti) ? p.prodotti.length : '—'}</div>
                    <div class="text-muted small">Prodotti</div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="fs-2 fw-bold text-danger">${escHtml(String(p.prodottiSottoSoglia ?? '—'))}</div>
                    <div class="text-muted small">Sotto soglia</div>
                </div>
            </div>`;
    } else panEl.innerHTML = '<p class="text-danger">Errore caricamento panoramica</p>';

    // — Giacenze —
    const tg = document.getElementById('tbodyGiacenze');
    if (giacenze.status === 'fulfilled' && Array.isArray(giacenze.value)) {
        dataGiacenze = giacenze.value;
        document.getElementById('btnCsvGiacenze').disabled = false;
        tg.innerHTML = giacenze.value.length === 0
            ? '<tr><td colspan="4" class="text-center text-muted py-2">Nessuna giacenza</td></tr>'
            : giacenze.value.map(g => `
                <tr>
                    <td><strong>${escHtml(g.codProd)}</strong></td>
                    <td>${escHtml(g.codArmadio)}</td>
                    <td>${escHtml(g.codScaffale)}</td>
                    <td>${escHtml(String(g.qta))}</td>
                </tr>`).join('');
    } else tg.innerHTML = '<tr><td colspan="4" class="text-danger text-center py-2">Errore caricamento</td></tr>';

    // — Report completo —
    const tc = document.getElementById('tbodyCompleto');
    if (completo.status === 'fulfilled' && Array.isArray(completo.value)) {
        dataCompleto = completo.value;
        document.getElementById('btnCsvCompleto').disabled = false;
        tc.innerHTML = completo.value.length === 0
            ? '<tr><td colspan="7" class="text-center text-muted py-2">Nessun dato</td></tr>'
            : completo.value.map(p => `
                <tr>
                    <td><strong>${escHtml(p.codProd)}</strong></td>
                    <td>${escHtml(String(p.giacenzaTotale ?? '—'))}</td>
                    <td>${escHtml(String(p.qtaRiordino))}</td>
                    <td>${p.sottoSoglia ? '<span class="badge text-bg-danger">Sì</span>' : '<span class="badge text-bg-success">No</span>'}</td>
                    <td>${escHtml(p.codCat ?? '—')}</td>
                    <td>${escHtml(p.codReg ?? '—')}</td>
                    <td>${escHtml(p.codOE ?? '—')}</td>
                </tr>`).join('');
    } else tc.innerHTML = '<tr><td colspan="7" class="text-danger text-center py-2">Errore caricamento</td></tr>';
}

function csvSoglia() {
    downloadCsv(
        dataSoglia.map(p => [p.codProd, p.qtaRiordino, p.giacenzaTotale ?? '', p.codCat ?? '']),
        ['Codice Prodotto','Soglia Riordino','Giacenza Totale','Categoria'],
        'sotto_soglia.csv'
    );
}
function csvGiacenze() {
    downloadCsv(
        dataGiacenze.map(g => [g.codProd, g.codArmadio, g.codScaffale, g.qta]),
        ['Codice Prodotto','Armadio','Scaffale','Quantità'],
        'giacenze.csv'
    );
}
function csvCompleto() {
    downloadCsv(
        dataCompleto.map(p => [p.codProd, p.giacenzaTotale ?? '', p.qtaRiordino, p.sottoSoglia ? 'Sì' : 'No', p.codCat ?? '', p.codReg ?? '', p.codOE ?? '']),
        ['Codice Prodotto','Giacenza Totale','Soglia','Sotto Soglia','Categoria','Cod. Reg.','Cod. OE'],
        'report_completo.csv'
    );
}

loadReport();
</script>
