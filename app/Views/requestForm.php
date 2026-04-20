<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg form-signin">
    <div class="card-body p-5">
        <h1 class="fs-4 card-title fw-bold mb-4">¿Has olvidado tu contraseña?</h1>
        <form method="POST" action="<?= base_url('password-email') ?>" autocomplete="off">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="email" class="mb-2">Ingresa tu email</label>
                <input type="email" class="form-control" name="email" id="email" required autofocus>
            </div>

            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary ms-auto">
                    Enviar enlace
                </button>
            </div>
        </form>
        <?php if (session('error_request')): ?>
                <div class="text-danger small mb-4">
                    <?= session('error_request') ?>
                </div>
            <?php endif; ?>
    </div>

    <div class="card-footer py-3 border-0">
        <div class="text-center">
            <a href="<?= base_url('login') ?>">Iniciar sesión</a>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>