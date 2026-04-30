<?php include '_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-title mb-0"><i class="bi bi-archive me-2"></i>Armadi e Posizioni</h2>
    <button class="btn btn-primary btn-sm" onclick="openCreateArmadio()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo armadio
    </button>
</div>

<div id="armadioList">
    <p class="text-muted">Caricamento...</p>
</div>

<!-- Modal Armadio -->
<div class="modal fade" id="modalArmadio" tabindex="-1" aria-labelledby="titleArmadio" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleArmadio">Armadio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice armadio <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodArmadio" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione</label>
                    <input type="text" class="form-control" id="fDescArmadio" maxlength="100">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitArmadio()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Posizione -->
<div class="modal fade" id="modalPosizione" tabindex="-1" aria-labelledby="titlePosizione" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titlePosizione">Posizione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice scaffale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodScaffale" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione</label>
                    <input type="text" class="form-control" id="fDescPosizione" maxlength="100">
                </div>
                <input type="hidden" id="fPosArmadio">
                <input type="hidden" id="fPosEditMode">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitPosizione()">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script>
let armadioEditCod = null;
let qtaPerArmadio = {};
let qtaPerScaffale = {};

async function loadArmadi() {
    try {
        const [resArmadi, resGiac] = await Promise.allSettled([
            apiCall('/armadi'),
            apiCall('/report/giacenze')
        ]);
        const armadi = resArmadi.status === 'fulfilled' ? (resArmadi.value ?? []) : [];
        const giacenze = resGiac.status === 'fulfilled' ? (resGiac.value ?? []) : [];

        // Costruisce mappe quantità
        qtaPerArmadio = {};
        qtaPerScaffale = {};
        for (const g of giacenze) {
            qtaPerArmadio[g.codArmadio] = (qtaPerArmadio[g.codArmadio] ?? 0) + g.qta;
            const key = g.codArmadio + '|' + g.codScaffale;
            qtaPerScaffale[key] = (qtaPerScaffale[key] ?? 0) + g.qta;
        }
        const container = document.getElementById('armadioList');
        if (armadi.length === 0) {
            container.innerHTML = '<p class="text-muted">Nessun armadio presente.</p>';
            return;
        }
        container.innerHTML = armadi.map(a => `
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <span>
                        <i class="bi bi-archive me-2 text-primary"></i>
                        <strong>${escHtml(a.codArmadio)}</strong>
                        ${a.descrizione ? ' — <span class="text-muted">' + escHtml(a.descrizione) + '</span>' : ''}
                        <span class="badge text-bg-secondary ms-2" title="Pezzi totali in questo armadio">${escHtml(String(qtaPerArmadio[a.codArmadio] ?? 0))} pz</span>
                    </span>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEditArmadio(${escHtml(JSON.stringify(a.codArmadio))})" aria-label="Modifica armadio ${escHtml(a.codArmadio)}"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger me-1" onclick="deleteArmadio(${escHtml(JSON.stringify(a.codArmadio))})" aria-label="Elimina armadio ${escHtml(a.codArmadio)}"><i class="bi bi-trash"></i></button>
                        <button class="btn btn-sm btn-outline-primary" onclick="openCreatePosizione(${escHtml(JSON.stringify(a.codArmadio))})" aria-label="Aggiungi posizione in ${escHtml(a.codArmadio)}"><i class="bi bi-plus-lg"></i> Scaffale</button>
                    </div>
                </div>
                <div class="card-body p-0" id="pos_${escHtml(a.codArmadio)}">
                    <p class="text-muted small p-2 mb-0">Caricamento posizioni...</p>
                </div>
            </div>
        `).join('');
        for (const a of armadi) loadPosizioni(a.codArmadio);
    } catch(e) { showToast(e.message, 'danger'); }
}

