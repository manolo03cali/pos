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
            <form method="POST" action="<?php echo base_url(); ?>usuarios/actualizar" autocomplete="off">
                <!--Enviamos el id oculto luego lo copio para los demas campos  -->
                <input type="hidden" id="id" name="id" value="<?php echo $usuario['id']; ?>">
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Usuario</label>
                            <br>
                            <input class="=form-control" id="usuario" name="usuario" type="text" value="<?php echo $usuario['usuario']; ?>" disabled />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <br>
                            <input class="=form-control" id="nombre" name="nombre" type="text" value="<?php echo $usuario['nombre']; ?>" autofocus  />
                        </div>
                       
                       
                    </div>
                </div>
                <div class="form-group">

                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Rol</label>
                            <br>
                            <!-- para poder ver los datos del usuario que vienen de la consulta en los select se debe usar la propiedad selected y se identifica con un if que los datos
                            cuando la unidad que se tiene en el combo sea igual a la variable que viene usuario id_unidad de la tabla si se cumple con un echo se imprime 
                            el selected, el echo de la consulta debe quedar dentro del option -->
                            <select class="form-control" id="roles_id" name="roles_id" >
                                <option value="">Seleccionar rol </option>
                                <?php foreach ($roles as $rol) { ?>
                                    <option value="<?php echo $rol['id']; ?>"
                                        <?php if ($rol['id'] == $usuario['roles_id']) {
                                            echo 'selected';
                                        } ?>>
                                        <?php echo $rol['nombre'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Caja</label>
                            <br>
                            <select class="form-control" id="cajas_id" name="cajas_id" >
                                <option value="">Seleccionar Caja </option>
                                <?php foreach ($cajas as $caja) { ?>
                                    <option value="<?php echo $caja['id']; ?>"
                                        <?php if ($caja['id'] == $usuario['cajas_id']) {
                                            echo 'selected';} ?>>
                                            <?php echo $caja['nombre'] ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <a href="<?php echo base_url(); ?>usuarios" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>

            </form>

        </div>
    </main>