<?php
// Obtener la ruta base para los enlaces
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_url .= ($script_name === '/' || $script_name === '\\') ? '' : $script_name;
?>

<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Productos con Stock Bajo</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($productos_bajo_stock)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_bajo_stock as $producto): ?>
                            <tr class="table-warning">
                                <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['ubicacion']); ?></td>
                                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($producto['stock_minimo']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle me-2"></i> No hay productos con stock bajo.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-times-circle me-2"></i>Productos Agotados</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($productos_agotados)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Stock Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_agotados as $producto): ?>
                            <tr class="table-danger">
                                <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['ubicacion']); ?></td>
                                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle me-2"></i> No hay productos agotados.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Productos con Stock Suficiente</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($productos_ok)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_ok as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['ubicacion']); ?></td>
                                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($producto['stock_minimo']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> No hay productos con stock suficiente.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Generar Reportes Documentales</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <a href="<?php echo htmlspecialchars($base_url); ?>/reportes/pdf" class="btn btn-danger w-100">
                    <i class="fas fa-file-pdf me-2"></i> Generar Reporte de Inventario (PDF)
                </a>
            </div>
            <div class="col-md-6 mb-3">
                <a href="<?php echo htmlspecialchars($base_url); ?>/reportes/excel" class="btn btn-success w-100">
                    <i class="fas fa-file-excel me-2"></i> Generar Reporte de Stock Agotado (Excel)
                </a>
            </div>
        </div>
        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success mt-3" role="alert"><?php echo htmlspecialchars($_GET['exito']); ?></div>
        <?php endif; ?>
    </div>
</div>
