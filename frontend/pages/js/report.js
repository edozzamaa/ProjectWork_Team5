let dataSoglia = [], dataGiacenze = [], dataCompleto = [];
let regMap = {}, oeMap = {}, prodMap = {};

async function loadReport() {
    const [sotto, pan, giacenze, completo, codReg, codOE] = await Promise.allSettled([
        apiCall('/report/sotto-soglia'),
        apiCall('/report/panoramica'),
        apiCall('/report/giacenze'),
        apiCall('/report/completo'),
        apiCall('/codifiche/reg'),
        apiCall('/codifiche/oe'),
    ]);

    // Mappe descrizioni
    if (codReg.status === 'fulfilled') (codReg.value ?? []).forEach(r => regMap[r.codReg] = r.descrizione);
    if (codOE.status === 'fulfilled') (codOE.value ?? []).forEach(o => oeMap[o.codOE] = o.descrizione);
    if (pan.status === 'fulfilled' && Array.isArray(pan.value?.prodotti))
        pan.value.prodotti.forEach(p => prodMap[p.codProd] = p);

    // — Sotto soglia — (campo API: qtaTotale, non giacenzaTotale; codCat viene da prodMap)
    const ts = document.getElementById('tbodySoglia');
    const soItems = sotto.status === 'fulfilled' ? (sotto.value ?? []) : null;
    if (soItems !== null) {
        dataSoglia = soItems;
        if (soItems.length > 0) document.getElementById('btnCsvSoglia').disabled = false;
        ts.innerHTML = soItems.length === 0
            ? '<tr><td colspan="5" class="text-center text-success py-2"><i class="bi bi-check-circle me-1"></i>Nessun prodotto sotto soglia</td></tr>'
            : soItems.map(p => {
                const pr = prodMap[p.codProd];
                const desc = pr ? (regMap[pr.codReg] ?? pr.codReg ?? '—') : '—';
                return `<tr>
                    <td><span class="fw-bold">${escHtml(p.codProd)}</span></td>
                    <td class="small">${escHtml(desc)}</td>
                    <td>${escHtml(String(p.qtaRiordino))}</td>
                    <td><span class="badge text-bg-danger">${escHtml(String(p.qtaTotale ?? p.giacenzaTotale ?? 0))}</span></td>
                    <td>${escHtml(pr?.codCat ?? '—')}</td>
                </tr>`;
            }).join('');
    } else ts.innerHTML = '<tr><td colspan="5" class="text-danger text-center py-2">Errore caricamento</td></tr>';

    // — Panoramica —
    const panEl = document.getElementById('panBody');
    if (pan.status === 'fulfilled' && pan.value) {
        const p = pan.value;
        const pezzi = Array.isArray(p.prodotti) ? p.prodotti.reduce((s, x) => s + (x.giacenzaTotale ?? 0), 0) : 0;
        panEl.innerHTML = `
            <div class="row text-center w-100">
                <div class="col-6 col-md-3 mb-3">
                    <div class="fs-2 fw-bold text-primary">${escHtml(String(pezzi))}</div>
                    <div class="text-muted small">Pezzi in magazzino</div>
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
    const gItems = giacenze.status === 'fulfilled' ? (giacenze.value ?? []) : null;
    if (gItems !== null) {
        dataGiacenze = gItems;
        // Aggrega per armadio+scaffale
        const posizioniMap = {};
        gItems.forEach(g => {
            const k = g.codArmadio + '|' + g.codScaffale;
            if (!posizioniMap[k]) posizioniMap[k] = { codArmadio: g.codArmadio, codScaffale: g.codScaffale, qta: 0 };
            posizioniMap[k].qta += g.qta;
        });
        const posizioni = Object.values(posizioniMap).sort((a, b) =>
            a.codArmadio.localeCompare(b.codArmadio) || a.codScaffale.localeCompare(b.codScaffale)
        );
        tg.innerHTML = posizioni.length === 0
            ? '<tr><td colspan="3" class="text-center text-muted py-2"><i class="bi bi-info-circle me-1"></i>Nessun prodotto ancora posizionato in magazzino</td></tr>'
            : posizioni.map(g => `<tr>
                    <td>${escHtml(g.codArmadio)}</td>
                    <td>${escHtml(g.codScaffale)}</td>
                    <td>${escHtml(String(g.qta))}</td>
                </tr>`).join('');
    } else tg.innerHTML = '<tr><td colspan="3" class="text-danger text-center py-2">Errore caricamento</td></tr>';

    // — Report completo —
    const tc = document.getElementById('tbodyCompleto');
    const cItems = completo.status === 'fulfilled' ? (completo.value ?? []) : null;
    if (cItems !== null) {
        dataCompleto = cItems;
        if (cItems.length > 0) document.getElementById('btnCsvCompleto').disabled = false;
        tc.innerHTML = cItems.length === 0
            ? '<tr><td colspan="6" class="text-center text-muted py-2">Nessun dato disponibile</td></tr>'
            : cItems.map(p => {
                const regDesc = regMap[p.codReg] ?? p.codReg ?? '—';
                const oeDesc  = oeMap[p.codOE]  ?? p.codOE  ?? '—';
                return `<tr>
                    <td><span class="fw-bold">${escHtml(p.codProd)}</span></td>
                    <td class="small">
                        <span>${escHtml(regDesc)}</span><br>
                        <small class="text-muted">${escHtml(oeDesc)}</small>
                    </td>
                    <td>${escHtml(String(p.giacenzaTotale ?? 0))}</td>
                    <td>${escHtml(String(p.qtaRiordino))}</td>
                    <td>${p.sottoSoglia ? '<span class="badge text-bg-danger">Sì</span>' : '<span class="badge text-bg-success">No</span>'}</td>
                    <td>${escHtml(p.codCat ?? '—')}</td>
                </tr>`;
            }).join('');
    } else tc.innerHTML = '<tr><td colspan="6" class="text-danger text-center py-2">Errore caricamento</td></tr>';
}

/* ------------------------------------------------------------------ */
/*  CSV                                                                 */
/* ------------------------------------------------------------------ */
function csvSoglia() {
    downloadCsv(
        dataSoglia.map(p => {
            const pr = prodMap[p.codProd];
            return [p.codProd, regMap[pr?.codReg] ?? pr?.codReg ?? '', p.qtaRiordino, p.qtaTotale ?? p.giacenzaTotale ?? 0, pr?.codCat ?? ''];
        }),
        ['Codice', 'Descrizione', 'Soglia Riordino', 'Giacenza Totale', 'Categoria'],
        tsFilename('sotto_soglia', 'csv')
    );
}
function csvCompleto() {
    downloadCsv(
        dataCompleto.map(p => [
            p.codProd,
            regMap[p.codReg] ?? p.codReg ?? '',
            oeMap[p.codOE]  ?? p.codOE  ?? '',
            p.giacenzaTotale ?? 0,
            p.qtaRiordino,
            p.sottoSoglia ? 'Sì' : 'No',
            p.codCat ?? '',
        ]),
        ['Codice', 'Descrizione Reg.', 'Descrizione OE', 'Giacenza Totale', 'Soglia', 'Sotto Soglia', 'Categoria'],
        tsFilename('report_completo', 'csv')
    );
}

/* ------------------------------------------------------------------ */
/*  Excel sotto soglia                                                  */
/* ------------------------------------------------------------------ */
async function exportExcelSoglia() {
    const btn = document.getElementById('btnExcelSoglia');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Generazione…';
    try {
        const [allProds, allAttrTypes] = await Promise.all([
            apiCall('/prodotti'),
            apiCall('/attributi'),
        ]);

        const prods     = allProds     ?? [];
        const attrTypes = allAttrTypes ?? [];

        // mappa codAttr → nome colonna
        const attrNomeMap = {};
        attrTypes.forEach(a => attrNomeMap[a.codAttr] = a.nome ?? a.codAttr);

        // tutti i codAttr distinti presenti nei prodotti sotto soglia
        const soggliaCodici = new Set(dataSoglia.map(p => p.codProd));
        const prodsSoglia = prods.filter(p => soggliaCodici.has(p.codProd));

        const attrCodes = [];
        prodsSoglia.forEach(p => (p.attributi ?? []).forEach(a => {
            if (!attrCodes.includes(a.codAttr)) attrCodes.push(a.codAttr);
        }));

        // attributi per prodotto: codProd → { codAttr: valore }
        const attrByProd = {};
        prodsSoglia.forEach(p => {
            attrByProd[p.codProd] = {};
            (p.attributi ?? []).forEach(a => attrByProd[p.codProd][a.codAttr] = a.valore ?? '✓');
        });

        const headers = [
            'Codice', 'Descrizione Reg.', 'Codifica Reg.',
            'Descrizione OE', 'Codifica OE',
            'Categoria', 'Giacenza Totale', 'Soglia Riordino',
            ...attrCodes.map(c => attrNomeMap[c] ?? c)
        ];

        const rows = dataSoglia.map(p => {
            const pr = prodMap[p.codProd];
            return [
                p.codProd,
                regMap[pr?.codReg] ?? '',
                pr?.codReg ?? '',
                oeMap[pr?.codOE] ?? '',
                pr?.codOE ?? '',
                pr?.codCat ?? '',
                p.qtaTotale ?? p.giacenzaTotale ?? 0,
                p.qtaRiordino,
                ...attrCodes.map(c => attrByProd[p.codProd]?.[c] ?? ''),
            ];
        });

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
        ws['!cols'] = headers.map((h, i) => ({ wch: Math.max(h.length, ...rows.map(r => String(r[i] ?? '').length)) + 2 }));
        XLSX.utils.book_append_sheet(wb, ws, 'Sotto Soglia');
        XLSX.writeFile(wb, tsFilename('sotto_soglia', 'xlsx'));
    } catch (e) {
        alert('Errore Excel: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-file-earmark-excel me-1"></i>Excel';
    }
}

/* ------------------------------------------------------------------ */
/*  Excel giacenze                                                      */
/* ------------------------------------------------------------------ */
async function exportExcelGiacenze() {
    const btn = document.getElementById('btnExcelGiacenze');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Generazione…';
    try {
        // Aggrega per armadio+scaffale
        const posizioniMap = {};
        dataGiacenze.forEach(g => {
            const k = g.codArmadio + '|' + g.codScaffale;
            if (!posizioniMap[k]) posizioniMap[k] = { codArmadio: g.codArmadio, codScaffale: g.codScaffale, qta: 0 };
            posizioniMap[k].qta += g.qta;
        });
        const posizioni = Object.values(posizioniMap).sort((a, b) =>
            a.codArmadio.localeCompare(b.codArmadio) || a.codScaffale.localeCompare(b.codScaffale)
        );
        const headers = ['Armadio', 'Scaffale', 'Quantità'];
        const rows = posizioni.map(g => [g.codArmadio, g.codScaffale, g.qta]);
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
        ws['!cols'] = headers.map((h, i) => ({ wch: Math.max(h.length, ...rows.map(r => String(r[i] ?? '').length)) + 2 }));
        XLSX.utils.book_append_sheet(wb, ws, 'Giacenze');
        XLSX.writeFile(wb, tsFilename('giacenze', 'xlsx'));
    } catch (e) {
        alert('Errore Excel: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-file-earmark-excel me-1"></i>Excel';
    }
}

/* ------------------------------------------------------------------ */
/*  Excel — usa SheetJS + /api/prodotti (contiene attributi)           */
/* ------------------------------------------------------------------ */
async function exportExcel() {
    const btn = document.getElementById('btnExcelCompleto');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Generazione…';

    try {
        const [allProds, allAttrTypes] = await Promise.all([
            apiCall('/prodotti'),
            apiCall('/attributi'),
        ]);

        const prods     = allProds     ?? [];
        const attrTypes = allAttrTypes ?? [];

        // mappa codAttr → nome colonna
        const attrNomeMap = {};
        attrTypes.forEach(a => attrNomeMap[a.codAttr] = a.nome ?? a.codAttr);

        // tutti i codAttr distinti presenti (ordine di prima comparsa)
        const attrCodes = [];
        prods.forEach(p => (p.attributi ?? []).forEach(a => {
            if (!attrCodes.includes(a.codAttr)) attrCodes.push(a.codAttr);
        }));

        // attributi per prodotto: codProd → { codAttr: valore }
        const attrByProd = {};
        prods.forEach(p => {
            attrByProd[p.codProd] = {};
            (p.attributi ?? []).forEach(a => attrByProd[p.codProd][a.codAttr] = a.valore ?? '✓');
        });

        // usa dataCompleto per le giacenze (già calcolate dal backend)
        const completoMap = {};
        dataCompleto.forEach(p => completoMap[p.codProd] = p);

        const headers = [
            'Codice', 'Descrizione Reg.', 'Codifica Reg.',
            'Descrizione OE', 'Codifica OE',
            'Categoria', 'Giacenza Totale', 'Soglia Riordino', 'Sotto Soglia',
            ...attrCodes.map(c => attrNomeMap[c] ?? c)
        ];

        const rows = prods.map(p => {
            const cp  = completoMap[p.codProd];
            const giac = cp?.giacenzaTotale ?? p.giacenzaTotale ?? 0;
            const soglia = cp?.qtaRiordino ?? p.qtaRiordino ?? 0;
            return [
                p.codProd,
                regMap[p.codReg] ?? '',
                p.codReg ?? '',
                oeMap[p.codOE]  ?? '',
                p.codOE  ?? '',
                p.codCat ?? '',
                giac,
                soglia,
                giac < soglia ? 'Sì' : 'No',
                ...attrCodes.map(c => attrByProd[p.codProd]?.[c] ?? ''),
            ];
        });

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);

        // larghezze colonne automatiche
        const colWidths = headers.map((h, i) => ({
            wch: Math.max(h.length, ...rows.map(r => String(r[i] ?? '').length)) + 2
        }));
        ws['!cols'] = colWidths;

        XLSX.utils.book_append_sheet(wb, ws, 'Prodotti');
        XLSX.writeFile(wb, tsFilename('report_magazzino', 'xlsx'));
    } catch (e) {
        alert('Errore durante la generazione Excel: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-file-earmark-excel me-1"></i>Excel';
    }
}

loadReport();
