<?php include '_header.php'; ?>

<h2 class="section-title mb-4"><i class="bi bi-upc-scan me-2"></i>Codifiche</h2>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tabReg">Codifiche Regionali</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tabOE">Codifiche OE</a>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB REG -->
    <div class="tab-pane fade show active" id="tabReg">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary btn-sm" onclick="openCreateReg()"><i class="bi bi-plus-lg me-1"></i>Nuova codifica reg.</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Codice</th><th>Descrizione</th><th class="text-end">Azioni</th></tr></thead>
                <tbody id="tbodyReg">
                    <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB OE -->
    <div class="tab-pane fade" id="tabOE">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary btn-sm" onclick="openCreateOE()"><i class="bi bi-plus-lg me-1"></i>Nuova codifica OE</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Codice</th><th>Descrizione</th><th>Fornitore</th><th class="text-end">Azioni</th></tr></thead>
                <tbody id="tbodyOE">
                    <tr><td colspan="4" class="text-center text-muted py-3">Caricamento...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Reg -->
<div class="modal fade" id="modalReg" tabindex="-1" aria-labelledby="titleReg" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleReg">Codifica Regionale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodReg" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fDescReg" maxlength="100">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitReg()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal OE -->
<div class="modal fade" id="modalOE" tabindex="-1" aria-labelledby="titleOE" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleOE">Codifica OE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodOE" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrizione <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fDescOE" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fornitore (ragione sociale)</label>
                    <input type="text" class="form-control" id="fRagSocOE" maxlength="100" placeholder="opzionale">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitOE()">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script>
let regList = [], oeList = [];
let editReg = null, editOE = null;

async function loadReg() {
    try {
        regList = await apiCall('/codifiche/reg') ?? [];
        const tbody = document.getElementById('tbodyReg');
        if (regList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Nessuna codifica regionale</td></tr>';
            return;
        }
        tbody.innerHTML = regList.map(r => `
            <tr>
                <td><code>${escHtml(r.codReg)}</code></td>
                <td>${escHtml(r.descrizione)}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEditReg(${escHtml(JSON.stringify(r.codReg))})" aria-label="Modifica codifica ${escHtml(r.codReg)}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteReg(${escHtml(JSON.stringify(r.codReg))})" aria-label="Elimina codifica ${escHtml(r.codReg)}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`).join('');
    } catch(e) { showToast(e.message, 'danger'); }
}

async function loadOE() {
    try {
        oeList = await apiCall('/codifiche/oe') ?? [];
        const tbody = document.getElementById('tbodyOE');
        if (oeList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Nessuna codifica OE</td></tr>';
            return;
        }
        tbody.innerHTML = oeList.map(o => `
            <tr>
                <td><code>${escHtml(o.codOE)}</code></td>
                <td>${escHtml(o.descrizione)}</td>
                <td>${escHtml(o.ragSoc ?? '—')}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEditOE(${escHtml(JSON.stringify(o.codOE))})" aria-label="Modifica codifica OE ${escHtml(o.codOE)}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteOE(${escHtml(JSON.stringify(o.codOE))})" aria-label="Elimina codifica OE ${escHtml(o.codOE)}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`).join('');
    } catch(e) { showToast(e.message, 'danger'); }
}

// — Codifica Reg —
function openCreateReg() {
    editReg = null;
    document.getElementById('titleReg').textContent = 'Nuova codifica regionale';
    document.getElementById('fCodReg').value = ''; document.getElementById('fCodReg').disabled = false;
    document.getElementById('fDescReg').value = '';
    getModal('modalReg').show();
}
function openEditReg(cod) {
    const r = regList.find(x => x.codReg === cod);
    if (!r) return;
    editReg = cod;
    document.getElementById('titleReg').textContent = 'Modifica codifica regionale';
    document.getElementById('fCodReg').value = r.codReg; document.getElementById('fCodReg').disabled = true;
    document.getElementById('fDescReg').value = r.descrizione;
    getModal('modalReg').show();
}
async function submitReg() {
    const cod  = document.getElementById('fCodReg').value.trim();
    const desc = document.getElementById('fDescReg').value.trim();
    if (!desc || (!cod && !editReg)) { showToast('Compila tutti i campi obbligatori', 'danger'); return; }
    try {
        if (!editReg) {
            await apiCall('/codifiche/reg', 'POST', { codReg: cod, descrizione: desc });
            showToast('Codifica regionale creata');
        } else {
            await apiCall('/codifiche/reg/' + encodeURIComponent(editReg), 'PUT', { descrizione: desc });
            showToast('Codifica regionale aggiornata');
        }
        getModal('modalReg').hide(); loadReg();
    } catch(e) { showToast(e.message, 'danger'); }
}
async function deleteReg(cod) {
    confirmDel(`Eliminare la codifica regionale "${cod}"?`, async () => {
        try { await apiCall('/codifiche/reg/' + encodeURIComponent(cod), 'DELETE'); showToast('Eliminata'); loadReg(); }
        catch(e) { showToast(e.message, 'danger'); }
    });
}

// — Codifica OE —
function openCreateOE() {
    editOE = null;
    document.getElementById('titleOE').textContent = 'Nuova codifica OE';
    ['fCodOE','fDescOE','fRagSocOE'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('fCodOE').disabled = false;
    getModal('modalOE').show();
}
function openEditOE(cod) {
    const o = oeList.find(x => x.codOE === cod);
    if (!o) return;
    editOE = cod;
    document.getElementById('titleOE').textContent = 'Modifica codifica OE';
    document.getElementById('fCodOE').value = o.codOE; document.getElementById('fCodOE').disabled = true;
    document.getElementById('fDescOE').value = o.descrizione;
    document.getElementById('fRagSocOE').value = o.ragSoc ?? '';
    getModal('modalOE').show();
}
async function submitOE() {
    const cod    = document.getElementById('fCodOE').value.trim();
    const desc   = document.getElementById('fDescOE').value.trim();
    const ragSoc = document.getElementById('fRagSocOE').value.trim() || null;
    if (!desc || (!cod && !editOE)) { showToast('Compila tutti i campi obbligatori', 'danger'); return; }
    try {
        if (!editOE) {
            await apiCall('/codifiche/oe', 'POST', { codOE: cod, descrizione: desc, ragSoc });
            showToast('Codifica OE creata');
        } else {
            await apiCall('/codifiche/oe/' + encodeURIComponent(editOE), 'PUT', { descrizione: desc, ragSoc });
            showToast('Codifica OE aggiornata');
        }
        getModal('modalOE').hide(); loadOE();
    } catch(e) { showToast(e.message, 'danger'); }
}
async function deleteOE(cod) {
    confirmDel(`Eliminare la codifica OE "${cod}"?`, async () => {
        try { await apiCall('/codifiche/oe/' + encodeURIComponent(cod), 'DELETE'); showToast('Eliminata'); loadOE(); }
        catch(e) { showToast(e.message, 'danger'); }
    });
}

loadReg(); loadOE();
</script>
