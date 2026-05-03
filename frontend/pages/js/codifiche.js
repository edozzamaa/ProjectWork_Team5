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
