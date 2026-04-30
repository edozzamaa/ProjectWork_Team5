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
                <th scope="col">Codice</th><th scope="col">Giacenza</th><th scope="col">Soglia Riordino</th>
                <th scope="col">Posizioni</th>
                <th scope="col">Categoria</th><th scope="col">Cod. Reg.</th><th scope="col">Cod. OE</th>
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
                <div class="mb-3">
                    <label class="form-label">Codice prodotto <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodProd" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Soglia riordino</label>
                    <input type="number" class="form-control" id="fQtaRiordino" min="0" value="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Codice categoria</label>
                    <input type="text" class="form-control" id="fCodCat" maxlength="10" placeholder="opzionale">
                </div>
                <div class="mb-3">
                    <label class="form-label">Codifica regionale</label>
                    <input type="text" class="form-control" id="fCodReg" maxlength="20" placeholder="opzionale">
                </div>
                <div class="mb-3">
                    <label class="form-label">Codifica OE</label>
                    <input type="text" class="form-control" id="fCodOE" maxlength="20" placeholder="opzionale">
                </div>
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
                        <input type="text" class="form-control" id="fCaricoArmadio" placeholder="es. ARM01">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Scaffale <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fCaricoScaffale" placeholder="es. S01">
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
                        <input type="text" class="form-control" id="fScaricoCodArmadio" placeholder="es. ARM01">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Scaffale <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fScaricoCodScaffale" placeholder="es. S01">
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
                        <input type="text" class="form-control form-control-sm" id="fAssignCodAttr" placeholder="Codice attributo">
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
<script>
let prodotti = [];
let prodEditCod = null;
let giacenzeMap = {};

async function loadProdotti() {
    try {
        const [resProd, resGiac] = await Promise.allSettled([
            apiCall('/prodotti/giacenza'),
            apiCall('/report/giacenze')
        ]);
        prodotti = resProd.status === 'fulfilled' ? (resProd.value ?? []) : [];
        const giacenzeList = resGiac.status === 'fulfilled' ? (resGiac.value ?? []) : [];
        giacenzeMap = {};
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
                : posizioni.map(g => `<span class="badge text-bg-light text-dark border me-1">${escHtml(g.codArmadio)}/${escHtml(g.codScaffale)} <strong>${escHtml(String(g.qta))}</strong></span>`).join('');
            return `
                <tr>
                    <td><strong>${escHtml(p.codProd)}</strong></td>
                    <td>${badge}</td>
                    <td>${escHtml(String(p.qtaRiordino))}</td>
                    <td class="small">${posBadges}</td>
                    <td>${escHtml(p.codCat ?? '—')}</td>
                    <td>${escHtml(p.codReg ?? '—')}</td>
                    <td>${escHtml(p.codOE ?? '—')}</td>
                    <td class="text-end text-nowrap">
                        <button class="btn btn-sm btn-outline-success me-1" aria-label="Carico ${escHtml(p.codProd)}" onclick="openCarico(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-arrow-down-circle"></i></button>
                        <button class="btn btn-sm btn-outline-warning me-1" aria-label="Scarico ${escHtml(p.codProd)}" onclick="openScarico(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-arrow-up-circle"></i></button>
                        <button class="btn btn-sm btn-outline-info me-1" aria-label="Attributi ${escHtml(p.codProd)}" onclick="openAttrProd(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-list-check"></i></button>
                        <button class="btn btn-sm btn-outline-secondary me-1" aria-label="Modifica ${escHtml(p.codProd)}" onclick="openEdit(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" aria-label="Elimina ${escHtml(p.codProd)}" onclick="deleteProd(${escHtml(JSON.stringify(p.codProd))})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
        }).join('');
    } catch(e) { showToast(e.message, 'danger'); }
}

// — CRUD Prodotto —
function openCreate() {
    prodEditCod = null;
    document.getElementById('titleProdotto').textContent = 'Nuovo prodotto';
    document.getElementById('fCodProd').value = '';
    document.getElementById('fCodProd').disabled = false;
    document.getElementById('fQtaRiordino').value = '0';
    ['fCodCat','fCodReg','fCodOE'].forEach(id => document.getElementById(id).value = '');
    getModal('modalProdotto').show();
}

function openEdit(cod) {
    const p = prodotti.find(x => x.codProd === cod);
    if (!p) return;
    prodEditCod = cod;
    document.getElementById('titleProdotto').textContent = 'Modifica prodotto';
    document.getElementById('fCodProd').value = p.codProd;
    document.getElementById('fCodProd').disabled = true;
    document.getElementById('fQtaRiordino').value = p.qtaRiordino ?? 0;
    document.getElementById('fCodCat').value = p.codCat ?? '';
    document.getElementById('fCodReg').value = p.codReg ?? '';
    document.getElementById('fCodOE').value  = p.codOE  ?? '';
    getModal('modalProdotto').show();
}

async function submitProdotto() {
    const cod = document.getElementById('fCodProd').value.trim();
    if (!cod && !prodEditCod) { showToast('Il codice prodotto è obbligatorio', 'danger'); return; }
    const body = {
        qtaRiordino: parseInt(document.getElementById('fQtaRiordino').value) || 0,
        codCat: document.getElementById('fCodCat').value.trim() || null,
        codReg: document.getElementById('fCodReg').value.trim() || null,
        codOE:  document.getElementById('fCodOE').value.trim()  || null,
    };
    try {
        if (!prodEditCod) {
            await apiCall('/prodotti', 'POST', { codProd: cod, ...body });
            showToast('Prodotto creato');
        } else {
            await apiCall('/prodotti/' + encodeURIComponent(prodEditCod), 'PUT', body);
            showToast('Prodotto aggiornato');
        }
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
    document.getElementById('fCaricoArmadio').value = '';
    document.getElementById('fCaricoScaffale').value = '';
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
    const codProd    = document.getElementById('fCaricoCodProd').value;
    const codArmadio = document.getElementById('fCaricoArmadio').value.trim();
    const codScaffale= document.getElementById('fCaricoScaffale').value.trim();
    const qta        = parseInt(document.getElementById('fCaricoQta').value);
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
    document.getElementById('fScaricoCodArmadio').value = '';
    document.getElementById('fScaricoCodScaffale').value = '';
    document.getElementById('fScaricoQta').value = '1';
    getModal('modalScarico').show();
}

async function submitScarico() {
    const codProd    = document.getElementById('fScaricoCodProd').value;
    const codArmadio = document.getElementById('fScaricoCodArmadio').value.trim();
    const codScaffale= document.getElementById('fScaricoCodScaffale').value.trim();
    const qta        = parseInt(document.getElementById('fScaricoQta').value);
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
    document.getElementById('fAssignCodAttr').value = '';
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

loadProdotti();
</script>
