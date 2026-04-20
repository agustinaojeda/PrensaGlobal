<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg form-signin">
    <div class="card-body p-5">
        <h1 class="fs-4 card-title fw-bold mb-4">Restablecer la contraseña</h1>
        <form method="POST" action="<?= base_url('password/resetForm') ?>" autocomplete="off">

            <?= csrf_field() ?>

            <input type="hidden" name="token" value="<?= $token ?>">

            <div class="mb-3">
                <label for="password">Nueva contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required autofocus>
                <?php if (session('errors.password')): ?>
                    <div class="text-danger small   mt-1">
                        <?= session('errors.password') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="repassword">Confirmar Contraseña</label>
                <input type="password" class="form-control" name="repassword" id="repassword" required>
                <?php if (session('errors.repassword')): ?>
                    <div class="text-danger small   mt-1">
                        <?= session('errors.repassword') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary ms-auto">
                    Restablecer contraseña
                </button>
            </div>
        </form>


    </div>
</div>
<?= $this->endSection(); ?>