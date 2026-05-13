let fornitori = [];
let editRagSoc = null;

async function loadFornitori() {
    try {
        fornitori = await apiCall('/fornitori') ?? [];
        const tbody = document.getElementById('tbodyFornitori');
        if (fornitori.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Nessun fornitore presente</td></tr>';
            return;
        }
        tbody.innerHTML = fornitori.map(f => `
            <tr>
                <td headers="thForRagSoc"><span class="fw-bold">${escHtml(f.ragSoc)}</span></td>
                <td headers="thForPIVA">${escHtml(f.partIVA ?? '—')}</td>
                <td headers="thForTel">${escHtml(f.telefono ?? '—')}</td>
                <td headers="thForEmail">${escHtml(f.email ?? '—')}</td>
                <td headers="thForInd">${escHtml(f.indirizzo ?? '—')}</td>
                <td class="text-end" headers="thForAzioni">
                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEdit(${escHtml(JSON.stringify(f.ragSoc))})" aria-label="Modifica ${escHtml(f.ragSoc)}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteFor(${escHtml(JSON.stringify(f.ragSoc))})" aria-label="Elimina ${escHtml(f.ragSoc)}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `).join('');
    } catch(e) { showToast(e.message, 'danger'); }
}

function openCreate() {
    editRagSoc = null;
    document.getElementById('modalTitle').textContent = 'Nuovo fornitore';
    ['fRagSoc','fPartIVA','fTelefono','fEmail','fIndirizzo'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('fRagSoc').disabled = false;
    getModal('modalForm').show();
}

function openEdit(ragSoc) {
    const f = fornitori.find(x => x.ragSoc === ragSoc);
    if (!f) return;
    editRagSoc = ragSoc;
    document.getElementById('modalTitle').textContent = 'Modifica fornitore';
    document.getElementById('fRagSoc').value = f.ragSoc;
    document.getElementById('fRagSoc').disabled = true;
    document.getElementById('fPartIVA').value = f.partIVA ?? '';
    document.getElementById('fTelefono').value = f.telefono ?? '';
    document.getElementById('fEmail').value = f.email ?? '';
    document.getElementById('fIndirizzo').value = f.indirizzo ?? '';
    getModal('modalForm').show();
}

async function submitForm() {
    const ragSoc = document.getElementById('fRagSoc').value.trim();
    if (!ragSoc && editRagSoc === null) { showToast('La ragione sociale è obbligatoria', 'danger'); return; }
    const fields = {
        partIVA:   document.getElementById('fPartIVA').value.trim()   || null,
        telefono:  document.getElementById('fTelefono').value.trim()  || null,
        email:     document.getElementById('fEmail').value.trim()     || null,
        indirizzo: document.getElementById('fIndirizzo').value.trim() || null,
    };
    try {
        if (editRagSoc === null) {
            await apiCall('/fornitori', 'POST', { ragSoc, ...fields });
            showToast('Fornitore creato');
        } else {
            await apiCall('/fornitori/' + encodeURIComponent(editRagSoc), 'PUT', fields);
            showToast('Fornitore aggiornato');
        }
        getModal('modalForm').hide();
        loadFornitori();
    } catch(e) { showToast(e.message, 'danger'); }
}

async function deleteFor(ragSoc) {
    confirmDel(`Eliminare il fornitore "${ragSoc}"?`, async () => {
        try {
            await apiCall('/fornitori/' + encodeURIComponent(ragSoc), 'DELETE');
            showToast('Fornitore eliminato');
            loadFornitori();
        } catch(e) { showToast(e.message, 'danger'); }
    });
}

document.getElementById('btnNuovoFornitore').addEventListener('click', openCreate);
document.getElementById('formFornitore').addEventListener('submit', e => { e.preventDefault(); submitForm(); });
document.getElementById('btnSalvaFornitore').addEventListener('click', submitForm);

loadFornitori();
