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
                <li class="breadcrumb-item active" aria-current="page">Nueva noticia</li>
            </ol>
        </nav>
    </div>
    <div class="container">
        <h2><?= isset($noticia) ? 'Editar Noticia' : 'Crear Noticia' ?></h2>
    </div>
    <form action="<?= base_url('noticias/guardar') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if (isset($noticia)): ?>
            <input type="hidden" name="idNoticia" value="<?= $noticia['idNoticia'] ?>">
        <?php endif; ?>

        <div class="container py-4 formularioCrearNoticia mt-3">

            <div class="row ">

                <div class="col-lg-8">

                    <input type="text" name="titulo" class="form-control border-0 fs-3 mb-2" placeholder="Título" maxlength="100" value="<?= old('titulo', isset($noticia) ? esc($noticia['tituloNoticia']) : '') ?>" required>
                    <?php if (session('errors.titulo')): ?>
                        <div class="text-danger small  mb-2">
                            <?= session('errors.titulo') ?>
                        </div>
                    <?php endif; ?>

                    <div id="editor-container"></div>
                    <div id="counter" class="text-muted small m-2"></div>
                    <input type="hidden" name="contenido" id="contenido-input" value="<?= old('contenido') ?>">
                    <?php if (session('errors.contenido')): ?>
                        <div class="text-danger small   mt-1">
                            <?= session('errors.contenido') ?>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="position-fixed top-5 end-0 p-3" style="z-index: 1050;">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm alerta-flotante" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">
                    <?php if (isset($noticia)): ?>
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 historial">

                            <h6 class="fw-bold text-uppercase small text-muted mb-3">Historial de cambios</h6>

                            <?php if ($noticia['estadoNoticia'] == 3 && !empty($noticia['ultimoComentario'])): ?>
                                <div class="alert alert-warning border-0 small mb-3">
                                    <p class="mb-1 fw-bold">Observación de corrección:</p>
                                    <?= esc($noticia['ultimoComentario']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="small text-muted" style="max-height: 200px; overflow-y: auto;">
                                <?php if (!empty($historial)): ?>
                                    <?php foreach ($historial as $item): ?>
                                        <div class="mb-3 border-start ps-3 border-2 border-primary-subtle">
                                            <div class="d-flex justify-content-between">
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
                                                            case 5:
                                                                echo 'Anulada';
                                                                break;
                                                            case 6:
                                                                echo 'Expirada';
                                                                break;
                                                            case 7:
                                                                echo 'Creada';
                                                                break;
                                                            default:
                                                                echo 'Cambio realizado';
                                                        } ?></strong>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.85rem;">
                                                <span>Por: <strong><?= esc($item['nombreEditor'] ?? 'Desconocido') ?></strong></span><br>
                                                <small><i class="bi bi-clock me-1"></i> <?= date('d/m/y H:i', strtotime($item['fechaHistorial'])) ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No hay cambios registrados.</p>
                                <?php endif; ?>

                            </div>

                        </div>
                    <?php endif; ?>

                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" id="drop-zone">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3">Subir portada</h6>
                        <?php if (isset($noticia)): ?>
                            <input type="hidden" name="urlImagenActual" value="<?= $noticia['urlImagenNoticia'] ?>">
                            <input type="hidden" name="imagenBorrada" id="imagenBorrada" value="0">
                        <?php endif; ?>

                        <input type="file" name="fotoPortada" id="file-input" accept="image/jpeg,image/png" style="display: none;">

                        <div id="preview-container" class="text-center py-5 border-dashed rounded-2 position-relative" style="cursor: pointer; min-height: 200px;">

                            <div id="drop-zone-prompt">
                                <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                                <p class=" mb-1 fw-bold text-dark">Pulsa y arrastra la imagen de portada</p>
                                <p class="small text-muted mb-0">Opcional. Tamaño máximo: 2mb. Formato permitido: PNG, JPG.</p>
                            </div>

                            <img src="<?= isset($noticia['urlImagenNoticia']) ? base_url('assets/uploads/noticias/' . $noticia['urlImagenNoticia']) : '' ?>" id="image-preview" class="img-fluid rounded-3 <?= isset($noticia['urlImagenNoticia']) ? '' : 'd-none' ?>" style="max-height: 250px; object-fit: cover; width: 100%;">

                            <button type="button" id="remove-image" class="btn btn-danger btn-sm rounded-circle position-absolute d-none" style="top: 10px; right: 10px; z-index: 10;">
                                <i class="bi bi-x"></i>
                            </button>
                            <?php if (session('errors.fotoPortada')): ?>
                                <div class="text-danger small   mt-1">
                                    <?= session('errors.fotoPortada') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-grid gap-2">

                        <button type="submit" name="accion" value="borrador" class="btn btn-outline-secondary rounded-pill">Guardar borrador</button>
                        <?php if (session('idRolUsuario') == 1 || session('idRolUsuario') == 3): ?>
                            <button type="submit" name="accion" value="validacion" class="btn btn-primary rounded-pill">Enviar a validación</button>
                        <?php else: ?>
                            <button type="submit" name="accion" value="publicar" class="btn btn-primary rounded-pill">Publicar noticia</button>
                        <?php endif; ?>


                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const imagePreview = document.getElementById('image-preview');
            const dropZonePrompt = document.getElementById('drop-zone-prompt');
            const removeBtn = document.getElementById('remove-image');

            if (imagePreview.src && imagePreview.src !== window.location.href) {
                imagePreview.classList.remove('d-none');
                dropZonePrompt.classList.add('d-none');
                removeBtn.classList.remove('d-none');
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('drop-zone');
            const previewContainer = document.getElementById('preview-container');
            const fileInput = document.getElementById('file-input');
            const prompt = document.getElementById('drop-zone-prompt');
            const previewImage = document.getElementById('image-preview');
            const removeBtn = document.getElementById('remove-image');

            previewContainer.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
            });

            dropZone.addEventListener('drop', function(e) {
                if (!previewImage.classList.contains('d-none')) {
                    alert("Ya hay una imagen seleccionada. Elimínala primero para cambiarla.");
                    return;
                }
                let dt = e.dataTransfer;
                let files = dt.files;
                handleFiles(files);
            });

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];

                    if (file.size > 2 * 1024 * 1024) { //tamaño max 2mb
                        alert('La imagen es muy pesada. Máximo 2MB.');
                        return;
                    }

                    const formatosValidos = ['image/jpeg', 'image/png'];
                    if (!formatosValidos.includes(file.type)) {
                        alert('Formato no permitido. Solo puedes subir imágenes JPG o PNG.');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.classList.remove('d-none');
                        prompt.classList.add('d-none');
                        removeBtn.classList.remove('d-none');

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                    };
                    reader.readAsDataURL(file);
                }
            }

            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();

                fileInput.value = '';
                previewImage.src = '';
                previewImage.classList.add('d-none');
                prompt.classList.remove('d-none');
                removeBtn.classList.add('d-none');
                document.getElementById('imagenBorrada').value = "1";
            });
        });


        const Counter = function(quill, options) {
            this.quill = quill;
            this.options = options;
            this.container = document.querySelector(options.container);
            this.quill.on('text-change', this.update.bind(this));
            this.update();
        };
        Counter.prototype.update = function() {
            let length = this.quill.getLength();
            let label = this.options.unit || 'characters';
            this.container.innerText = (length - 1) + ' / ' + this.options.max + ' ' + label;
        };
        Quill.register('modules/counter', Counter);

        const quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Empieza a redactar la noticia aquí...',
            maxLength: 5000,
            modules: {
                counter: {
                    container: '#counter',
                    max: 5000,
                    unit: 'caracteres'
                },
                toolbar: [
                    ['bold', 'italic', 'underline', 'link'],

                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }]
                ]
            }
        });

        var contenidoPrevio = document.getElementById('contenido-input').value;
        if (contenidoPrevio) {
            quill.root.innerHTML = contenidoPrevio;
        }

        <?php if (isset($noticia)): ?>
            quill.root.innerHTML = '<?= addslashes($noticia['descripcionNoticia']) ?>';
        <?php endif; ?>

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('contenido-input').value = quill.root.innerHTML;
            if (quill.getLength() > 5001) {
                quill.deleteText(5000, quill.getLength());
            }
        });

        document.querySelector('form').onsubmit = function() {
            let contenidoHTML = quill.root.innerHTML;

            let textoLimpio = quill.getText().trim();

            if (textoLimpio.length === 0) {
                alert('Por favor, escribe algo en la noticia.');
                return false;
            }

            document.querySelector('#contenido-input').value = contenidoHTML;
        };
    </script>
    <?= $this->endSection(); ?>