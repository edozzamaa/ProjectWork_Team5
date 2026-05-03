let prodotti = [];
let prodEditCod = null;
let giacenzeMap = {};
let attrCache = {};
let cacheCategorie = [], cacheArmadi = [], cacheCodReg = [], cacheCodOE = [], cacheAttributi = [];

function fillSelect(id, options, emptyLabel = '\u2014 seleziona \u2014') {
    const sel = document.getElementById(id);
    if (!sel) return;
    sel.innerHTML = `<option value="">${escHtml(emptyLabel)}</option>` +
        options.map(o => `<option value="${escHtml(String(o.value))}">${escHtml(o.label)}</option>`).join('');
}

async function loadSelectData() {
    const [resCat, resArm, resReg, resOE, resAttr] = await Promise.allSettled([
        apiCall('/categorie'),
        apiCall('/armadi'),
        apiCall('/codifiche/reg'),
        apiCall('/codifiche/oe'),
        apiCall('/attributi')
    ]);
    cacheCategorie = resCat.status === 'fulfilled' ? (resCat.value ?? []) : [];
    cacheArmadi    = resArm.status === 'fulfilled' ? (resArm.value ?? []) : [];
    cacheCodReg    = resReg.status === 'fulfilled' ? (resReg.value ?? []) : [];
    cacheCodOE     = resOE.status === 'fulfilled'  ? (resOE.value  ?? []) : [];
    cacheAttributi = resAttr.status === 'fulfilled' ? (resAttr.value ?? []) : [];
}

async function loadProdotti() {
    try {
        const [resProd, resGiac] = await Promise.allSettled([
            apiCall('/prodotti/giacenza'),
            apiCall('/report/giacenze')
        ]);
        prodotti = resProd.status === 'fulfilled' ? (resProd.value ?? []) : [];
        const giacenzeList = resGiac.status === 'fulfilled' ? (resGiac.value ?? []) : [];
        giacenzeMap = {};
        attrCache = {};
        for (const g of giacenzeList) {
            if (!giacenzeMap[g.codProd]) giacenzeMap[g.codProd] = [];
            giacenzeMap[g.codProd].push(g);
        }
        const tbody = document.getElementById('tbodyProdotti');
        if (prodotti.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-3">Nessun prodotto presente</td></tr>';
            return;
        }
        tbody.innerHTML = prodotti.map(p => {
            const sottoSoglia = p.giacenzaTotale !== null && p.giacenzaTotale < p.qtaRiordino;
            const badge = p.giacenzaTotale !== null
                ? `<span class="badge ${sottoSoglia ? 'text-bg-danger' : 'text-bg-success'}">${escHtml(String(p.giacenzaTotale))}</span>`
                : '<span class="badge text-bg-secondary">—</span>';
            const posizioni = (giacenzeMap[p.codProd] ?? []);
            const posBadges = posizioni.length === 0 ? '<span class="text-muted">—</span>'
                : posizioni.map(g => `<span class="badge text-bg-light text-dark border me-1">${escHtml(g.codArmadio)}/${escHtml(g.codScaffale)}</span>`).join('');
            const regEntry = cacheCodReg?.find(r => r.codReg === p.codReg);
            const oeEntry  = cacheCodOE?.find(o => o.codOE  === p.codOE);
            const regCell = p.codReg
                ? `<span class="fw-bold">${escHtml(regEntry?.descrizione ?? p.codReg)}</span><br><small class="text-muted">${escHtml(p.codReg)}</small>`
                : '<span class="text-muted">—</span>';
            const oeCell = p.codOE
                ? `<span class="fw-bold">${escHtml(oeEntry?.descrizione ?? p.codOE)}</span><br><small class="text-muted">${escHtml(p.codOE)}</small>`
                : '<span class="text-muted">—</span>';
            const rowId = 'attrRow_' + escHtml(p.codProd);
            return `
                <tr>
                    <td class="text-center">
                        <button class="btn btn-sm btn-link p-0 text-muted" id="btn_${escHtml(p.codProd)}" onclick="toggleAttrRow(${escHtml(JSON.stringify(p.codProd))})" aria-label="Mostra attributi ${escHtml(p.codProd)}" title="Attributi"><i class="bi bi-chevron-right"></i></button>
                    </td>
                    <td><span class="fw-bold">${escHtml(p.codProd)}</span></td>
                    <td>${badge}</td>
                    <td>${escHtml(String(p.qtaRiordino))}</td>
                    <td class="small">${posBadges}</td>
                    <td>${escHtml(p.codCat ?? '—')}</td>
                    <td class="small">${regCell}</td>
                    <td class="small">${oeCell}</td>
                    <td class="text-end text-nowrap">
                        <button class="btn btn-sm btn-outline-success me-1" aria-label="Carico ${escHtml(p.codProd)}" onclick="openCarico(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-arrow-down-circle"></i></button>
                        <button class="btn btn-sm btn-outline-warning me-1" aria-label="Scarico ${escHtml(p.codProd)}" onclick="openScarico(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-arrow-up-circle"></i></button>
                        <button class="btn btn-sm btn-outline-info me-1" aria-label="Attributi ${escHtml(p.codProd)}" onclick="openAttrProd(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-list-check"></i></button>
                        <button class="btn btn-sm btn-outline-secondary me-1" aria-label="Modifica ${escHtml(p.codProd)}" onclick="openEdit(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" aria-label="Elimina ${escHtml(p.codProd)}" onclick="deleteProd(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr id="${rowId}" class="d-none bg-light">
                    <td></td>
                    <td colspan="8" class="py-2 px-3" id="attrContent_${escHtml(p.codProd)}"><span class="text-muted small">...</span></td>
                </tr>`;
        }).join('');
    } catch(e) { showToast(e.message, 'danger'); }
}

