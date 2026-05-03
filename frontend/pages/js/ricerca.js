let allProdotti    = [];
let cacheCategorie = [], cacheCodReg = [], cacheCodOE = [], cacheAttributi = [];
let risultatiFiltrati = [];
let attrRowCounter = 0;
let cacheFiltriSalvati = [];

/* ---------- caricamento iniziale ------------------------------------ */
async function init() {
    // /prodotti/giacenza ha giacenzaTotale ma senza attributi
    // /prodotti ha attributi ma senza giacenzaTotale → merge
    const [resProdGiac, resProd, resCat, resReg, resOE, resAttr] = await Promise.allSettled([
        apiCall('/prodotti/giacenza'),
        apiCall('/prodotti'),
        apiCall('/categorie'),
        apiCall('/codifiche/reg'),
        apiCall('/codifiche/oe'),
        apiCall('/attributi'),
    ]);

    const prodGiac  = resProdGiac.status === 'fulfilled' ? (resProdGiac.value ?? []) : [];
    const prodAttrs = resProd.status     === 'fulfilled' ? (resProd.value     ?? []) : [];

    const attrByProd = {};
    prodAttrs.forEach(p => { attrByProd[p.codProd] = p.attributi ?? []; });
    allProdotti = prodGiac.map(p => ({ ...p, attributi: attrByProd[p.codProd] ?? [] }));

    // Default genere "Unisex" per calzature e vestiario senza attributo GEN
    const catConGenere = new Set(['CAL', 'VES']);
    allProdotti.forEach(p => {
        if (catConGenere.has(p.codCat) && !p.attributi.some(a => a.codAttr === 'GEN'))
            p.attributi.push({ codAttr: 'GEN', valore: 'Unisex' });
    });

    cacheCategorie = resCat.status  === 'fulfilled' ? (resCat.value  ?? []) : [];
    cacheCodReg    = resReg.status  === 'fulfilled' ? (resReg.value  ?? []) : [];
    cacheCodOE     = resOE.status   === 'fulfilled' ? (resOE.value   ?? []) : [];
    cacheAttributi = resAttr.status === 'fulfilled' ? (resAttr.value ?? []) : [];

    popolaDropdown();
    await renderFiltriSalvati();

    // modal salva filtro: reset input all'apertura + invio con Enter
    const modalEl = document.getElementById('modalSalvaFiltro');
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', () => {
            const inp = document.getElementById('nomeFiltroInput');
            inp.value = '';
            inp.classList.remove('is-invalid');
        });
        document.getElementById('nomeFiltroInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') salvaFiltroCorrente();
        });
    }
}

/* ---------- popola dropdown filtri base ----------------------------- */
function popolaDropdown() {
    const selCat = document.getElementById('fFiltCat');
    cacheCategorie.forEach(c => {
        const o = document.createElement('option');
        o.value = c.codCat;
        o.textContent = c.codCat + (c.tipo ? ' — ' + c.tipo : '');
        selCat.appendChild(o);
    });

    const selReg = document.getElementById('fFiltReg');
    cacheCodReg.forEach(r => {
        const o = document.createElement('option');
        o.value = r.codReg;
        o.textContent = r.descrizione ? r.descrizione + ' (' + r.codReg + ')' : r.codReg;
        selReg.appendChild(o);
    });

    const selOE = document.getElementById('fFiltOE');
    cacheCodOE.forEach(r => {
        const o = document.createElement('option');
        o.value = r.codOE;
        o.textContent = r.descrizione ? r.descrizione + ' (' + r.codOE + ')' : r.codOE;
        selOE.appendChild(o);
    });
}

/* ---------- riga filtro attributo dinamica -------------------------- */
function addAttrFiltroRow() {
    const id = 'attrRow_' + (++attrRowCounter);
    const tipoOptions = cacheAttributi
        .map(a => `<option value="${escHtml(a.codAttr)}">${escHtml(a.nome ?? a.codAttr)}</option>`)
        .join('');

    const div = document.createElement('div');
    div.className = 'row g-2 mb-2 attr-filtro-row';
    div.id = id;
    div.innerHTML = `
        <div class="col-sm-5">
            <select class="form-select form-select-sm attr-tipo" onchange="onAttrTipoChange(this)">
                <option value="">— scegli attributo —</option>
                ${tipoOptions}
            </select>
        </div>
        <div class="col-sm-5">
            <select class="form-select form-select-sm attr-valore" disabled>
                <option value="">— tutti i valori —</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-danger" onclick="removeAttrFiltroRow('${id}')" aria-label="Rimuovi filtro">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>`;
    document.getElementById('filtriAttrContainer').appendChild(div);
}

