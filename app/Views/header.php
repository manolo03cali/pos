<?php
//Traemos los datos de mi session para poder asignarla header en los iconos apropiados
$user_session = session();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tienda POS</title>
    <!-- Bootstrap CSS (necesario para cargar los estilos al inicio) -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" />
    <!-- Estilos personalizados -->
    <link href="<?php echo base_url(); ?>css/style.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/styles.css" rel="stylesheet" />
    <!-- jQuery UI CSS -->
    <link href="<?php echo base_url(); ?>js/jquery-ui-1.13.3/jquery-ui.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link href="<?php echo base_url(); ?>css/sweetalert2.min.css" rel="stylesheet" />
    <!-- jQuery (debe cargarse antes que jQuery UI) -->
    <script src="<?php echo base_url(); ?>js/jquery.min.js" d></script>
     <!-- jQuery UI JS (después de jQuery) -->
  <script src="<?php echo base_url(); ?>js/jquery-ui-1.13.3/jquery-ui.min.js" ></script>
       <!-- FontAwesome -->
 <script src="<?php echo base_url(); ?>js/all.js" ></script>
  
   
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="<?php echo base_url() ?>inicio">Punto de Venta PHP</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <!-- Con echo $user_session->nombre; traemos el nombre del usuario que inicio sesion y lo asociamos en el simbolo de usuario-->
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $user_session->nombre; ?><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?php echo base_url(); ?>usuarios/cambia_password">Cambiar contraseña</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="<?php echo base_url(); ?>usuarios/logout">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- Menu productos -->
                        <?php if (isset($tienePermisoMenuProductos) && $tienePermisoMenuProductos): ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProductos" aria-expanded="false" aria-controls="collapseProductos">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-basket-shopping"></i></div>
                                Productos
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                        <?php endif; ?>
                        <div class="collapse" id="collapseProductos" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if (isset($tienePermisoUnidadesCatalogo) && $tienePermisoUnidadesCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>unidades">Unidades</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoCategoriasCatalogo) && $tienePermisoCategoriasCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>categorias">Categorías</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoProductosCatalogo) && $tienePermisoProductosCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>productos">Productos</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <!-- Fin menu productos -->
                        <!-- Menu clientes -->
                        <?php if (isset($tienePermisoMenuClientes) && $tienePermisoMenuClientes): ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseClientes" aria-expanded="false" aria-controls="collapseClientes">
                                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-user-friends"></i></div>
                                Clientes
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                        <?php endif; ?>
                        <div class="collapse" id="collapseClientes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if (isset($tienePermisoClientesCatalogo) && $tienePermisoClientesCatalogo): ?>
                                    <!-- Traemos la url del sistema y la concatenamos con la vista unidades para que la muestre en el menu Unidades-->
                                    <a class="nav-link" href="<?php echo base_url(); ?>clientes">Clientes</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <!--  fin Menu clientes -->
                        <!-- Menu compras -->
                        <?php if (isset($tienePermisoMenuCompras) && $tienePermisoMenuCompras): ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCompras" aria-expanded="false" aria-controls="collapseCompras">
                                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-truck"></i></div>
                                Compras
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                        <?php endif; ?>
                        <div class="collapse" id="collapseCompras" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <!-- Traemos la url del sistema y la concatenamos con la vista unidades para que la muestre en el menu Unidades-->
                                <?php if (isset($tienePermisoComprasAgregar) && $tienePermisoComprasAgregar): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>compras/nuevo">Nueva compra</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoComprasCatalogo) && $tienePermisoComprasCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>compras">Compras</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <!--  fin Menu compras -->
                        <!-- Menu caja -->
                        <?php if (isset($tienePermisoMenuCaja) && $tienePermisoMenuCaja): ?>
                            <a class="nav-link" href="<?php echo base_url(); ?>ventas/venta">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-cash-register"></i></div>
                                Caja
                            </a>
                        <?php endif; ?>
                        <!--  fin Menu caja -->
                        <!-- Menu ventas -->
                        <?php if (isset($tienePermisoMenuVentas) && $tienePermisoMenuVentas): ?>
                            <a class="nav-link" href="<?php echo base_url(); ?>ventas">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                                Ventas
                            </a>
                        <?php endif; ?>
                        <!--  fin Menu caja -->
                        <!-- Menu reportes -->
                        <?php if (isset($tienePermisoMenuReportes) && $tienePermisoMenuReportes): ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReportes" aria-expanded="false" aria-controls="collapseReportes">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-rectangle-list"></i></div>
                                Reportes
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                        <?php endif; ?>
                        <div class="collapse" id="collapseReportes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <!-- Traemos la url del sistema y la concatenamos con la vista unidades para que la muestre en el menu Unidades-->
                                <?php if (isset($tienePermisoMenuReporteMinimos) && $tienePermisoMenuReporteMinimos): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>productos/mostrarMinimos">Reporte minimos</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoMenuFormularioReporteVentas) && $tienePermisoMenuFormularioReporteVentas): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>ventas/seleccionarReporteVentas">Reporte de ventas por fecha</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoMenuReporteProductos) && $tienePermisoMenuReporteProductos): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>productos/mostrarProductos">Reporte de productos</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoMenuReporteProductosCategoria) && $tienePermisoMenuReporteProductosCategoria): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>detalle_productos_categoria">Productos x categoria</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <!--  fin Menu reportes -->
                        <!-- Menu administraci'on -->
                        <?php if (isset($tienePermisoMenuAdministracion) && $tienePermisoMenuAdministracion): ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdministracion" aria-expanded="false" aria-controls="collapseAdministracion">
                                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-wrench"></i></div>
                                Administración
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                        <?php endif; ?>
                        <div class="collapse" id="collapseAdministracion" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <!-- Traemos la url del sistema y la concatenamos con la vista para que la muestre en el menu-->
                                <?php if (isset($tienePermisoConfiguracion) && $tienePermisoConfiguracion): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>configuracion">Configuración</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoUsuariosCatalogo) && $tienePermisoUsuariosCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>usuarios">Usuarios</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoRolesCatalogo) && $tienePermisoRolesCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>roles">Roles</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoPermisosCatalogo) && $tienePermisoPermisosCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>permisos">Permisos</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoMenuCategoriaPermisos) && $tienePermisoMenuCategoriaPermisos): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>categoriaPermisos">Categoria Permisos</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoCajasCatalogo) && $tienePermisoCajasCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>cajas">Cajas</a>
                                <?php endif; ?>
                                <?php if (isset($tienePermisoLogsCatalogo) && $tienePermisoLogsCatalogo): ?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>logs">Logs de acceso</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <!--  fin Menu administraci'on -->
                    </div>
                </div>
            </nav>
        </div>