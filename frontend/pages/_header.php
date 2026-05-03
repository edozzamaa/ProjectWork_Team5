<?php $currentPage = basename($_SERVER['PHP_SELF'], '.php'); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestione Magazzino</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/style/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/index.php">
            <i class="bi bi-box-seam-fill me-1"></i> Magazzino
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <?php
                $navItems = [
                    'index'     => ['icon' => 'speedometer2', 'label' => 'Dashboard', 'href' => '/index.php'],
                    'prodotti'  => ['icon' => 'box',           'label' => 'Prodotti',  'href' => '/prodotti.php'],
                    'categorie' => ['icon' => 'tags',          'label' => 'Categorie', 'href' => '/categorie.php'],
                    'armadi'    => ['icon' => 'archive',       'label' => 'Armadi',    'href' => '/armadi.php'],
                    'fornitori' => ['icon' => 'truck',         'label' => 'Fornitori', 'href' => '/fornitori.php'],
                    'codifiche' => ['icon' => 'upc-scan',      'label' => 'Codifiche', 'href' => '/codifiche.php'],
                    'attributi' => ['icon' => 'list-check',    'label' => 'Attributi', 'href' => '/attributi.php'],
                    'report'    => ['icon' => 'graph-up',      'label' => 'Report',    'href' => '/report.php'],
                    'ricerca'   => ['icon' => 'search',         'label' => 'Ricerca',   'href' => '/ricerca.php'],
                ];
                foreach ($navItems as $key => $item):
                    $active = $currentPage === $key ? 'active fw-semibold' : '';
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active ?>" href="<?= $item['href'] ?>">
                        <i class="bi bi-<?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid px-4 py-4">
