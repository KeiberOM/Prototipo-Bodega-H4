<?php
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_url .= ($script_name === '/' || $script_name === '\\') ? '' : $script_name;

$current_path = str_replace($script_name, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$current_path = trim($current_path, '/');
$current_path = $current_path === '' ? 'panelprincipal' : $current_path;

$userRole = $_SESSION['rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bodega H4 - <?php echo htmlspecialchars($title ?? 'Sistema de Gestión'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($base_url); ?>/assets/css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo htmlspecialchars($base_url); ?>/panelprincipal">
                <i class="fas fa-boxes me-2"></i>Bodega H4
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_path === 'panelprincipal' || $current_path === '') ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/panelprincipal">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>

                    <?php if ($userRole === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo (in_array($current_path, ['consultar', 'agregar', 'editar'])) ? 'active' : ''; ?>" href="#" id="navbarDropdownProducts" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-boxes me-1"></i> Productos
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownProducts">
                                <li><a class="dropdown-item <?php echo $current_path === 'agregar' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/agregar">Añadir Nuevo</a></li>
                                <li><a class="dropdown-item <?php echo $current_path === 'consultar' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/consultar">Inventario</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_path === 'consultar' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/consultar">
                                <i class="fas fa-boxes me-1"></i> Inventario
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo (in_array($current_path, ['mover', 'movimientos'])) ? 'active' : ''; ?>" href="#" id="navbarDropdownMovements" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-exchange-alt me-1"></i> Movimientos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMovements">
                            <li><a class="dropdown-item <?php echo $current_path === 'mover' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/mover">Registrar</a></li>
                            <li><a class="dropdown-item <?php echo $current_path === 'movimientos' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/movimientos">Historial</a></li>
                        </ul>
                    </li>

                    <?php if ($userRole === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo (strpos($current_path, 'reportes') !== false) ? 'active' : ''; ?>" href="#" id="navbarDropdownReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-bar me-1"></i> Reportes
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                                <li><a class="dropdown-item <?php echo $current_path === 'reportes' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/reportes">Stock</a></li>
                                <li><a class="dropdown-item <?php echo $current_path === 'reportes/graficos' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/reportes/graficos">Gráficos</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if ($userRole === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_path === 'crear_empleado' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($base_url); ?>/crear_empleado">
                                <i class="fas fa-user-plus me-1"></i> Registrar Empleado
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Invitado'); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo htmlspecialchars($base_url); ?>/cerrarsesion">
                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <?php
        // Asegúrate de que $flashMessages esté definido, si no, inicialízalo como un array vacío
        $flashMessages = $flashMessages ?? [];
        if (!empty($flashMessages)):
            foreach ($flashMessages as $type => $message):
                $alertClass = 'alert-info';
                if ($type === 'exito') $alertClass = 'alert-success';
                if ($type === 'error') $alertClass = 'alert-danger';
                if ($type === 'advertencia') $alertClass = 'alert-warning';
        ?>
                <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
        <?php
            endforeach;
        endif;
        ?>
