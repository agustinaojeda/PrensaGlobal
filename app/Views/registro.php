<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg form-signin">
    <div class="card-body p-5">
        <h1 class="fs-4 card-title fw-bold mb-4">Registro</h1>
        <form method="POST" action="<?= base_url('registro') ?>" autocomplete="off">

            <?= csrf_field(); ?>

            <div class="mb-3">
                <label class="mb-2" for="name">Nombre y Apellido</label>
                <input type="text" class="form-control" name="name" id="name" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="El nombre solo debe contener letras y espacios" value="<?= set_value('name') ?>" required autofocus>
                <?php if (session('errors.name')): ?>
                    <div class="text-danger small   mt-1">
                        <?= session('errors.name') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="mb-2" for="email">Correo electrónico</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= set_value('email') ?>" required>
                <?php if (session('errors.email')): ?>
                    <div class="text-danger small   mt-1">
                        <?= session('errors.email') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required>
                <?php if (session('errors.password')): ?>
                    <div class="text-danger small   mt-1">
                        <?= session('errors.password') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="repassword">Confirmar contraseña</label>
                <input type="password" class="form-control" name="repassword" id="repassword" required>
                <?php if (session('errors.repassword')): ?>
                    <div class="text-danger small   mt-1">
                        <?= session('errors.repassword') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-5">
                <label class="mb-2" for="rol">Rol</label>
                <select class="form-select" name="rol" id="rol" required>
                    <option value="" selected disabled>Selecciona un rol</option>
                    <option value="1">Editor</option>
                    <option value="2">Validador</option>
                    <option value="3">Editor y Validador</option>
                </select>
                <?php if (session('errors.rol')): ?>
                    <div class="text-danger small   mt-1">
                        <?= session('errors.rol') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    Registrarse
                </button>
            </div>
        </form>

    </div>
    <div class="card-footer py-3 border-0">
        <div class="text-center">
            <a href="<?= base_url('login') ?>">Iniciar sesión</a>
        </div>
    </div>

    <?= $this->endSection(); ?>