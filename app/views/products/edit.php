<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Producto: <?php echo htmlspecialchars($producto['nombre']); ?></h5>
    </div>
    <div class="card-body">
        <?php if (isset($error) && $error != ''): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="editar?id=<?php echo htmlspecialchars($producto['id']); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" name="codigo"
                           value="<?php echo htmlspecialchars($producto['codigo']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                           value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                </div>
                <div class="col-12">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" class="form-control" id="categoria" name="categoria"
                           value="<?php echo htmlspecialchars($producto['categoria']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="ubicacion" class="form-label">Ubicación</label>
                    <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                           value="<?php echo htmlspecialchars($producto['ubicacion']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                    <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" min="0"
                           value="<?php echo htmlspecialchars($producto['stock_minimo']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cantidad Actual</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" readonly>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="consultar" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
