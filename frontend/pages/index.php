<?php include '_header.php'; ?>

<section aria-labelledby="titoloDashboard">
<header class="mb-4">
    <h1 class="section-title mb-1" id="titoloDashboard"><i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>Dashboard</h1>
    <p class="text-muted small mb-0">Panoramica del magazzino: giacenze, categorie e prodotti sotto soglia di riordino.</p>
</header>
<dl class="row g-3 mb-4">
    <div class="col-6 col-md-3 card card-stat primary shadow-sm p-3">
        <dt class="text-muted small">Pezzi in magazzino</dt>
        <dd class="fs-2 fw-bold text-primary mb-0" id="statReferenze">—</dd>
    </div>
    <div class="col-6 col-md-3 card card-stat success shadow-sm p-3">
        <dt class="text-muted small">Categorie</dt>
        <dd class="fs-2 fw-bold text-success mb-0" id="statCategorie">—</dd>
    </div>
    <div class="col-6 col-md-3 card card-stat warning shadow-sm p-3">
        <dt class="text-muted small">Prodotti</dt>
        <dd class="fs-2 fw-bold text-warning mb-0" id="statProdotti">—</dd>
    </div>
    <div class="col-6 col-md-3 card card-stat danger shadow-sm p-3">
        <dt class="text-muted small">Sotto soglia</dt>
        <dd class="fs-2 fw-bold text-danger mb-0" id="statSoglia">—</dd>
    </div>
</dl>
</section>

<section aria-labelledby="titoloSoglia">
<h2 class="section-title h5" id="titoloSoglia"><i class="bi bi-exclamation-triangle-fill text-danger me-2" aria-hidden="true"></i>Prodotti sotto soglia di riordino</h2>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <caption class="visually-hidden">Prodotti con giacenza inferiore alla soglia di riordino</caption>
        <thead>
            <tr><th scope="col" id="thIdxCod">Codice Prodotto</th><th scope="col" id="thIdxSoglia">Soglia Riordino</th><th scope="col" id="thIdxGiac">Giacenza Totale</th><th scope="col" id="thIdxCat">Categoria</th><th scope="col" id="thIdxReg">Cod. Reg.</th><th scope="col" id="thIdxOE">Cod. OE</th></tr>
        </thead>
        <tbody id="tbodySoglia" aria-live="polite">
            <tr><td colspan="6" class="text-center text-muted py-3">Caricamento...</td></tr>
        </tbody>
    </table>
</div>
</section>

<script src="/js/index.js"></script>
<?php include '_footer.php'; ?>