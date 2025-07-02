<?php
// Obtener la ruta base para los enlaces
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_url .= ($script_name === '/' || $script_name === '\\') ? '' : $script_name;
?>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
    </div>
    <div class="card-body text-center">
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">¡Advertencia!</h4>
            <p>¿Está seguro que desea eliminar el siguiente producto?</p>
            <p class="mb-0">Esta acción es irreversible y también eliminará todos los movimientos asociados a este producto.</p>
        </div>

        <div class="card mb-4 text-start">
            <div class="card-body">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($producto['nombre']); ?></p>
                <p><strong>Código:</strong> <?php echo htmlspecialchars($producto['codigo']); ?></p>
                <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($producto['ubicacion']); ?></p>
                <p><strong>Stock actual:</strong> <?php echo htmlspecialchars($producto['cantidad']); ?></p>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <a href="<?php echo htmlspecialchars($base_url); ?>/consultar" class="btn btn-secondary me-3">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>/eliminar?id=<?php echo htmlspecialchars($producto['id']); ?>&csrf_token=<?php echo htmlspecialchars($csrf_token); ?>" class="btn btn-danger">
                <i class="fas fa-trash-alt me-1"></i> Sí, eliminar
            </a>
        </div>
    </div>
</div>
