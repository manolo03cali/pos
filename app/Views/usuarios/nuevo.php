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
            <form method="POST" action="<?php echo base_url(); ?>usuarios/insertar" autocomplete="off">
                
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Usuario</label>
                            <br>
                            <input class="=form-control" id="usuario" name="usuario" type="text" value="<?php echo set_value('usuario') ?>" autofocus  />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <br>
                            <input class="=form-control" id="nombre" name="nombre" type="text" value="<?php echo set_value('nombre') ?>"  />
                        </div>
                        
                    </div>
                </div>
                <div class="form-group">
                    <!-- para agregar filas-->
                     
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Contraseña</label>
                            <br>
                            <input class="=form-control" id="password" name="password" type="password" value="<?php echo set_value('password') ?>"  />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Repita contraseña</label>
                            <br>
                            <input class="=form-control" id="repassword" name="repassword" type="password" value="<?php echo set_value('repassword') ?>"  />
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
                            <select class="form-control" id="roles_id" name="roles_id" >
                                <option value="">Seleccionar rol </option>
                                <?php foreach ($roles as $rol) { 
                                    //$selected recordamos la opcion seleccionada por el usuario en caso de que no cumpla la validaci'on de otro campo
                                    $selected = set_value('roles_id') == $rol['id'] ? 'selected' : ''; ?>
                                    <option value ="<?php echo  $rol['id']; ?>" <?php echo $selected;?>>
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
                                <?php foreach ($cajas as $caja) { 
                                    $selected = set_value('cajas_id') == $caja['id'] ? 'selected' : '';?>
                                    <option value="<?php echo $caja['id'] ?>" <?php echo $selected;?>>                                    
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