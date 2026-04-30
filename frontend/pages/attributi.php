<?php include '_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-title mb-0"><i class="bi bi-list-check me-2"></i>Attributi</h2>
    <button class="btn btn-primary btn-sm" onclick="openCreate()">
        <i class="bi bi-plus-lg me-1"></i>Nuovo attributo
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr><th scope="col">Codice</th><th scope="col">Nome</th><th scope="col" class="text-end">Azioni</th></tr>
        </thead>
        <tbody id="tbodyAttributi">
            <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Attributo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Codice attributo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodAttr" maxlength="20">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nome <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fNome" maxlength="100">
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
let attributi = [];
let editCod = null;

async function loadAttributi() {
    try {
        attributi = await apiCall('/attributi') ?? [];
        const tbody = document.getElementById('tbodyAttributi');
        if (attributi.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Nessun attributo presente</td></tr>';
            return;
        }
        tbody.innerHTML = attributi.map(a => `
            <tr>
                <td><code>${escHtml(a.codAttr)}</code></td>
                <td>${escHtml(a.nome)}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEdit(${escHtml(JSON.stringify(a.codAttr))})" aria-label="Modifica attributo ${escHtml(a.codAttr)}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAttr(${escHtml(JSON.stringify(a.codAttr))})" aria-label="Elimina attributo ${escHtml(a.codAttr)}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`).join('');
    } catch(e) { showToast(e.message, 'danger'); }
}

function openCreate() {
    editCod = null;
    document.getElementById('modalTitle').textContent = 'Nuovo attributo';
    document.getElementById('fCodAttr').value = '';
    document.getElementById('fCodAttr').disabled = false;
    document.getElementById('fNome').value = '';
    getModal('modalForm').show();
}

function openEdit(cod) {
    const a = attributi.find(x => x.codAttr === cod);
    if (!a) return;
    editCod = cod;
    document.getElementById('modalTitle').textContent = 'Modifica attributo';
    document.getElementById('fCodAttr').value = a.codAttr;
    document.getElementById('fCodAttr').disabled = true;
    document.getElementById('fNome').value = a.nome;
    getModal('modalForm').show();
}

async function submitForm() {
    const cod  = document.getElementById('fCodAttr').value.trim();
    const nome = document.getElementById('fNome').value.trim();
    if (!nome || (!cod && !editCod)) { showToast('Compila tutti i campi obbligatori', 'danger'); return; }
    try {
        if (!editCod) {
            await apiCall('/attributi', 'POST', { codAttr: cod, nome });
            showToast('Attributo creato');
        } else {
            await apiCall('/attributi/' + encodeURIComponent(editCod), 'PUT', { nome });
            showToast('Attributo aggiornato');
        }
        getModal('modalForm').hide();
        loadAttributi();
    } catch(e) { showToast(e.message, 'danger'); }
}

async function deleteAttr(cod) {
    confirmDel(`Eliminare l'attributo "${cod}"?`, async () => {
        try {
            await apiCall('/attributi/' + encodeURIComponent(cod), 'DELETE');
            showToast('Attributo eliminato');
            loadAttributi();
        } catch(e) { showToast(e.message, 'danger'); }
    });
}

loadAttributi();
</script>