function removeAttrFiltroRow(id) {
    document.getElementById(id)?.remove();
}

function onAttrTipoChange(sel) {
    const row     = sel.closest('.attr-filtro-row');
    const selVal  = row.querySelector('.attr-valore');
    const codAttr = sel.value;

    selVal.innerHTML = '<option value="">— tutti i valori —</option>';
    if (!codAttr) { selVal.disabled = true; return; }

    const valoriSet = new Set();
    allProdotti.forEach(p =>
        (p.attributi ?? []).forEach(a => {
            if (a.codAttr === codAttr && a.valore != null && a.valore !== '')
                valoriSet.add(a.valore);
        })
    );

    const valori = [...valoriSet].sort((a, b) => a.localeCompare(b, 'it', { numeric: true }));
    valori.forEach(v => {
        const o = document.createElement('option');
        o.value = v; o.textContent = v;
        selVal.appendChild(o);
    });
    selVal.disabled = valori.length === 0;
}

/* ---------- esegui ricerca ------------------------------------------ */
function eseguiRicerca() {
    const filtCat    = document.getElementById('fFiltCat').value;
    const filtReg    = document.getElementById('fFiltReg').value;
    const filtOE     = document.getElementById('fFiltOE').value;
    const filtSoglia = document.getElementById('fFiltSoglia').checked;

    const filtriAttr = [];
    document.querySelectorAll('.attr-filtro-row').forEach(row => {
        const tipo   = row.querySelector('.attr-tipo').value;
        const valore = row.querySelector('.attr-valore').value;
        if (tipo) filtriAttr.push({ codAttr: tipo, valore: valore || null });
    });

    risultatiFiltrati = allProdotti.filter(p => {
        if (filtCat && p.codCat !== filtCat) return false;
        if (filtReg && p.codReg !== filtReg) return false;
        if (filtOE  && p.codOE  !== filtOE)  return false;
        if (filtSoglia && (p.giacenzaTotale ?? 0) >= p.qtaRiordino) return false;
        for (const f of filtriAttr) {
            const attr = (p.attributi ?? []).find(a => a.codAttr === f.codAttr);
            if (!attr) return false;
            if (f.valore && attr.valore !== f.valore) return false;
        }
        return true;
    });

    renderRisultati();
}

/* ---------- reset --------------------------------------------------- */
function resetFiltri() {
    document.getElementById('fFiltCat').value      = '';
    document.getElementById('fFiltReg').value      = '';
    document.getElementById('fFiltOE').value       = '';
    document.getElementById('fFiltSoglia').checked = false;
    document.getElementById('filtriAttrContainer').innerHTML = '';
    attrRowCounter = 0;

    risultatiFiltrati = [];
    document.getElementById('tbodyRicerca').innerHTML = `
        <tr><td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-search me-1"></i>Imposta i filtri e premi <span class="fw-bold">Cerca</span>
        </td></tr>`;
    document.getElementById('lblRisultati').innerHTML = '<i class="bi bi-table me-2"></i>Risultati';
    document.getElementById('btnExcelRicerca').classList.add('d-none');
}