async function loadPosizioni(codArmadio) {
    const el = document.getElementById('pos_' + codArmadio);
    if (!el) return;
    try {
        const posizioni = await apiCall('/armadi/' + encodeURIComponent(codArmadio) + '/posizioni') ?? [];
        if (posizioni.length === 0) {
            el.innerHTML = '<p class="text-muted small p-2 mb-0">Nessuna posizione</p>';
            return;
        }
        el.innerHTML = `
            <table class="table table-sm mb-0">
                <thead><tr><th scope="col">Scaffale</th><th scope="col">Descrizione</th><th scope="col">Quantità</th><th scope="col" class="text-end">Azioni</th></tr></thead>
                <tbody>` +
            posizioni.map(p => {
                const qta = qtaPerScaffale[p.codArmadio + '|' + p.codScaffale] ?? 0;
                return `
                <tr>
                    <td><code>${escHtml(p.codScaffale)}</code></td>
                    <td>${escHtml(p.descrizione ?? '—')}</td>
                    <td><span class="badge text-bg-light text-dark border">${escHtml(String(qta))} pz</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEditPosizione(${escHtml(JSON.stringify(p.codArmadio))},${escHtml(JSON.stringify(p.codScaffale))},${escHtml(JSON.stringify(p.descrizione ?? ''))})" aria-label="Modifica posizione ${escHtml(p.codScaffale)}"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePosizione(${escHtml(JSON.stringify(p.codArmadio))},${escHtml(JSON.stringify(p.codScaffale))})" aria-label="Elimina posizione ${escHtml(p.codScaffale)}"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
            }).join('') +
            `</tbody></table>`;
    } catch(e) {
        if (el) el.innerHTML = '<p class="text-danger small p-2">Errore caricamento posizioni</p>';
    }
}

// — Armadio CRUD —
function openCreateArmadio() {
    armadioEditCod = null;
    document.getElementById('titleArmadio').textContent = 'Nuovo armadio';
    document.getElementById('fCodArmadio').value = '';
    document.getElementById('fCodArmadio').disabled = false;
    document.getElementById('fDescArmadio').value = '';
    getModal('modalArmadio').show();
}

function openEditArmadio(cod) {
    armadioEditCod = cod;
    document.getElementById('titleArmadio').textContent = 'Modifica armadio';
    document.getElementById('fCodArmadio').value = cod;
    document.getElementById('fCodArmadio').disabled = true;
    document.getElementById('fDescArmadio').value = '';
    getModal('modalArmadio').show();
}

async function submitArmadio() {
    const cod  = document.getElementById('fCodArmadio').value.trim();
    const desc = document.getElementById('fDescArmadio').value.trim() || null;
    if (!cod && armadioEditCod === null) { showToast('Il codice è obbligatorio', 'danger'); return; }
    try {
        if (armadioEditCod === null) {
            await apiCall('/armadi', 'POST', { codArmadio: cod, descrizione: desc });
            showToast('Armadio creato');
        } else {
            await apiCall('/armadi/' + encodeURIComponent(armadioEditCod), 'PUT', { descrizione: desc });
            showToast('Armadio aggiornato');
        }
        getModal('modalArmadio').hide();
        loadArmadi();
    } catch(e) { showToast(e.message, 'danger'); }
}

async function deleteArmadio(cod) {
    confirmDel(`Eliminare l'armadio "${cod}" e tutte le sue posizioni?`, async () => {
        try {
            await apiCall('/armadi/' + encodeURIComponent(cod), 'DELETE');
            showToast('Armadio eliminato');
            loadArmadi();
        } catch(e) { showToast(e.message, 'danger'); }
    });
}

// — Posizione CRUD —
function openCreatePosizione(codArmadio) {
    document.getElementById('titlePosizione').textContent = 'Nuova posizione in ' + codArmadio;
    document.getElementById('fCodScaffale').value = '';
    document.getElementById('fCodScaffale').disabled = false;
    document.getElementById('fDescPosizione').value = '';
    document.getElementById('fPosArmadio').value = codArmadio;
    document.getElementById('fPosEditMode').value = '';
    getModal('modalPosizione').show();
}

function openEditPosizione(codArmadio, codScaffale, desc) {
    document.getElementById('titlePosizione').textContent = 'Modifica posizione';
    document.getElementById('fCodScaffale').value = codScaffale;
    document.getElementById('fCodScaffale').disabled = true;
    document.getElementById('fDescPosizione').value = desc;
    document.getElementById('fPosArmadio').value = codArmadio;
    document.getElementById('fPosEditMode').value = codScaffale;
    getModal('modalPosizione').show();
}

async function submitPosizione() {
    const codArmadio  = document.getElementById('fPosArmadio').value;
    const codScaffale = document.getElementById('fCodScaffale').value.trim();
    const desc        = document.getElementById('fDescPosizione').value.trim() || null;
    const isEdit      = document.getElementById('fPosEditMode').value;
    if (!codScaffale) { showToast('Il codice scaffale è obbligatorio', 'danger'); return; }
    const base = '/armadi/' + encodeURIComponent(codArmadio) + '/posizioni';
    try {
        if (!isEdit) {
            await apiCall(base, 'POST', { codScaffale, descrizione: desc });
            showToast('Posizione creata');
        } else {
            await apiCall(base + '/' + encodeURIComponent(isEdit), 'PUT', { descrizione: desc });
            showToast('Posizione aggiornata');
        }
        getModal('modalPosizione').hide();
        loadArmadi();
    } catch(e) { showToast(e.message, 'danger'); }
}

async function deletePosizione(codArmadio, codScaffale) {
    confirmDel(`Eliminare la posizione "${codScaffale}" dall'armadio "${codArmadio}"?`, async () => {
        try {
            await apiCall('/armadi/' + encodeURIComponent(codArmadio) + '/posizioni/' + encodeURIComponent(codScaffale), 'DELETE');
            showToast('Posizione eliminata');
            loadArmadi();
        } catch(e) { showToast(e.message, 'danger'); }
    });
}

loadArmadi();
</script>
