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
    <?php if (isset($totalNoticias) && $totalNoticias > 0): ?>
        <?php $mostradas = 1; ?>
        <div class="container">

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card bg-transparent border-0 rounded-4 overflow-hidden position-relative" style="min-height: 400px;">
                        <a href="<?= base_url('noticias/' . $destacada['idNoticia']) ?>" class="text-decoration-none text-dark h-100 d-flex flex-column">

                            <img src="<?= $destacada['urlImagenNoticia'] ? base_url('assets/uploads/noticias/' . $destacada['urlImagenNoticia']) : base_url('assets/uploads/noticias/default.webp') ?>"
                                class="card-img h-100"
                                style="object-fit: cover; filter: brightness(0.6); position: absolute; top: 0; left: 0; width: 100%;">

                            <div class="card-img-overlay d-flex flex-column justify-content-end p-3 p-md-4 text-white">
                                <span class="badge bg-primary text-white align-self-start mb-2">ÚLTIMO MOMENTO</span>
                                <h1 class="fw-bold display-5"><?= esc($destacada['tituloNoticia']) ?></h1>
                                <div class="d-flex align-items-center mt-1">
                                    <img src="<?= base_url('assets/uploads/perfil/icondefault.webp') ?>" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">

                                    <div class="d-flex flex-column">
                                        <small class="fw-bold"><?= esc($destacada['nombreAutor']) ?></small>
                                        <small class="text-white-50"><?= FechaHelper::fechaString($destacada['fechaPublicacionNoticia']) ?></small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php if ($totalNoticias > 1): ?>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                            <h5 class="fw-bold mb-4"><i class="bi bi-graph-up-arrow me-2"></i>Últimas noticias</h5>

                            <?php foreach ($listaRapida as $n): ?>
                                <a href="<?= base_url('noticias/' . $n['idNoticia']) ?>" class="text-decoration-none text-dark d-flex flex-column">
                                    <div>

                                        <h6 class="fw-bold mb-1"><?= esc($n['tituloNoticia']) ?></h6>
                                        <small class="text-muted"><?= esc($n['nombreAutor']) ?> - </small>
                                        <small class="text-muted"><?= FechaHelper::fechaString($n['fechaPublicacionNoticia']) ?></small>

                                        <hr>
                                    </div>

                                    <?php $mostradas++; ?>
                                <?php endforeach; ?>

                                <a href="#noticias" class="btn btn-outline-secondary rounded-pill mt-auto">Ver más noticias</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <?php if ($mostradas < $totalNoticias): ?>
                <?php $noticiasParaMostrar = array_slice($restoNoticias, 0, 6); ?>
                <h4 id="noticias" class="fw-bold mt-4 mb-3">Todas las <span class="text-primary">noticias</span></h4>

                <div class="row g-4" id="noticias-container">

                </div>
            <?php else: ?>
                <div class="text-center mt-5">
                    <h5 class="text-muted">No hay más noticias para mostrar.</h5>
                </div>
            <?php endif; ?>
            <div class="mb-5"></div>

        </div>
    <?php else: ?>
        <div class="text-center mt-5">
            <i class="bi bi-folder-x fs-1"></i>
            <h5 class="text-muted">No hay noticias publicadas aún.</h5>
        </div>
    <?php endif; ?>

    <script>
        function renderCard(n) {
            let imagenHTML = '';
            if (n.urlImagenNoticia && n.urlImagenNoticia.trim() !== '') {
                imagenHTML = `<img src="<?= base_url('assets/uploads/noticias') ?>/${n.urlImagenNoticia}" 
                      class="card-img-top" style="height: 200px; object-fit: cover;">`;
            }

            return `
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 tarjetaNoticia overflow-hidden">
            <a href="<?= base_url('noticias') ?>/${n.idNoticia}" class="text-decoration-none text-dark h-100 d-flex flex-column">
                ${imagenHTML}
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <small class="" style="color: #394d6d">${n.fechaPublicacionNoticia}</small>
                    <h6 class="fw-bold mt-2 mb-0">${n.tituloNoticia}</h6>
                </div>
            </a>
        </div>
    </div>`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            cargarNoticias();
        });

        let offset = 3;
        let cargando = false;

        function cargarNoticias() {
            if (cargando) return;
            cargando = true;

            fetch(`<?= base_url('noticias/cargarMas') ?>/${offset}`)
                .then(res => res.json())
                .then(data => {
                    let container = document.getElementById('noticias-container');
                    data.forEach(n => {
                        container.insertAdjacentHTML('beforeend', renderCard(n));
                    });

                    offset += data.length;
                    cargando = false;
                });
        }

        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                cargarNoticias();
            }
        });
    </script>

    <?= $this->endSection(); ?>