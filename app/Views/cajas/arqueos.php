<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
      <h4 class="mt-4"><?php echo $titulo; ?></h4>
      <!-- Creamos un boton para agregar registro y ver los eliminados -->
      <div>
        <p>
          <a href="<?php echo base_url(); ?>cajas/nuevo_arqueo" class="btn btn-info">Agregar</a>
          <a href="<?php echo base_url(); ?>cajas/eliminados_arqueo" class="btn btn-warning">Eliminados</a>
          <a href="<?php echo base_url(); ?>cajas" class="btn btn-primary">Volver a cajas</a>
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
            <th>Fecha de apertura</th>
            <th>Fecha de cierre</th>
            <th>Monto inicial</th>
            <th>Monto Final</th>
            <th>Total ventas</th>
            <th>Estatus</th>
         
            <th></th>
            

          </tr>
        </thead>
        <tbody>
          <!-- Creamos foreach para traer los datos de la base de datos usando la variable $datos que viene con los datos
                                         que nos trae  -->
          <?php foreach ($datos as $dato) { ?>
            <tr>
              <td> <?php echo $dato['id']; ?></td>
              <td> <?php echo $dato['fecha_inicio']; ?></td>
              <td> <?php echo $dato['fecha_fin']; ?></td>
              <td> <?php echo $dato['monto_inicial']; ?></td>
              <td> <?php echo $dato['monto_final']; ?></td>
              <td> <?php echo $dato['total_ventas']; ?></td>
              
              <?php if ($dato['estatus']== 1){ ?>
                <td>Abierta</td>
              <td>
                <a href="#" data-href="<?php echo base_url() . 'cajas/cerrar_caja/' . $dato['id']; ?>" data-toggle="modal" data-target="#modal-confirma"
                  data-placement="top" title="Cerrar caja" class="btn btn-danger">
                  <i class="fa-solid fa-lock"></i>
              </td>
                  <?php }else {  ?>
                  <td>Cerrada</td>
                  <td>
                <a href="#" data-href="<?php echo base_url() . 'cajas/eliminar/' . $dato['id']; ?>" data-toggle="modal" data-target="#add-new"
                  data-placement="top" title="Eliminar caja" class="btn btn-success">
                  <i class="fa fa-print" aria-hidden="true"></i>

                </a>
              </td>
              <?php }  ?>

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
          <h5 class="modal-title" id="exampleModalLabel">Cerrar Caja</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Desea cerrar la caja?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          <a class="btn btn-danger btn-ok">Si</a>
        </div>
      </div>
    </div>
  </div>