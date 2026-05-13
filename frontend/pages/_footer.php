</main><!-- /main -->

<footer class="visually-hidden" aria-label="Piè di pagina"></footer>

<!-- Modal conferma eliminazione (condiviso su tutte le pagine) -->
<div class="modal fade" id="modalConfirmDel" tabindex="-1" aria-labelledby="titleConfirmDel" aria-describedby="msgConfirmDel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h2 class="modal-title fs-5" id="titleConfirmDel"><i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>Conferma eliminazione</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
            <p id="msgConfirmDel" class="mb-1"></p>
            <p class="text-muted small mb-0">L'operazione è irreversibile.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-danger" id="btnConfirmDel">
                <i class="bi bi-trash me-1" aria-hidden="true"></i>Elimina
            </button>
        </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999" id="toastContainer" aria-live="polite" aria-atomic="true"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
