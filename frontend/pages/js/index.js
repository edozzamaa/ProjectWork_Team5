async function loadDashboard() {
    try {
        const [pano, soglia] = await Promise.all([
            apiCall('/report/panoramica'),
            apiCall('/report/sotto-soglia')
        ]);
        const pezziTotali = Array.isArray(pano.prodotti)
            ? pano.prodotti.reduce((sum, p) => sum + (p.giacenzaTotale ?? 0), 0)
            : 0;
        document.getElementById('statReferenze').textContent = pezziTotali;
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
                <td headers="thIdxCod"><span class="fw-bold">${escHtml(p.codProd)}</span></td>
                <td headers="thIdxSoglia">${escHtml(String(p.qtaRiordino))}</td>
                <td headers="thIdxGiac"><span class="badge text-bg-danger">${escHtml(String(p.qtaTotale ?? '—'))}</span></td>
                <td headers="thIdxCat">${escHtml(p.codCat ?? '—')}</td>
                <td headers="thIdxReg">${escHtml(p.codReg ?? '—')}</td>
                <td headers="thIdxOE">${escHtml(p.codOE ?? '—')}</td>
            </tr>
        `).join('');
    } catch(e) {
        showToast(e.message, 'danger');
    }
}

loadDashboard();
