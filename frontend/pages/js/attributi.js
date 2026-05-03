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