// — CRUD Prodotto —
async function toggleAttrRow(codProd) {
    const row  = document.getElementById('attrRow_' + codProd);
    const icon = document.querySelector('#btn_' + codProd + ' i');
    const isOpen = !row.classList.contains('d-none');
    if (isOpen) {
        row.classList.add('d-none');
        icon.className = 'bi bi-chevron-right';
        return;
    }
    row.classList.remove('d-none');
    icon.className = 'bi bi-chevron-down';
    if (attrCache[codProd] !== undefined) {
        renderAttrRow(codProd, attrCache[codProd]);
        return;
    }
    document.getElementById('attrContent_' + codProd).innerHTML = '<span class="text-muted small"><i class="bi bi-hourglass-split me-1"></i>Caricamento...</span>';
    try {
        const attrs = await apiCall('/prodotti/' + encodeURIComponent(codProd) + '/attributi') ?? [];
        attrCache[codProd] = attrs;
        renderAttrRow(codProd, attrs);
    } catch(e) {
        document.getElementById('attrContent_' + codProd).innerHTML = '<span class="text-danger small">Errore caricamento attributi</span>';
    }
}

function renderAttrRow(codProd, attrs) {
    const el = document.getElementById('attrContent_' + codProd);
    if (!el) return;
    if (attrs.length === 0) {
        el.innerHTML = '<span class="text-muted small fst-italic">Nessun attributo assegnato</span>';
        return;
    }
    el.innerHTML = attrs.map(a =>
        `<span class="badge text-bg-secondary me-1 mb-1">${escHtml(a.codAttr)}: <span class="fw-bold">${escHtml(a.valore ?? '—')}</span></span>`
    ).join('');
}

function addProdAttrRow(codAttr = '', valore = '') {
    const div = document.createElement('div');
    div.className = 'row g-2 mt-1 align-items-center';
    const opts = cacheAttributi.map(a =>
        `<option value="${escHtml(a.codAttr)}" ${a.codAttr === codAttr ? 'selected' : ''}>${escHtml(a.codAttr)} — ${escHtml(a.nome)}</option>`
    ).join('');
    div.innerHTML = `
        <div class="col-5"><select class="form-select form-select-sm prod-attr-cod"><option value="">— seleziona —</option>${opts}</select></div>
        <div class="col-5"><input type="text" class="form-control form-control-sm prod-attr-val" placeholder="Valore (opz.)" value="${escHtml(valore)}"></div>
        <div class="col-2"><button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.closest('.row').remove()"><i class="bi bi-x"></i></button></div>
    `;
    document.getElementById('prodAttrContainer').appendChild(div);
}

