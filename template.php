 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <!-- CSS de AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="Diseños/estilos_contenedorReportes.css">
 </head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">La Tienda de Don Fabrizio</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="clientes.php" class="nav-link <?php echo ($pagina == 'clientes') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="productos.php" class="nav-link <?php echo ($pagina == 'productos') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Productos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="generarcomprobante.php" class="nav-link <?php echo ($pagina == 'comprobantes') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-plus-circle"></i>
                            <p>Nueva Venta</p>
                        </a>
                    </li>
                         <!-- "Reportes" -->
                    <li class="nav-item has-treeview <?php echo ($pagina == 'listar_boletas' || $pagina == 'listar_facturas') ? 'menu-open' : ''; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                Reportes
                                <i class="right fas fa-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="listar_boletas.php" class="nav-link <?php echo ($pagina == 'listar_boletas') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-receipt"></i>
                                    <p>Ver Boletas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="listar_facturas.php" class="nav-link <?php echo ($pagina == 'listar_facturas') ? 'active' : ''; ?>">
                                    <i class="nav-icon  fas fa-file-invoice"></i>
                                    <p>Ver Facturas</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Contenido -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <?php 
                echo $contenido; ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center" >
        <strong>Copyright &copy; 2024 <a href="#">La Tienda de Don Fabrizio</a>.</strong> Todos los derechos reservados.
    </footer>
</div>

<!-- JS de AdminLTE -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="./Diseños/script_contenedorReportes.js"></script>
</body>
</html>

<?php
 
?>