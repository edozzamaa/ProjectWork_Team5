let categorie = [];
let editCod = null;

async function loadCategorie() {
    try {
        categorie = await apiCall('/categorie') ?? [];
        const tbody = document.getElementById('tbodyCategorie');
        if (categorie.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Nessuna categoria presente</td></tr>';
            return;
        }
        tbody.innerHTML = categorie.map(c => `
            <tr>
                <td><code>${escHtml(c.codCat)}</code></td>
                <td>${escHtml(c.tipo)}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEdit(${escHtml(JSON.stringify(c.codCat))})" aria-label="Modifica categoria ${escHtml(c.codCat)}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteCat(${escHtml(JSON.stringify(c.codCat))})" aria-label="Elimina categoria ${escHtml(c.codCat)}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `).join('');
    } catch(e) { showToast(e.message, 'danger'); }
}

function openCreate() {
    editCod = null;
    document.getElementById('modalTitle').textContent = 'Nuova categoria';
    document.getElementById('fCodCat').value = '';
    document.getElementById('fCodCat').disabled = false;
    document.getElementById('fTipo').value = '';
    getModal('modalForm').show();
}

function openEdit(cod) {
    const c = categorie.find(x => x.codCat === cod);
    if (!c) return;
    editCod = cod;
    document.getElementById('modalTitle').textContent = 'Modifica categoria';
    document.getElementById('fCodCat').value = c.codCat;
    document.getElementById('fCodCat').disabled = true;
    document.getElementById('fTipo').value = c.tipo;
    getModal('modalForm').show();
}

async function submitForm() {
    const codCat = document.getElementById('fCodCat').value.trim();
    const tipo   = document.getElementById('fTipo').value.trim();
    if (editCod === null && !codCat) { showToast('Il codice è obbligatorio', 'danger'); return; }
    if (!tipo) { showToast('Il tipo è obbligatorio', 'danger'); return; }
    try {
        if (editCod === null) {
            await apiCall('/categorie', 'POST', { codCat, tipo });
            showToast('Categoria creata con successo');
        } else {
            await apiCall('/categorie/' + encodeURIComponent(editCod), 'PUT', { tipo });
            showToast('Categoria aggiornata');
        }
        getModal('modalForm').hide();
        loadCategorie();
    } catch(e) { showToast(e.message, 'danger'); }
}

async function deleteCat(cod) {
    confirmDel(`Eliminare la categoria "${cod}"?`, async () => {
        try {
            await apiCall('/categorie/' + encodeURIComponent(cod), 'DELETE');
            showToast('Categoria eliminata');
            loadCategorie();
        } catch(e) { showToast(e.message, 'danger'); }
    });
}

loadCategorie();
