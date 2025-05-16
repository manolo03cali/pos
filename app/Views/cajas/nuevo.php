<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            <!-- Agregamos la lista de errores usando la libreria boostrap y php-->
            <!-- Validamos de que exista la variable $validation-->
            <?php if (isset($validation)) { ?>
                <div class="alert alert-danger">
                    <!--Traemos la lista de errores y la guardamos en la variable validation -->
                    <?php echo $validation->listErrors(); ?>
                </div>
            <?php } ?>

            <!-- Creamos un boton para agregar registro y ver los eliminados autocomplete para que no autocomplete el formulario -->
            <form method="POST" action="<?php echo base_url(); ?>cajas/insertar" autocomplete="off">
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Número de la caja</label>
                            <br>
                            <!-- agregamos set_value en  los campos para que al momento de dejar un campo vacio y se ejecute la validacion recuerde el dato que si 
                             ingreso el usuario-->
                            <input class="=form-control" id="numero_caja" name="numero_caja" type="text" value="<?php echo set_value('numero_caja') ?>" autofocus />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <br>
                            <input class="=form-control" id="nombre" name="nombre" type="text" value="<?php echo set_value('nombre') ?>" />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Remisión</label>
                            <br>
                            <input class="=form-control" id="folio" name="folio" type="text" value="<?php echo set_value('folio') ?>" />
                        </div>
                        
                    </div>

                    <br>
                    <a href="<?php echo base_url(); ?>cajas" class="btn btn-primary">Regresar</a>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>

        </div>
    </main>