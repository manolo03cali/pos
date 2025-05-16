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
            <!-- Creamos un boton para agregar registro y ver los eliminados autocomplete para que no autocomplete el formulario 
             enctype="multipart/form-data" hay que agregarlo para que el formulario me permita enviar archivos-->
            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>productos/insertar" autocomplete="off">
                
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Codigo</label>
                            <br>
                            <input class="=form-control" id="codigo" name="codigo" type="text" value="<?php echo set_value('codigo') ?>"   />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <br>
                            <input class="=form-control" id="nombre" name="nombre" type="text" value="<?php echo set_value('nombre') ?>"autofocus  />
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
                            <select class="form-control" id="unidades_id" name="unidades_id" >
                                <option value="">Seleccionar unidad </option>
                                <?php foreach ($unidades as $unidad) { 
                                    //$selected recordamos la opcion seleccionada por el usuario en caso de que no cumpla la validaci'on de otro campo
                                    $selected = set_value('unidades_id') == $unidad['id'] ? 'selected' : ''; ?>
                                    <option value ="<?php echo  $unidad['id']; ?>" <?php echo $selected;?>>
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
                                <?php foreach ($categorias as $categoria) { 
                                    $selected = set_value('categorias_id') == $categoria['id'] ? 'selected' : '';?>
                                    <option value="<?php echo $categoria['id'] ?>" <?php echo $selected;?>>                                    
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
                            <input class="=form-control" id="precio_venta" name="precio_venta" type="text" value="<?php echo set_value('precio_venta') ?>" />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Precio compra</label>
                            <br>
                            <input class="=form-control" id="precio_compra" name="precio_compra" type="text" value="<?php echo set_value('precio_compra') ?>" autofocus  />
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
                            <input class="=form-control" id="stock_minimo" name="stock_minimo" type="text" value="<?php echo set_value('stock_minimo') ?>" />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Es inventariable</label>
                            <br>
                            <select class="form-control" id="inventariable" name="inventariable" >
                                <option value="1">Si</option>
                                <option value="0">No</option>

                            </select>


                        </div>
                    </div>
                </div>
                <div class = "form-group" >
                                <div class ="row" >
                                    <div class ="col-12 col-sm-6" >
                                    <label>Imagen producto</label>
                                    <br>
                                             <!-- Agregammos el parametro multiple para cargar multiples imagenes-->                                
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