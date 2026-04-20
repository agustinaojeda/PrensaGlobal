<?= $this->extend('layout/templateHome') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-white border-bottom px-3 navbar-expand-lg sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <a class="navbar-brand fw-bold" href="<?= base_url('panel') ?>">
                Prensa <span class="text-primary">Global</span>
            </a>

            <div class="d-flex align-items-center">
                <ul class="navbar-nav flex-row gap-3 align-items-center">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle overflow-hidden bg-light shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <?php if (session('fotoPerfil')): ?>
                                    <img src="<?= base_url('uploads/perfiles/' . session('fotoPerfil')) ?>" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?= base_url('assets/uploads/perfil/icondefault.webp') ?>" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
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
    <div class="container mt-2">
        <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('panel') ?>" style="text-decoration: none;">Panel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ver noticia</li>
            </ol>
        </nav>
    </div>
    <div class="container py-2">
        <div class="row justify-content-center">
            <div class="col-lg-8 ">
                <header class="mb-4 text-center">
                    <h1 class="fw-bold mb-3"><?= esc($noticia['tituloNoticia']) ?></h1>
                    <div class="text-muted small">
                        <span class="me-3"><i class="bi bi-person-fill"></i> Por: <strong><?= esc($noticia['nombreAutor'] ?? 'Redacción') ?></strong></span>
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
                <?php
                $rol = session('idRolUsuario');
                $estado = $noticia['estadoNoticia'];
                $esAutor = ($noticia['idAutorNoticia'] == session('idUsuario'));

                $mostrarVolver = ($rol == 1 && in_array($estado, [2, 4, 5, 6])) ||
                    ($rol == 3 && in_array($estado, [4, 5, 6])) ||
                    ($estado == 2 && $esAutor);

                $mostrarAcciones = in_array($rol, [2, 3]) && $estado == 2 && !$esAutor;
                ?>

                <div class="text-end mt-lg-4 mt-sm-md-3 mb-4">
                    <?php if ($mostrarVolver): ?>
                        <button class="btn btn-primary" onclick="window.location.href='<?= base_url('panel') ?>'">
                            Volver al panel
                        </button>
                    <?php endif; ?>

                    <?php if ($mostrarAcciones): ?>
                        <a href="<?= base_url('noticias/publicar/' . $noticia['idNoticia']) ?>" class="btn btn-primary me-3">
                            Publicar noticia
                        </a>
                        <button type="button" class="btn btn-secondary" onclick="pedirCorreccion(<?= $noticia['idNoticia'] ?>)">
                            Solicitar corrección
                        </button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <?php if (!empty($historial)): ?>
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 bg-light">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3">Historial de cambios</h6>
                            <div class="small" style="max-height: 250px; overflow-y: auto;">
                                <?php foreach ($historial as $item): ?>
                                    <div class="mb-3 border-start ps-3 border-2 border-primary-subtle">
                                        <strong><?php switch ($item['estadoFinalHistorial']) {
                                                    case 1:
                                                        echo 'Borrador';
                                                        break;
                                                    case 2:
                                                        echo 'Para revisión';
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
                                                    case 7:
                                                        echo 'Creada';
                                                        break;
                                                    default:
                                                        echo 'Cambio realizado';
                                                } ?></strong><br>
                                        <span class="text-muted">
                                            Por: <strong><?= esc($item['nombreEditor'] ?? 'Desconocido') ?></strong>
                                            - <?= date('d/m/y H:i', strtotime($item['fechaHistorial'])) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script>
        function pedirCorreccion(id) {
            Swal.fire({
                title: 'Motivo de la corrección',
                input: 'textarea',
                inputLabel: '¿Qué debe corregir el autor?',
                inputPlaceholder: 'Ej: Falta profundizar en el segundo párrafo...',
                inputAttributes: {
                    'maxlength': 500
                },
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                preConfirm: (texto) => {
                    if (!texto.trim()) {
                        Swal.showValidationMessage('El comentario es obligatorio');
                    }
                    return texto;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('noticias/corregir/') ?>" + id + "?comentario=" + encodeURIComponent(result.value);
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->endSection(); ?>