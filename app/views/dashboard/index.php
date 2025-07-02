<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card bg-success text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-box-open me-2"></i>Productos en Stock</h5>
                <h2 class='mb-0'><?php echo htmlspecialchars($products_in_stock_count); ?></h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card bg-warning text-dark">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-exclamation-triangle me-2"></i>Productos Agotados</h5>
                <h2 class='mb-0'><?php echo htmlspecialchars($out_of_stock_products_count); ?></h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card bg-info text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-exchange-alt me-2"></i>Movimientos Hoy</h5>
                <h2 class='mb-0'><?php echo htmlspecialchars($today_movements_count); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Actividad Reciente</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_movements)): ?>
                        <?php foreach ($recent_movements as $row): ?>
                            <?php 
                            // Determinar la clase del badge según el tipo de movimiento
                            $badge_class = ($row['tipo'] ?? '') == 'entrada' ? 'bg-success' : 'bg-danger';
                            
                            // Manejo seguro de valores posibles no definidos
                            $fecha = $row['fecha'] ?? 'Fecha no disponible';
                            $product_name = $row['nombre_producto'] ?? $row['nombre'] ?? 'Producto desconocido';
                            $tipo = $row['tipo'] ?? 'indefinido';
                            $cantidad = $row['cantidad'] ?? 0;
                            $usuario = $row['usuario_id'] ?? $row['usuario'] ?? 'Sistema'; // Asegúrate de que 'usuario' es el campo correcto
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fecha); ?></td>
                                <td><?php echo htmlspecialchars($product_name); ?></td>
                                <td><span class='badge <?php echo $badge_class; ?>'><?php echo ucfirst(htmlspecialchars($tipo)); ?></span></td>
                                <td><?php echo htmlspecialchars($cantidad); ?></td>
                                <td><?php echo htmlspecialchars($usuario); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay actividad reciente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
