<div class="card border-danger">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar eliminación</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <p class="mb-0">¿Está seguro que desea eliminar el producto <strong><?= htmlspecialchars($producto['nombre']) ?></strong> (código: <?= htmlspecialchars($producto['codigo']) ?>)?</p>
            <p class="mb-0 mt-2">Esta acción eliminará también todos los movimientos asociados y no se puede deshacer.</p>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="consultar" class="btn btn-secondary me-md-2">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
            <a href="eliminar?id=<?= $producto['id'] ?>&csrf_token=<?= htmlspecialchars($csrf_token) ?>" 
               class="btn btn-danger">
                <i class="fas fa-trash-alt me-1"></i> Confirmar eliminación
            </a>
        </div>
    </div>
</div>