function openCreate() {
    prodEditCod = null;
    document.getElementById('titleProdotto').textContent = 'Nuovo prodotto';
    document.getElementById('fCodProd').value = '';
    document.getElementById('fQtaRiordino').value = '0';
    fillSelect('fCodCat', cacheCategorie.map(c => ({ value: c.codCat, label: c.codCat })), '\u2014 nessuna \u2014');
    fillSelect('fCodReg', cacheCodReg.map(r => ({ value: r.codReg, label: r.codReg + (r.descrizione ? ' \u2014 ' + r.descrizione : '') })), '\u2014 nessuna \u2014');
    fillSelect('fCodOE',  cacheCodOE.map(o => ({ value: o.codOE,   label: o.codOE  + (o.descrizione ? ' \u2014 ' + o.descrizione : '') })), '\u2014 nessuna \u2014');
    document.getElementById('prodAttrContainer').innerHTML = '';
    getModal('modalProdotto').show();
}

async function openEdit(cod) {
    const p = prodotti.find(x => x.codProd === cod);
    if (!p) return;
    prodEditCod = cod;
    document.getElementById('titleProdotto').textContent = 'Modifica prodotto';
    document.getElementById('fCodProd').value = p.codProd;
    document.getElementById('fCodProd').disabled = true;
    document.getElementById('fQtaRiordino').value = p.qtaRiordino ?? 0;
    fillSelect('fCodCat', cacheCategorie.map(c => ({ value: c.codCat, label: c.codCat })), '\u2014 nessuna \u2014');
    fillSelect('fCodReg', cacheCodReg.map(r => ({ value: r.codReg, label: r.codReg + (r.descrizione ? ' \u2014 ' + r.descrizione : '') })), '\u2014 nessuna \u2014');
    fillSelect('fCodOE',  cacheCodOE.map(o => ({ value: o.codOE,   label: o.codOE  + (o.descrizione ? ' \u2014 ' + o.descrizione : '') })), '\u2014 nessuna \u2014');
    document.getElementById('fCodCat').value = p.codCat ?? '';
    document.getElementById('fCodReg').value = p.codReg ?? '';
    document.getElementById('fCodOE').value  = p.codOE  ?? '';
    const attrEl = document.getElementById('prodAttrContainer');
    attrEl.innerHTML = '<p class="text-muted small mb-0">Caricamento attributi...</p>';
    getModal('modalProdotto').show();
    const attrs = await apiCall('/prodotti/' + encodeURIComponent(cod) + '/attributi') ?? [];
    attrEl.innerHTML = '';
    attrs.forEach(a => addProdAttrRow(a.codAttr, a.valore ?? ''));
}

async function submitProdotto() {
    const body = {
        qtaRiordino: parseInt(document.getElementById('fQtaRiordino').value) || 0,
        codCat: document.getElementById('fCodCat').value || null,
        codReg: document.getElementById('fCodReg').value || null,
        codOE:  document.getElementById('fCodOE').value  || null,
    };
    const attrRows = [];
    document.querySelectorAll('#prodAttrContainer .row').forEach(row => {
        const c = row.querySelector('.prod-attr-cod').value;
        const v = row.querySelector('.prod-attr-val').value.trim() || null;
        if (c) attrRows.push({ codAttr: c, valore: v });
    });
    try {
        let targetCod;
        if (!prodEditCod) {
            const res = await apiCall('/prodotti', 'POST', body);
            targetCod = res.codProd;
            showToast('Prodotto creato (' + targetCod + ')');
        } else {
            targetCod = prodEditCod;
            await apiCall('/prodotti/' + encodeURIComponent(prodEditCod), 'PUT', body);
            showToast('Prodotto aggiornato');
        }
        if (prodEditCod) {
            const existing = await apiCall('/prodotti/' + encodeURIComponent(targetCod) + '/attributi') ?? [];
            await Promise.all(existing.map(a =>
                apiCall('/prodotti/' + encodeURIComponent(targetCod) + '/attributi/' + encodeURIComponent(a.codAttr), 'DELETE')
            ));
        }
        await Promise.all(attrRows.map(a =>
            apiCall('/prodotti/' + encodeURIComponent(targetCod) + '/attributi', 'POST', a)
        ));
        getModal('modalProdotto').hide();
        loadProdotti();
    } catch(e) { showToast(e.message, 'danger'); }
}

async function deleteProd(cod) {
    confirmDel(`Eliminare il prodotto "${cod}"?`, async () => {
        try {
            await apiCall('/prodotti/' + encodeURIComponent(cod), 'DELETE');
            showToast('Prodotto eliminato');
            loadProdotti();
        } catch(e) { showToast(e.message, 'danger'); }
    });
}

