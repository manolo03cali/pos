<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
             <!-- Validamos de que exista la variable $validation-->
           <?php if (isset($validation)) { ?>
                <div class="alert alert-danger">
                    <!--Traemos la lista de errores y la guardamos en la variable validation -->
                    <?php echo $validation->listErrors(); ?>
                </div>
            <?php } ?>
            <!-- Creamos un boton para agregar registro y ver los eliminados autocomplete para que no autocomplete el formulario -->
            <form method="POST" action="<?php echo base_url(); ?>permisos/insertar" autocomplete="off">
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <br>
                            <input class="=form-control" id="nombre" name="nombre" type="text" value="<?php echo set_value('nombre') ?>" autofocus  />
                            
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Tipo</label>
                            <br>
                            <input class="=form-control" id="tipo" name="tipo" type="text" value="<?php echo set_value('tipo') ?>" />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Categoria</label>
                            <br>
                            <select class="form-control" id="categoria_permisos_id" name="categoria_permisos_id" >
                                <option value="">Seleccionar Categoria </option>
                                <?php foreach ($categoriasPermisos as $categoria) { 
                                    $selected = set_value('categoria_permisos_id') == $categoria['id'] ? 'selected' : '';?>
                                    <option value="<?php echo $categoria['id'] ?>" <?php echo $selected;?>>                                    
                                    <?php echo $categoria['nombre'] ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <br>
                    <a href="<?php echo base_url(); ?>permisos" class="btn btn-primary">Regresar</a>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>

        </div>
    </main>
    