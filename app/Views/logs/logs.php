<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->
      <h4 class="mt-4"><?php echo $titulo; ?></h4>
      <!-- Creamos un boton para agregar registro y ver los eliminados -->

      <div class="card mb-4">
        <div class="card-header">
          <i class="fas fa-table me-1"></i>
          Mostrando datos
        </div>
      </div>

      <table id="datatablesSimple">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Dirección Ip</th>
            <th>Evento</th>
            <th>Detalles</th>

          </tr>
        </thead>
        <tbody>
          <!-- Creamos foreach para traer los datos de la base de datos usando la variable $datos que viene con los datos
                                         que nos trae  -->
          <?php foreach ($datos as $dato) { ?>
            <tr>
              <td> <?php echo $dato['fecha_alta']; ?></td>
              <td> <?php echo $dato['usuario']; ?></td>
              <td> <?php echo $dato['ip']; ?></td>
              <td> <?php echo $dato['evento']; ?></td>
              <td> <?php echo $dato['detalles']; ?></td>

            </tr>

          <?php }  ?>

        </tbody>
      </table>
    </div>
  </main>