// — Carico —
function openCarico(codProd) {
    document.getElementById('titoloCarico').textContent = codProd;
    document.getElementById('fCaricoCodProd').value = codProd;
    fillSelect('fCaricoArmadio', cacheArmadi.map(a => ({ value: a.codArmadio, label: a.codArmadio + (a.descrizione ? ' \u2014 ' + a.descrizione : '') })), '\u2014 seleziona armadio \u2014');
    const selScaf = document.getElementById('fCaricoScaffale');
    selScaf.innerHTML = '<option value="">\u2014 prima seleziona armadio \u2014</option>';
    selScaf.disabled = true;
    document.getElementById('fCaricoQta').value = '1';
    document.getElementById('attrContainer').innerHTML = '';
    getModal('modalCarico').show();
}

function addAttrRow() {
    const div = document.createElement('div');
    div.className = 'row g-2 mt-1';
    div.innerHTML = `
        <div class="col-4"><input type="text" class="form-control form-control-sm attr-cod" placeholder="Codice attr."></div>
        <div class="col-6"><input type="text" class="form-control form-control-sm attr-val" placeholder="Valore"></div>
        <div class="col-2"><button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.closest('.row').remove()"><i class="bi bi-x"></i></button></div>
    `;
    document.getElementById('attrContainer').appendChild(div);
}

async function submitCarico() {
    const codProd     = document.getElementById('fCaricoCodProd').value;
    const codArmadio  = document.getElementById('fCaricoArmadio').value.trim();
    const codScaffale = document.getElementById('fCaricoScaffale').value.trim();
    const qta         = parseInt(document.getElementById('fCaricoQta').value);
    if (!codArmadio || !codScaffale || !qta) { showToast('Armadio, scaffale e quantità sono obbligatori', 'danger'); return; }

    const codRows = document.querySelectorAll('#attrContainer .attr-cod');
    const valRows = document.querySelectorAll('#attrContainer .attr-val');
    const attributi = [];
    for (let i = 0; i < codRows.length; i++) {
        const c = codRows[i].value.trim();
        if (c) attributi.push({ codAttr: c, valore: valRows[i].value.trim() || null });
    }
    try {
        await apiCall('/prodotti/carico', 'POST', { codProd, codArmadio, codScaffale, qta, attributi });
        showToast('Carico effettuato con successo');
        getModal('modalCarico').hide();
        loadProdotti();
    } catch(e) { showToast(e.message, 'danger'); }
}

// — Scarico —
function openScarico(codProd) {
    document.getElementById('titoloScarico').textContent = codProd;
    document.getElementById('fScaricoCodProd').value = codProd;
    const posizioni = giacenzeMap[codProd] ?? [];
    const armadioSet = [...new Set(posizioni.map(g => g.codArmadio))];
    fillSelect('fScaricoCodArmadio', armadioSet.map(a => ({ value: a, label: a })), '\u2014 seleziona armadio \u2014');
    const selScaf = document.getElementById('fScaricoCodScaffale');
    selScaf.innerHTML = '<option value="">\u2014 prima seleziona armadio \u2014</option>';
    selScaf.disabled = true;
    document.getElementById('fScaricoQta').value = '1';
    getModal('modalScarico').show();
}

async function submitScarico() {
    const codProd     = document.getElementById('fScaricoCodProd').value;
    const codArmadio  = document.getElementById('fScaricoCodArmadio').value.trim();
    const codScaffale = document.getElementById('fScaricoCodScaffale').value.trim();
    const qta         = parseInt(document.getElementById('fScaricoQta').value);
    if (!codArmadio || !codScaffale || !qta) { showToast('Armadio, scaffale e quantità sono obbligatori', 'danger'); return; }
    try {
        const result = await apiCall('/prodotti/scarico', 'POST', { codProd, codArmadio, codScaffale, qta });
        const warn = result?.sottoSoglia ? ' ⚠️ Prodotto sotto soglia di riordino!' : '';
        showToast('Scarico effettuato' + warn);
        getModal('modalScarico').hide();
        loadProdotti();
    } catch(e) { showToast(e.message, 'danger'); }
}

