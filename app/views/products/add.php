<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Agregar nuevo producto</h5>
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="codigo" class="form-label">Código*</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" 
                           required value="<?= htmlspecialchars($_POST['codigo'] ?? '') ?>">
                </div>
                
                <div class="col-md-8">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" 
                          rows="2"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" class="form-control" id="categoria" name="categoria"
                           value="<?= htmlspecialchars($_POST['categoria'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="ubicacion" class="form-label">Ubicación*</label>
                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" 
                           required value="<?= htmlspecialchars($_POST['ubicacion'] ?? '') ?>">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="cantidad" class="form-label">Cantidad inicial*</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" 
                           min="0" required value="<?= htmlspecialchars($_POST['cantidad'] ?? '0') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="stock_minimo" class="form-label">Stock mínimo*</label>
                    <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" 
                           min="0" required value="<?= htmlspecialchars($_POST['stock_minimo'] ?? '5') ?>">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="consultar" class="btn btn-secondary me-md-2">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar producto
                </button>
            </div>
        </form>
    </div>
</div>
