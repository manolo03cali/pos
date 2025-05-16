<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            <!-- Creamos un boton para agregar registro y ver los eliminados -->
            <?php if (isset($validation)) { ?>
                <div class="alert alert-danger">
                    <!--Traemos la lista de errores y la guardamos en la variable validation -->
                    <?php echo $validation->listErrors(); ?>
                </div>
            <?php } ?>

            <!-- importante agregar  enctype="multipart/form-data" para que reconozca la imagen o archivo input de tipo file que vamosa  adjuntar si no lo tomara como un texto plano-->
            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>configuracion/actualizar" autocomplete="off">
                <?php
                //para que nos paso con cada una de las filas que no se agrego el datos
                csrf_field();
                ?>
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-6">
                            <label>Nombre tienda</label>
                            <br>
                            <!-- agregamos echo isset para traer los datos de mi tabla configuracion y asignarlo al campo de texto -->

                            <input class="=form-control" id="tienda_nombre" name="tienda_nombre" type="text" value="<?php echo ($datos['tienda_nombre']) ?>" ; autofocus />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>RFC tienda</label>
                            <br>
                            <input class="=form-control" id="tienda_rfc" name="tienda_rfc" type="text" value="<?php echo ($datos['tienda_rfc']) ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- para agregar filas-->
                        <div class="row">
                            <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                            <div class="col-12 col-sm-6">
                                <label>Telefono tienda</label>
                                <br>
                                <!-- agregamos set_value en  los campos para que al momento de dejar un campo vacio y se ejecute la validacion recuerde el dato que si 
                             ingreso el usuario-->
                                <input class="=form-control" id="tienda_telefono" name="tienda_telefono" type="text" value="<?php echo ($datos['tienda_telefono']) ?>" />
                            </div>
                            <div class="col-12 col-sm-6">
                                <label>Email tienda</label>
                                <br>
                                <input class="=form-control" id="tienda_email" name="tienda_email" type="text" value="<?php echo ($datos['tienda_email']) ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- para agregar filas-->
                            <div class="row">
                                <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                                <div class="col-12 col-sm-6">
                                    <label>Dirección tienda</label>
                                    <br>
                                    <!-- agregamos set_value en  los campos para que al momento de dejar un campo vacio y se ejecute la validacion recuerde el dato que si 
                             ingreso el usuario-->
                                    <textarea class="=form-control" id="tienda_direccion" name="tienda_direccion" type="text"><?php echo ($datos['tienda_direccion']) ?></textarea>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label>Ticket leyenda</label>
                                    <br>
                                    <textarea class="=form-control" id="ticket_leyenda" name="ticket_leyenda" type="text"><?php echo ($datos['ticket_leyenda']) ?></textarea>
                                </div>
                            </div>
                            <br>

                            <div class = "form-group" >
                                <div class ="row" >
                                    <div class ="col-12 col-sm-6" >
                                    <label>Logotipo</label>
                                    <br>
                                    
                                    <img src="<?php echo base_url() . '/images/logotipo.png?' . time(); ?>" class="img-responsive" width="200" height="200"/>
                                    
                                    <input type="file" id="tienda_logo" name="tienda_logo" accept="image/png"/>
                                    <p class="text-danger">Cargar imagen en formato png de 150*150 pixeles</p>
                                    

                                    </div>

                                </div>

                            </div>

                            <br>
                            <!-- <a href="<?php //echo base_url(); 
                                            ?>unidades" class="btn btn-primary">Regresar</a> -->

                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>

            </form>

        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="modal-confirma" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Desea eliminar el registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <a class="btn btn-danger btn-ok">Si</a>
                </div>
            </div>
        </div>
    </div>