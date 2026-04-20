<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg form-signin">
    <div class="card-body p-5">
        <a href="<?=  base_url('index') ?>" class="text-decoration-none text-black">
            <h2 class="text-center fs-3 fw-bold mb-4">Prensa <span class="text-primary">Global</span></h2>
        </a>
        <h1 class="fs-4 card-title fw-bold mb-3">Iniciar sesión</h1>

        <form method="POST" action="<?= base_url('auth') ?>" autocomplete="off">

            <?= csrf_field(); ?>

            <div class="mb-3">
                <label class="mb-2" for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" required autofocus>
            </div>

            <div class="mb-3">
                <label class="mb-2" for="password">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required>
                <a href="<?= base_url('password-request') ?>" class="small">
                    Olvide mi contraseña
                </a>
            </div>

            <?php if (session('error_login')): ?>
                <div class="text-danger small mb-4">
                    <?= session('error_login') ?>
                </div>
            <?php endif; ?>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    Ingresar
                </button>
            </div>
        </form>

    </div>
    <div class="card-footer py-3 border-0">
        <div class="text-center">
            ¿No tienes una cuenta? <a href="<?= base_url('registro') ?>" class="text-dark">Registrate aquí</a>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>