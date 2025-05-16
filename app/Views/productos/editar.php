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
            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>productos/actualizar" autocomplete="off">
                <?php
                //para que nos indique que paso con cada una de las filas que no se agrego el datos
                csrf_field();
                ?>
                <!--Enviamos el id oculto luego lo copio para los demas campos  -->
                <input type="hidden" id="id" name="id" value="<?php echo $producto['id']; ?>">
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Codigo</label>
                            <br>
                            <input class="=form-control" id="codigo" name="codigo" type="text" value="<?php echo $producto['codigo']; ?>"  />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <br>
                            <input class="=form-control" id="nombre" name="nombre" type="text" value="<?php echo $producto['nombre']; ?>" autofocus  />
                        </div>
                    </div>
                </div>
                <div class="form-group">

                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Unidad</label>
                            <br>
                            <!-- para poder ver los datos del producto que vienen de la consulta en los select se debe usar la propiedad selected y se identifica con un if que los datos
                            cuando la unidad que se tiene en el combo sea igual a la variable que viene producto id_unidad de la tabla si se cumple con un echo se imprime 
                            el selected, el echo de la consulta debe quedar dentro del option -->
                            <select class="form-control" id="unidades_id" name="unidades_id" >
                                <option value="">Seleccionar unidad </option>
                                <?php foreach ($unidades as $unidad) { ?>
                                    <option value="<?php echo $unidad['id']; ?>"
                                        <?php if ($unidad['id'] == $producto['unidades_id']) {
                                            echo 'selected';
                                        } ?>>
                                        <?php echo $unidad['nombre'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Categoria</label>
                            <br>
                            <select class="form-control" id="categorias_id" name="categorias_id" >
                                <option value="">Seleccionar Categoria </option>
                                <?php foreach ($categorias as $categoria) { ?>
                                    <option value="<?php echo $categoria['id']; ?>"
                                        <?php if ($categoria['id'] == $producto['categorias_id']) {
                                            echo 'selected';} ?>>
                                            <?php echo $categoria['nombre'] ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Precio venta</label>
                            <br>
                            <input class="form-control" id="precio_venta" name="precio_venta" type="text" value="<?php echo $producto['precio_venta']; ?>"  />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Precio compra</label>
                            <br>
                            <input class="form-control" id="precio_compra" name="precio_compra" type="text" value="<?php echo $producto['precio_compra']; ?>" autofocus  />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Stock Minimo</label>
                            <br>
                            <input class="form-control" id="stock_minimo" name="stock_minimo" type="text" value="<?php echo $producto['stock_minimo']; ?>"  />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Es inventariable</label>
                            <br>
                            <select class="form-control" id="inventariable" name="inventariable">                                                                                                                                                   } ?> required>
                                <option value="1"<?php if ($producto['inventariable']==1) {
                                            echo 'selected';} ?>>Si</option>
                                <option value="0"<?php if ($producto['inventariable']==0) {
                                            echo 'selected';} ?>>No</option>

                            </select>


                        </div>
                    </div>
                </div>
                <div class = "form-group" >
                                <div class ="row" >
                                    <div class ="col-12 col-sm-6" >
                                    <label>Imagen</label>
                                    <br>
                                                                     
                                    
                                    <td> <img src="<?php echo base_url() . '/images/productos/'.$producto['id'].'/foto_1.png?'. time(); ?>" width="100"/></td>
                                  
                                    <input type="file" id="img_producto" name="img_producto[]" accept="image/png" multiple/>
                                    <p class="text-danger">Cargar imagen en formato png de 150*150 pixeles</p>
                                    

                                    </div>

                                </div>

                            </div>

                <br>
                <a href="<?php echo base_url(); ?>productos" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>

            </form>

        </div>
    </main>