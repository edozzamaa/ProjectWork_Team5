<?php include '_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-title mb-0"><i class="bi bi-truck me-2"></i>Fornitori</h2>
    <button class="btn btn-primary btn-sm" onclick="openCreate()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo fornitore
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr><th scope="col">Ragione Sociale</th><th scope="col">P.IVA</th><th scope="col">Telefono</th><th scope="col">Email</th><th scope="col">Indirizzo</th><th scope="col" class="text-end">Azioni</th></tr>
        </thead>
        <tbody id="tbodyFornitori">
            <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Fornitore</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Ragione sociale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fRagSoc" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Partita IVA</label>
                    <input type="text" class="form-control" id="fPartIVA" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefono</label>
                    <input type="tel" class="form-control" id="fTelefono" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="fEmail" maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Indirizzo</label>
                    <input type="text" class="form-control" id="fIndirizzo" maxlength="200">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
<script>
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
                <td><strong>${escHtml(f.ragSoc)}</strong></td>
                <td>${escHtml(f.partIVA ?? '—')}</td>
                <td>${escHtml(f.telefono ?? '—')}</td>
                <td>${escHtml(f.email ?? '—')}</td>
                <td>${escHtml(f.indirizzo ?? '—')}</td>
                <td class="text-end">
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
    const ragSoc   = document.getElementById('fRagSoc').value.trim();
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

loadFornitori();
</script>
