<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            <!-- Creamos un boton para agregar registro y ver los eliminados -->
            <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center mt-4">
                                    <h1 class="display-1">401</h1>
                                    <p class="lead">No autorizado</p>
                                    <p><?php echo $mensaje; ?></p>
                                    <a href="index.html">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        <a href="<?php echo base_url(); ?>inicio"> Volver al Dashboard</a>
                                    </a>
                                </div>
                            </div>
                        </div>
        </div>
    </main>