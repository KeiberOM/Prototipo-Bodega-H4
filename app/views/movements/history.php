<?php
// Obtener la ruta base para los enlaces
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_url .= ($script_name === '/' || $script_name === '\\') ? '' : $script_name;
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Movimientos</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo htmlspecialchars($base_url); ?>/movimientos" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="tipo" class="form-label">Filtrar por tipo:</label>
                    <select id="tipo" name="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="entrada" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'entrada') ? 'selected' : ''; ?>>Entradas</option>
                        <option value="salida" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'salida') ? 'selected' : ''; ?>>Salidas</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="producto" class="form-label">Filtrar por producto:</label>
                    <select id="producto" name="producto" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($all_products as $row): ?>
                            <?php $selected = (isset($_GET['producto']) && $_GET['producto'] == $row['id']) ? 'selected' : ''; ?>
                            <option value='<?php echo htmlspecialchars($row['id']); ?>' <?php echo $selected; ?>><?php echo htmlspecialchars($row['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    <a href="<?php echo htmlspecialchars($base_url); ?>/movimientos" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
        
        <?php if (!empty($movimientos)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimientos as $movimiento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($movimiento['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($movimiento['producto']); ?></td>
                                <td>
                                    <span class="badge <?php echo ($movimiento['tipo'] == 'entrada') ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars(ucfirst($movimiento['tipo'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($movimiento['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($movimiento['motivo']); ?></td>
                                <td><?php echo htmlspecialchars($movimiento['usuario']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No hay movimientos registrados.
            </div>
        <?php endif; ?>
    </div>
</div>
