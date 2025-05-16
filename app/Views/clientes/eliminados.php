<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            <!-- Creamos un boton para agregar registro y ver los eliminados -->
            <div>
                <p>
                    <a href="<?php echo base_url(); ?>clientes" class="btn btn-warning">Clientes</a>

                </p>

            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Mostrando datos
                </div>
            </div>

            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Telefono</th>
                        <th>Correo</th>
                        <th>Editar</th>


                    </tr>
                </thead>
                <tbody>
                    <!-- Creamos foreach para traer los datos de la base de datos usando la variable $datos que viene con los datos
                                         que nos trae  -->
                    <?php foreach ($datos as $dato) { ?>
                        <tr>
                            <td> <?php echo $dato['id']; ?></td>
                            <td> <?php echo $dato['nombre']; ?></td>
                            <td> <?php echo $dato['direccion']; ?></td>
                            <td> <?php echo $dato['telefono']; ?></td>
                            <td> <?php echo $dato['correo']; ?></td>
                            <td>
                                <a href="#" data-href="<?php echo base_url() . 'clientes/reingresar/' . $dato['id']; ?>" data-toggle="modal" data-target="#modal-confirma" data-placement="top" title="Reingresar registro" class="btn btn-warning">
                                    <i class="fa-solid fa-arrow-alt-circle-up"></i>
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
                    <h5 class="modal-title" id="exampleModalLabel">Reingresar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Desea reingresar el registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <a class="btn btn-danger btn-ok">Si</a>
                </div>
            </div>
        </div>
    </div>