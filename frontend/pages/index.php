<?php include '_header.php'; ?>

<h2 class="section-title mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-stat primary shadow-sm p-3 h-100">
            <div class="text-muted small">Referenze totali</div>
            <div class="fs-2 fw-bold text-primary" id="statReferenze">—</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat success shadow-sm p-3 h-100">
            <div class="text-muted small">Categorie</div>
            <div class="fs-2 fw-bold text-success" id="statCategorie">—</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat warning shadow-sm p-3 h-100">
            <div class="text-muted small">Prodotti</div>
            <div class="fs-2 fw-bold text-warning" id="statProdotti">—</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat danger shadow-sm p-3 h-100">
            <div class="text-muted small">Sotto soglia</div>
            <div class="fs-2 fw-bold text-danger" id="statSoglia">—</div>
        </div>
    </div>
</div>

<h5 class="section-title"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Prodotti sotto soglia di riordino</h5>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr><th>Codice Prodotto</th><th>Soglia Riordino</th><th>Giacenza Totale</th><th>Categoria</th><th>Cod. Reg.</th><th>Cod. OE</th></tr>
        </thead>
        <tbody id="tbodySoglia">
            <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>

<?php include '_footer.php'; ?>
<script>
async function loadDashboard() {
    try {
        const [pano, soglia] = await Promise.all([
            apiCall('/report/panoramica'),
            apiCall('/report/sotto-soglia')
        ]);
        document.getElementById('statReferenze').textContent = pano.totaleReferenze ?? '—';
        document.getElementById('statCategorie').textContent = Array.isArray(pano.categorie) ? pano.categorie.length : '—';
        document.getElementById('statProdotti').textContent  = Array.isArray(pano.prodotti)  ? pano.prodotti.length  : '—';
        document.getElementById('statSoglia').textContent    = pano.prodottiSottoSoglia ?? '—';

        const tbody = document.getElementById('tbodySoglia');
        if (!Array.isArray(soglia) || soglia.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-success py-3"><i class="bi bi-check-circle"></i> Nessun prodotto sotto soglia</td></tr>';
            return;
        }
        tbody.innerHTML = soglia.map(p => `
            <tr>
                <td><strong>${escHtml(p.codProd)}</strong></td>
                <td>${escHtml(String(p.qtaRiordino))}</td>
                <td><span class="badge text-bg-danger">${escHtml(String(p.giacenzaTotale))}</span></td>
                <td>${escHtml(p.codCat ?? '—')}</td>
                <td>${escHtml(p.codReg ?? '—')}</td>
                <td>${escHtml(p.codOE ?? '—')}</td>
            </tr>
        `).join('');
    } catch(e) {
        showToast(e.message, 'danger');
    }
}
loadDashboard();
</script>
