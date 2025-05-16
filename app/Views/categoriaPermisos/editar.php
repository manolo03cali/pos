<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            <!-- Validamos de que exista la variable $validation-->
            <?php if (isset($validation)) { ?>
                <div class="alert alert-danger">
                    <!--Traemos la lista de errores y la guardaamos en la variable validation -->
                    <?php echo $validation->listErrors(); ?>
                </div>
            <?php } ?>
            <!-- Creamos un boton para agregar registro y ver los eliminados autocomplete para que no autocomplete el formulario -->
            <form method="POST" action="<?php echo base_url(); ?>categoriaPermisos/actualizar" autocomplete="off">
                <?php
                //para que nos paso con cada una de las filas que no se agrego el datos
                csrf_field();
                ?>
                <!-- Adicional a los campos nombre y nombre_corto debemos obtener el id de manera oculta para que al momento de enviar el formulario 
                                 se envien los tres parametros id y nombre a editar y nombre_corto a editar -->
                <input type="hidden" value="<?php echo $datos['id'] ?>" name="id" />
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <!-- Traemos el dato correspondiente al campo del arreglo datos que viene del controlador-->
                            <input class="=form-control" id="nombre" name="nombre" type="text" value="<?php echo $datos['nombre'] ?>" autofocus  />
                        </div>
                       
                    </div>
                </div>
                <br>
                <a href="<?php echo base_url(); ?>unidades" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
            </form>
        </div>
    </main>