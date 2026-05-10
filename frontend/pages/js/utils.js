async function apiCall(path, method = 'GET', body = null) {
    const opts = {
        method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
    };
    if (body !== null) opts.body = JSON.stringify(body);
    const res = await fetch('/api' + path, opts);
    const text = await res.text();
    let json;
    try { json = JSON.parse(text); }
    catch { throw new Error('Risposta non valida dal server (HTTP ' + res.status + ')'); }
    if (!json.success) throw new Error(json.message ?? 'Errore sconosciuto');
    return json.data ?? null;
}

function showToast(msg, type = 'success') {
    const bg   = type === 'success' ? 'text-bg-success' : 'text-bg-danger';
    const role = type === 'success' ? 'status' : 'alert';
    const id   = 'toast_' + Date.now();
    document.getElementById('toastContainer').insertAdjacentHTML('beforeend', `
        <div id="${id}" class="toast align-items-center ${bg} border-0" role="${role}" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${escHtml(msg)}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Chiudi notifica"></button>
            </div>
        </div>`);
    new bootstrap.Toast(document.getElementById(id), { delay: 3500 }).show();
}

function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function confirmDel(msg, fn) {
    document.getElementById('msgConfirmDel').textContent = msg;
    const old = document.getElementById('btnConfirmDel');
    const btn = old.cloneNode(true);
    old.parentNode.replaceChild(btn, old);
    btn.addEventListener('click', async () => {
        getModal('modalConfirmDel').hide();
        await fn();
    });
    getModal('modalConfirmDel').show();
}

function getModal(id) {
    return bootstrap.Modal.getOrCreateInstance(document.getElementById(id));
}

function downloadCsv(rows, headers, filename) {
    const esc = v => '"' + String(v ?? '').replace(/"/g, '""') + '"';
    const csv = [headers.map(esc).join(','), ...rows.map(r => r.map(esc).join(','))].join('\r\n');
    const a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,\uFEFF' + encodeURIComponent(csv);
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function tsFilename(base, ext) {
    const now = new Date();
    const pad = n => String(n).padStart(2, '0');
    const ts = `${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}`;
    return `${base}_${ts}.${ext}`;
}
