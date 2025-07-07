<?php
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_url .= ($script_name === '/' || $script_name === '\\') ? '' : $script_name;
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i><?php echo htmlspecialchars($title); ?></h5>
    </div>
    <div class="card-body">
        <?php if (isset($error) && $error != ''): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($base_url); ?>/mover">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <?php if ($producto): ?>
                <input type="hidden" name="producto_id" value="<?php echo htmlspecialchars($producto['id']); ?>">

                <div class="mb-3">
                    <label class="form-label">Producto:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Actual:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" readonly>
                </div>
            <?php else: ?>
                <div class="mb-3">
                    <label for="producto_id" class="form-label">Producto:</label>
                    <select id="producto_id" name="producto_id" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($all_products as $row): ?>
                            <option value='<?php echo htmlspecialchars($row['id']); ?>'><?php echo htmlspecialchars($row['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Movimiento:</label>
                <select id="tipo" name="tipo" class="form-select" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" required>
            </div>

            <div class="mb-4">
                <label for="motivo" class="form-label">Motivo:</label>
                <input type="text" id="motivo" name="motivo" class="form-control" required>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?php echo htmlspecialchars($base_url); ?>/consultar" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Registrar Movimiento
                </button>
            </div>
        </form>
    </div>
</div>
