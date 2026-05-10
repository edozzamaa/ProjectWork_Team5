<?php include '_header.php'; ?>

<section aria-labelledby="titoloCategorie">
<header class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="section-title mb-0" id="titoloCategorie"><i class="bi bi-tags me-2"></i>Categorie</h1>
    <button class="btn btn-primary btn-sm" onclick="openCreate()">
        <i class="bi bi-plus-lg me-1"></i>Nuova categoria
    </button>
</header>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <caption class="visually-hidden">Elenco categorie</caption>
        <thead>
            <tr><th scope="col">Codice</th><th scope="col">Tipo</th><th scope="col" class="text-end">Azioni</th></tr>
        </thead>
        <tbody id="tbodyCategorie">
            <tr><td colspan="3" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>
</section>

<!-- Modal Crea/Modifica -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Categoria</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <form id="formCategoria" onsubmit="submitForm(); return false;" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="fCodCat">Codice categoria <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fCodCat" maxlength="10">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fTipo">Tipo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fTipo" maxlength="50">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Elimina categoria -->
<div class="modal fade" id="modalElimina" tabindex="-1" aria-labelledby="titleElimina" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h2 class="modal-title fs-5" id="titleElimina"><i class="bi bi-exclamation-triangle-fill me-2"></i>Elimina categoria</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Stai per eliminare la categoria:</p>
                <p class="fw-bold fs-5 mb-3" id="eliminaNome"></p>
                <div id="eliminaWarningProdotti" class="alert alert-danger d-none" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Verranno eliminati anche <strong id="eliminaNProdotti"></strong> associati a questa categoria.
                </div>
                <p class="text-muted mb-0">L'operazione è irreversibile.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-danger" id="btnConfermaElimina" onclick="confermaElimina()">
                    <i class="bi bi-trash me-1"></i>Elimina
                </button>
            </div>
        </div>
    </div>
</div>

<script src="/js/categorie.js"></script>
<?php include '_footer.php'; ?>