// — Attributi prodotto —
async function openAttrProd(codProd) {
    document.getElementById('titoloAttrProd').textContent = codProd;
    document.getElementById('fAttrProdCod').value = codProd;
    fillSelect('fAssignCodAttr', cacheAttributi.map(a => ({ value: a.codAttr, label: a.codAttr + ' \u2014 ' + a.nome })), '\u2014 seleziona attributo \u2014');
    document.getElementById('fAssignValore').value = '';
    getModal('modalAttrProd').show();
    await refreshAttrProd(codProd);
}

async function refreshAttrProd(codProd) {
    const el = document.getElementById('listaAttrProd');
    try {
        const attrs = await apiCall('/prodotti/' + encodeURIComponent(codProd) + '/attributi') ?? [];
        if (attrs.length === 0) {
            el.innerHTML = '<p class="text-muted small">Nessun attributo assegnato</p>';
            return;
        }
        el.innerHTML = `
            <table class="table table-sm">
                <thead><tr><th>Codice</th><th>Valore</th><th></th></tr></thead>
                <tbody>` +
            attrs.map(a => `
                <tr>
                    <td><code>${escHtml(a.codAttr)}</code></td>
                    <td>${escHtml(a.valore ?? '—')}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-danger" onclick="removeAttr(${escHtml(JSON.stringify(a.codAttr))})" aria-label="Rimuovi attributo ${escHtml(a.codAttr)}"><i class="bi bi-x"></i></button>
                    </td>
                </tr>`).join('') +
            `</tbody></table>`;
    } catch(e) { el.innerHTML = '<p class="text-danger small">Errore caricamento attributi</p>'; }
}

async function assignAttr() {
    const codProd = document.getElementById('fAttrProdCod').value;
    const codAttr = document.getElementById('fAssignCodAttr').value.trim();
    const valore  = document.getElementById('fAssignValore').value.trim() || null;
    if (!codAttr) { showToast('Inserisci il codice attributo', 'danger'); return; }
    try {
        await apiCall('/prodotti/' + encodeURIComponent(codProd) + '/attributi', 'POST', { codAttr, valore });
        showToast('Attributo assegnato');
        document.getElementById('fAssignCodAttr').value = '';
        document.getElementById('fAssignValore').value = '';
        refreshAttrProd(codProd);
    } catch(e) { showToast(e.message, 'danger'); }
}

async function removeAttr(codAttr) {
    const codProd = document.getElementById('fAttrProdCod').value;
    confirmDel(`Rimuovere l'attributo "${codAttr}" dal prodotto?`, async () => {
        try {
            await apiCall('/prodotti/' + encodeURIComponent(codProd) + '/attributi/' + encodeURIComponent(codAttr), 'DELETE');
            showToast('Attributo rimosso');
            refreshAttrProd(codProd);
        } catch(e) { showToast(e.message, 'danger'); }
    });
}

// — Cascading selects —
document.getElementById('fCaricoArmadio').addEventListener('change', async function() {
    const selScaf = document.getElementById('fCaricoScaffale');
    const cod = this.value;
    if (!cod) {
        selScaf.innerHTML = '<option value="">— prima seleziona armadio —</option>';
        selScaf.disabled = true;
        return;
    }
    selScaf.innerHTML = '<option value="">Caricamento...</option>';
    selScaf.disabled = true;
    const posizioni = await apiCall('/armadi/' + encodeURIComponent(cod) + '/posizioni') ?? [];
    fillSelect('fCaricoScaffale', posizioni.map(p => ({ value: p.codScaffale, label: p.codScaffale + (p.descrizione ? ' — ' + p.descrizione : '') })), '— seleziona scaffale —');
    selScaf.disabled = posizioni.length === 0;
});

document.getElementById('fScaricoCodArmadio').addEventListener('change', function() {
    const selScaf = document.getElementById('fScaricoCodScaffale');
    const codProd = document.getElementById('fScaricoCodProd').value;
    const codArm  = this.value;
    if (!codArm) {
        selScaf.innerHTML = '<option value="">— prima seleziona armadio —</option>';
        selScaf.disabled = true;
        return;
    }
    const scaffali = (giacenzeMap[codProd] ?? []).filter(g => g.codArmadio === codArm);
    fillSelect('fScaricoCodScaffale', scaffali.map(s => ({ value: s.codScaffale, label: s.codScaffale + ' (' + s.qta + ' pz)' })), '— seleziona scaffale —');
    selScaf.disabled = scaffali.length === 0;
});

loadSelectData();
loadProdotti();