/* ---------- render tabella risultati -------------------------------- */
function renderRisultati() {
    const tbody = document.getElementById('tbodyRicerca');
    const n     = risultatiFiltrati.length;

    document.getElementById('lblRisultati').innerHTML =
        `<i class="bi bi-table me-2"></i>Risultati <span class="badge text-bg-primary ms-1">${n}</span>`;
    document.getElementById('btnExcelRicerca').classList.toggle('d-none', n === 0);

    if (n === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-emoji-frown me-1"></i>Nessun prodotto trovato con i filtri selezionati
        </td></tr>`;
        return;
    }

    tbody.innerHTML = risultatiFiltrati.map(p => {
        const sottoSoglia = (p.giacenzaTotale ?? 0) < p.qtaRiordino;
        const badge = p.giacenzaTotale !== null
            ? `<span class="badge ${sottoSoglia ? 'text-bg-danger' : 'text-bg-success'}">${escHtml(String(p.giacenzaTotale))}</span>`
            : '<span class="badge text-bg-secondary">—</span>';

        const regEntry = cacheCodReg.find(r => r.codReg === p.codReg);
        const oeEntry  = cacheCodOE.find(o => o.codOE   === p.codOE);

        const regCell = p.codReg
            ? `<span class="fw-bold">${escHtml(regEntry?.descrizione ?? p.codReg)}</span><br><small class="text-muted">${escHtml(p.codReg)}</small>`
            : '<span class="text-muted">—</span>';
        const oeCell = p.codOE
            ? `<span class="fw-bold">${escHtml(oeEntry?.descrizione ?? p.codOE)}</span><br><small class="text-muted">${escHtml(p.codOE)}</small>`
            : '<span class="text-muted">—</span>';

        const attrBadges = (p.attributi ?? []).length === 0
            ? '<span class="text-muted">—</span>'
            : p.attributi.map(a => {
                const nome = cacheAttributi.find(x => x.codAttr === a.codAttr)?.nome ?? a.codAttr;
                return `<span class="badge text-bg-light text-dark border me-1">${escHtml(nome)}: <span class="fw-bold">${escHtml(a.valore ?? 'v')}</span></span>`;
            }).join('');

        return `<tr>
            <td><span class="fw-bold">${escHtml(p.codProd)}</span></td>
            <td>${badge}</td>
            <td>${escHtml(String(p.qtaRiordino))}</td>
            <td>${escHtml(p.codCat ?? '-')}</td>
            <td class="small">${regCell}</td>
            <td class="small">${oeCell}</td>
            <td class="small">${attrBadges}</td>
        </tr>`;
    }).join('');
}

/* ---------- export Excel risultati filtrati -------------------------- */
async function exportExcelRicerca() {
    const btn = document.getElementById('btnExcelRicerca');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Generazione...';
    try {
        const attrCodesSet = new Set();
        risultatiFiltrati.forEach(p => (p.attributi ?? []).forEach(a => attrCodesSet.add(a.codAttr)));
        const attrCodes = [...attrCodesSet].sort();
        const attrNomeMap = {};
        cacheAttributi.forEach(a => attrNomeMap[a.codAttr] = a.nome ?? a.codAttr);

        const headers = [
            'Codice', 'Giacenza Totale', 'Soglia Riordino', 'Sotto Soglia',
            'Categoria', 'Descrizione Reg.', 'Codifica Reg.', 'Descrizione OE', 'Codifica OE',
            ...attrCodes.map(c => attrNomeMap[c] ?? c),
        ];
        const rows = risultatiFiltrati.map(p => {
            const giac   = p.giacenzaTotale ?? 0;
            const soglia = p.qtaRiordino    ?? 0;
            const regEntry = cacheCodReg.find(r => r.codReg === p.codReg);
            const oeEntry  = cacheCodOE.find(o => o.codOE  === p.codOE);
            const attrMap  = {};
            (p.attributi ?? []).forEach(a => attrMap[a.codAttr] = a.valore ?? 'v');
            return [
                p.codProd, giac, soglia, giac < soglia ? 'Si' : 'No',
                p.codCat ?? '',
                regEntry?.descrizione ?? '', p.codReg ?? '',
                oeEntry?.descrizione  ?? '', p.codOE  ?? '',
                ...attrCodes.map(c => attrMap[c] ?? ''),
            ];
        });

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
        ws['!cols'] = headers.map((h, i) => ({
            wch: Math.max(h.length, ...rows.map(r => String(r[i] ?? '').length)) + 2
        }));
        XLSX.utils.book_append_sheet(wb, ws, 'Ricerca');
        XLSX.writeFile(wb, tsFilename('ricerca_prodotti', 'xlsx'));
    } catch (e) {
        alert('Errore Excel: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-file-earmark-excel me-1"></i>Excel';
    }
}

/* ---------- filtri salvati (API) ------------------------------------ */
function leggiStatoFiltri() {
    const filtriAttr = [];
    document.querySelectorAll('.attr-filtro-row').forEach(row => {
        const tipo   = row.querySelector('.attr-tipo').value;
        const valore = row.querySelector('.attr-valore').value;
        if (tipo) filtriAttr.push({ codAttr: tipo, valore: valore || null });
    });
    return {
        filtCat:    document.getElementById('fFiltCat').value,
        filtReg:    document.getElementById('fFiltReg').value,
        filtOE:     document.getElementById('fFiltOE').value,
        filtSoglia: document.getElementById('fFiltSoglia').checked,
        filtriAttr,
    };
}

function applicaStatoFiltri(stato) {
    document.getElementById('fFiltCat').value      = stato.filtCat    ?? '';
    document.getElementById('fFiltReg').value      = stato.filtReg    ?? '';
    document.getElementById('fFiltOE').value       = stato.filtOE     ?? '';
    document.getElementById('fFiltSoglia').checked = stato.filtSoglia ?? false;
    document.getElementById('filtriAttrContainer').innerHTML = '';
    attrRowCounter = 0;
    (stato.filtriAttr ?? []).forEach(f => {
        addAttrFiltroRow();
        const rows    = document.querySelectorAll('.attr-filtro-row');
        const lastRow = rows[rows.length - 1];
        const selTipo = lastRow.querySelector('.attr-tipo');
        selTipo.value = f.codAttr;
        onAttrTipoChange(selTipo);
        if (f.valore) lastRow.querySelector('.attr-valore').value = f.valore;
    });
}

async function renderFiltriSalvati() {
    const container = document.getElementById('filtriSalvatiContainer');
    const card      = document.getElementById('cardFiltriSalvati');
    if (!container || !card) return;

    cacheFiltriSalvati = await apiCall('/filtri') ?? [];

    if (cacheFiltriSalvati.length === 0) { card.classList.add('d-none'); return; }
    card.classList.remove('d-none');

    container.innerHTML = cacheFiltriSalvati.map(f => {
        const tags = [];
        if (f.stato.filtCat)    tags.push('Cat: '    + escHtml(f.stato.filtCat));
        if (f.stato.filtReg)    tags.push('Reg: '    + escHtml(f.stato.filtReg));
        if (f.stato.filtOE)     tags.push('OE: '     + escHtml(f.stato.filtOE));
        if (f.stato.filtSoglia) tags.push('Sotto soglia');
        (f.stato.filtriAttr ?? []).forEach(a => {
            const nome = cacheAttributi.find(x => x.codAttr === a.codAttr)?.nome ?? a.codAttr;
            tags.push(escHtml(nome) + (a.valore ? ': ' + escHtml(a.valore) : ''));
        });
        const tagsHtml = tags.length
            ? tags.map(t => `<span class="badge text-bg-secondary fw-normal me-1">${t}</span>`).join('')
            : '<span class="text-muted small">nessun filtro</span>';
        return `<div class="d-flex align-items-center gap-2 mb-2 p-2 border rounded bg-light">
            <div class="flex-grow-1">
                <span class="fw-bold me-2">${escHtml(f.nome)}</span>
                ${tagsHtml}
            </div>
            <button class="btn btn-sm btn-outline-primary" onclick="caricaFiltroSalvato(${f.id})" title="Carica e cerca">
                <i class="bi bi-arrow-down-circle me-1"></i>Carica
            </button>
            <button class="btn btn-sm btn-outline-danger" onclick="eliminaFiltroSalvato(${f.id})" title="Elimina">
                <i class="bi bi-trash3"></i>
            </button>
        </div>`;
    }).join('');
}

async function salvaFiltroCorrente() {
    const inp  = document.getElementById('nomeFiltroInput');
    const nome = inp.value.trim();
    if (!nome) { inp.classList.add('is-invalid'); return; }
    inp.classList.remove('is-invalid');
    await apiCall('/filtri', 'POST', { nome, stato: leggiStatoFiltri() });
    await renderFiltriSalvati();
    bootstrap.Modal.getInstance(document.getElementById('modalSalvaFiltro')).hide();
}

function caricaFiltroSalvato(id) {
    const filtro = cacheFiltriSalvati.find(f => f.id === id);
    if (!filtro) return;
    applicaStatoFiltri(filtro.stato);
    eseguiRicerca();
    document.querySelector('.card.shadow-sm:last-of-type')?.scrollIntoView({ behavior: 'smooth' });
}

async function eliminaFiltroSalvato(id) {
    await apiCall('/filtri/' + id, 'DELETE');
    await renderFiltriSalvati();
}

init();