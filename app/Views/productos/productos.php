<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            <!-- Creamos un boton para agregar registro y ver los eliminados -->
            <div>
                <p>
                    <a href="<?php echo base_url(); ?>productos/nuevo" class="btn btn-info">Agregar</a>
                    <a href="<?php echo base_url(); ?>productos/eliminados" class="btn btn-warning">Eliminados</a>
                    <a href="<?php echo base_url(); ?>productos/muestraCodigos" class="btn btn-primary">Códigos de barras masivo</a>
                </p>

            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Mostrando datos
                </div>
            </div>
            

            <table id="datatablesSimple" class="datatablesSimple">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Precio Venta</th>
                        <th>Existencias</th>
                        <th>Imagen</th>
                        <th>Editar</th>
                        <th>Eliminar</th>

                    </tr>
                </thead>
                <tbody>
                    <!-- Creamos foreach para traer el nombre de las columnas y sus datos de la base de datos usando la variable $datos que viene con los datos
                                         que nos trae  -->
                    <?php foreach ($datos as $dato) { ?>
                        <tr>
                            <td> <?php echo $dato['id']; ?></td>
                            <td> <?php echo $dato['codigo']; ?></td>
                            <td> <?php echo $dato['nombre']; ?></td>
                            <td> <?php echo $dato['precio_venta']; ?></td>
                            <td> <?php echo $dato['existencias']; ?></td>
                            <td> <img src="<?php echo base_url() . '/images/productos/'.$dato['id'].'/foto_1.png?'. time(); ?>" width="100"/></td>
                            <!-- En la tabla  creamos dos botones que traen la url que nos envian a las url para editar y eliminar 
                                                  para cada campo por intermedio del id obtenido de la base de datos a travez de la variable $dato del foreach
                                                  agregamos los botones de la libreria precargada fontawesome  es importante concatenas el id  con la url para 
                                                  que adjunte a la url el id y enviarlo al controlador para que ejecute el metodo correspondiente-->
                            <td> <a href="<?php echo base_url() . 'productos/editar/' . $dato['id']; ?>" class="btn btn-warning">
                                    <i class="fa-solid fa-pencil"></i></a></td>
                            <!--Vamos a trabajar Modal con el evento eliminar  -->
                            
                            <td>
                                <a href="#" data-href="<?php  echo base_url() . 'productos/eliminar/' . $dato['id']; ?>" data-toggle="modal" data-target="#modal-confirma"
                                 data-placement="top" title="Eliminar registro" class="btn btn-danger">
                                <i class="fa-regular fa-trash-can"></i>
                                </a>
                               
                               


                            </td>
                                


                        </tr>

                    <?php }  ?>

                </tbody>
            </table>
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
        <a class ="btn btn-danger btn-ok">Si</a>
      </div>
    </div>
  </div>
</div>

