<?php
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_url .= ($script_name === '/' || $script_name === '\\') ? '' : $script_name;
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i><?php echo htmlspecialchars($title); ?></h5>
    </div>
    <div class="card-body">
        <?php if (isset($error) && $error != ''): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($exito) && $exito != ''): ?>
            <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($exito); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($base_url); ?>/crear_empleado">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de Empleado</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <div class="mb-4">
                <label for="rol" class="form-label">Tipo de Empleado</label>
                <select class="form-select" id="rol" name="rol" required>
                    <option value="empleado" <?php echo (isset($_POST['rol']) && $_POST['rol'] == 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                    <option value="admin" <?php echo (isset($_POST['rol']) && $_POST['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?php echo htmlspecialchars($base_url); ?>/panelprincipal" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Registrar Empleado
                </button>
            </div>
        </form>
    </div>
</div>
