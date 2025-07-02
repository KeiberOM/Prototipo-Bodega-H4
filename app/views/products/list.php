<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Inventario de Productos</h5>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <a href="<?php echo htmlspecialchars($base_url); ?>/agregar" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Producto
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($exito)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($exito); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Ubicación</th>
                        <th>Cantidad</th>
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="<?php echo (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') ? 7 : 6; ?>" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p>No hay productos registrados</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($row['categoria']); ?></td>
                            <td><?php echo htmlspecialchars($row['ubicacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= htmlspecialchars($base_url) ?>/editar?id=<?= $row['id'] ?>" 
                                           class="btn btn-warning"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= htmlspecialchars($base_url) ?>/confirmar_eliminar?id=<?= $row['id'] ?>" 
                                           class="btn btn-danger"
                                           title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
