<?php

use App\Helpers\FechaHelper; ?>
<?= $this->extend('layout/templateHome') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-white border-bottom px-3 navbar-expand-lg sticky-top mb-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <a class="navbar-brand fw-bold" href="<?= base_url('index') ?>">
                Prensa <span class="text-primary">Global</span>
            </a>

            <a href="<?= base_url('login') ?>" class="btn btn-outline-primary">
                Iniciar sesión
            </a>

        </div>
    </nav>
    <div class="container py-2 bg-white rounded-3">
        <div class="row justify-content-center">
            <div class="col-lg-8 ">
                <header class="mb-4 text-center mt-4">
                    <h1 class="fw-bold mb-3"><?= esc($noticia['tituloNoticia']) ?></h1>
                    <div class="text-muted small">
                        <span class="me-3"><i class="bi bi-person-fill"></i> Por: <strong><?= esc($autor ?? 'Redacción') ?></strong></span>
                        <span><i class="bi bi-calendar3"></i> <?= date('d/m/Y', strtotime($noticia['fechaCreacionNoticia'])) ?></span>
                    </div>
                </header>

                <?php if ($noticia['urlImagenNoticia']): ?>
                    <div class="mb-5">
                        <img src="<?= base_url('assets/uploads/noticias/' . $noticia['urlImagenNoticia']) ?>"
                            alt="Portada"
                            class="img-fluid rounded-4 shadow-sm w-100"
                            style="max-height: 400px; object-fit: cover;">
                    </div>
                <?php endif; ?>

                <article class="fs-5 text-dark" style="line-height: 1.8;">
                    <?= $noticia['descripcionNoticia'] ?>
                </article>
                <hr class="my-5">

                <?php if (isset($relacionadas) && count($relacionadas) > 0): ?>
                    <div class="container mb-5">
                        <h4 class="fw-bold mb-4">Quizás te interese leer...</h4>
                        <div class="row g-4">
                            <?php foreach ($relacionadas as $rel): ?>
                                <div class="col-md-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-shadow transition-all">
                                        <a href="<?= base_url('noticias/' . $rel['idNoticia']) ?>" class="text-decoration-none text-dark">
                                            <img src="<?= $rel['urlImagenNoticia'] ? base_url('assets/uploads/noticias/' .  $rel['urlImagenNoticia']) : base_url('assets/uploads/noticias/default.webp') ?>"
                                                class="card-img-top" style="height: 180px; object-fit: cover;">
                                            <div class="card-body">
                                                <h6 class="fw-bold"><?= esc($rel['tituloNoticia']) ?></h6>
                                                <small class="text-muted"><?= FechaHelper::fechaString($rel['fechaPublicacionNoticia']) ?></small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?= $this->endSection() ?>