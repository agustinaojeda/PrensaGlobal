<?php

use App\Helpers\FechaHelper; ?>
<?= $this->extend('layout/templateHome') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel.css') ?>">
</head>

<body>
    <nav class="navbar navbar-light bg-white border-bottom px-3 navbar-expand-lg sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <a class="navbar-brand fw-bold" href="<?= base_url('panel') ?>">
                Prensa <span class="text-primary">Global</span>
            </a>

            <div class="d-flex align-items-center">
                <ul class="navbar-nav flex-row gap-3 align-items-center">
                    <?php if (session('idRolUsuario') == 1 || session('idRolUsuario') == 3): ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white rounded-pill px-3 d-flex align-items-center gap-2"
                                href="<?= base_url('noticias/crear') ?>"
                                style="background-color: #0d6efd; border: none;">

                                <i class="bi bi-plus-lg fw-bold"></i>

                                <span class="d-none d-md-inline">Crear noticia</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle overflow-hidden bg-light shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <img src="<?= base_url('assets/uploads/perfil/icondefault.webp') ?>" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2" aria-labelledby="userDropdown">
                            <li class="px-3 py-2 border-bottom mb-1">
                                <span class="d-block fw-bold" style="font-size: 0.9rem;"><?= session('nombreUsuario') ?></span>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger py-2" href="<?= base_url('logout'); ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>

        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4">
                <h2>Noticias</h2>
            </div>
        </div>
    </div>
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="d-block d-md-none mb-4">
                <select class="form-select form-select-lg rounded-4 border-0 shadow-sm bg-light"
                    onchange="location = this.value;">
                    <?php if (session('idRolUsuario') != 2): ?>
                        <option value="<?= base_url('panel?estado=0') ?>" <?= $estado_actual == 0 ? 'selected' : '' ?>>Todas las noticias</option>
                    <?php endif; ?>
                    <?php if (session('idRolUsuario') == 2 || session('idRolUsuario') == 3): ?>
                        <option value="<?= base_url('panel?estado=2') ?>" <?= $estado_actual == 2 ? 'selected' : '' ?>>Pendientes para validar</option>
                    <?php endif; ?>

                    <?php if (session('idRolUsuario') == 1 || session('idRolUsuario') == 3): ?>
                        <option value="<?= base_url('panel?estado=1') ?>" <?= $estado_actual == 1 ? 'selected' : '' ?>>Borradores</option>

                        <?php if (session('idRolUsuario') == 1 || session('idRolUsuario') == 3): ?>
                            <option value="<?= base_url('panel?estado=3') ?>" <?= $estado_actual == 3 ? 'selected' : '' ?>>Para corrección</option>
                            <option value="<?= base_url('panel?estado=2') ?>" <?= $estado_actual == 2 ? 'selected' : '' ?>>En validación</option>
                        <?php endif; ?>

                        <option value="<?= base_url('panel?estado=4') ?>" <?= $estado_actual == 4 ? 'selected' : '' ?>>Publicadas</option>
                        <option value="<?= base_url('panel?estado=5') ?>" <?= $estado_actual == 5 ? 'selected' : '' ?>>Anuladas y Expiradas</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="d-none d-md-flex gap-2 mb-4 filtros p-2 rounded-4" style="width: fit-content;">
                <?php if (session('idRolUsuario') != 2): ?>
                    <a href="<?= base_url('panel?estado=0') ?>"
                        class="btn <?= ($estado_actual == 0 || $estado_actual === null) ? 'bg-white shadow-sm fw-bold' : 'text-muted' ?> rounded-3 px-4 border-0">
                        Todas
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('panel?estado=2') ?>"
                    class="btn <?= $estado_actual == 2 ? 'bg-white shadow-sm fw-bold' : 'text-muted' ?> rounded-3 px-4 border-0">
                    Pendientes para validar
                </a>
                <?php if (session('idRolUsuario') == 1 || session('idRolUsuario') == 3): ?>
                    <a href="<?= base_url('panel?estado=1') ?>"
                        class="btn <?= $estado_actual == 1 ? 'bg-white shadow-sm fw-bold' : 'text-muted' ?> rounded-3 px-4 border-0">
                        Borradores
                    </a>
                    <?php if (session('idRolUsuario') == 1 || session('idRolUsuario') == 3): ?>
                        <a href="<?= base_url('panel?estado=3') ?>"
                            class="btn <?= $estado_actual == 3 ? 'bg-white shadow-sm fw-bold' : 'text-muted' ?> rounded-3 px-4 border-0">
                            Para corrección
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('panel?estado=4') ?>"
                        class="btn <?= $estado_actual == 4 ? 'bg-white shadow-sm fw-bold' : 'text-muted' ?> rounded-3 px-4 border-0">
                        Publicadas
                    </a>
                    <a href="<?= base_url('panel?estado=5') ?>"
                        class="btn <?= $estado_actual == 5 ? 'bg-white shadow-sm fw-bold' : 'text-muted' ?> rounded-3 px-4 border-0">
                        Anuladas y Expiradas
                    </a>
                <?php endif; ?>
            </div>

            <div class="d-flex flex-column gap-3">
                <?php if (!empty($noticias)): ?>
                    <?php foreach ($noticias as $n): ?>
                        <div class="card border-0 shadow-sm p-3 rounded-4">
                            <div class="row align-items-center">

                                <div class="col-md-3">
                                    <img src="<?= base_url('assets/uploads/noticias/' . (!empty($n['urlImagenNoticia']) ? $n['urlImagenNoticia'] : 'default.webp')) ?>"
                                        class="img-fluid rounded-3 w-100" style="height: 120px; object-fit: cover;">
                                </div>

                                <div class="col-md-7">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="text-muted small">Creado el <?= FechaHelper::fechaString($n['fechaCreacionNoticia']) ?> por <dark> <?= $n['nombreAutor'] ?></span>
                                    </div>

                                    <h5 class="fw-bold text-dark mb-2"><?= $n['tituloNoticia'] ?></h5>

                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge rounded-pill px-3 <?php if ($n['estadoNoticia'] == 4) echo 'bg-success-subtle text-success';
                                                                                if ($n['estadoNoticia'] == 2 || $n['estadoNoticia'] == 3 || $n['estadoNoticia'] == 1) echo 'bg-warning-subtle text-warning';
                                                                                if ($n['estadoNoticia'] == 5 || $n['estadoNoticia'] == 6) echo 'bg-danger-subtle text-danger '; ?>">
                                            <?php switch ($n['estadoNoticia']) {
                                                case 1:
                                                    echo 'Borrador';
                                                    break;
                                                case 2:
                                                    echo 'Pendiente para validar';
                                                    break;
                                                case 3:
                                                    echo 'Para corrección';
                                                    break;
                                                case 4:
                                                    echo 'Publicada';
                                                    break;
                                                case 6:
                                                    echo 'Anulada';
                                                    break;
                                                case 5:
                                                    echo 'Expirada';
                                                    break;
                                            } ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2 text-end">
                                    <div class="d-flex justify-content-end gap-2">

                                        <?php if ($n['estadoNoticia'] != 2 && $n['estadoNoticia'] != 4 && $n['estadoNoticia'] != 5 && $n['estadoNoticia'] != 6): ?>
                                            <a href="<?= base_url('noticias/editar/' . $n['idNoticia']) ?>" class="btn btn-action"><i class="bi bi-pencil"></i></a>
                                            <a href="javascript:void(0);"
                                                onclick="confirmarAnular(<?= $n['idNoticia'] ?>)" class="btn btn-action text-danger me-3"><i class="bi bi-trash "></i></a>
                                        <?php else: ?>
                                            <a href="<?= base_url('noticias/ver/' . $n['idNoticia']) ?>" class="btn btn-action me-3"><i class="bi bi-eye"></i></a>
                                        <?php endif; ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center p-5 text-muted">
                        <i class="bi bi-folder-x fs-1"></i>
                        <p>Aún no hay noticias en esta sección.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (session()->getFlashdata('mensaje_exito')): ?>
        <div class="alert alert-success alert-dismissible fade show alerta-flotante" role="alert">
            <?= session()->getFlashdata('mensaje_exito') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <script>
        setTimeout(function() {
            var alertElement = document.querySelector('.alerta-flotante');
            if (alertElement) {
                var alert = new bootstrap.Alert(alertElement);
                alert.close();
            }
        }, 3000);

        function confirmarAnular(idNoticia) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "La noticia pasará a estado 'Anulada'. Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, anular',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('noticias/anular/') ?>" + idNoticia;
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->endSection(); ?>