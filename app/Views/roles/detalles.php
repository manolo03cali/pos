<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-5">
      <div class="row mt-4">
        <div class="col-lg-12">
          <!-- Título principal -->
          <h2 class="text-center mb-4"><?php echo $titulo; ?></h2>
        </div>
      </div>

      <!-- Formulario de permisos -->
      <form id="form_permisos" name="form_permisos" method="POST" action="<?php echo base_url() . 'roles/guardaPermisos'; ?>">
        <input type="hidden" name="rol_id" value="<?php echo $rol_id; ?>">

        <div class="card mb-4 shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center w-100">
            <h5 class="card-title mb-0">Permisos para el rol: <strong><?php echo $nombreRol; ?></strong></h5>
            <div>
              <a href="<?php echo base_url(); ?>roles" class="btn btn-info me-2">Volver a roles</a>
              <button type="submit" class="btn btn-primary me-2">Guardar</button>
              <button type="reset" class="btn btn-secondary">Cancelar</button>
            </div>
          </div>

          <div class="card-body">
            <!-- Recorrer las categorías de permisos -->
            <div class="accordion" id="accordionPermisos">
              <?php foreach ($permisosAgrupados as $categoria => $permisos): ?>
                <div class="accordion-item mb-3">
                  <!-- Cabecera de la categoría -->
                  <h2 class="accordion-header" id="heading_<?php echo md5($categoria); ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo md5($categoria); ?>" aria-expanded="false" aria-controls="collapse_<?php echo md5($categoria); ?>">
                      <?php echo $categoria; ?>
                    </button>
                  </h2>

                  <!-- Permisos dentro de la categoría -->
                  <div id="collapse_<?php echo md5($categoria); ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?php echo md5($categoria); ?>" data-bs-parent="#accordionPermisos">
                    <div class="accordion-body">
                      <div class="form-check form-switch ms-3 d-flex align-items-center">
                        <span class="fw-bold me-2">Seleccionar todos</span>
                        <input class="form-check-input" type="checkbox" id="checkAll_<?php echo md5($categoria); ?>"
                          onclick="toggleCategoryPermisos('<?php echo md5($categoria); ?>')">
                      </div>

                      <hr>

                      <!-- Permisos individuales en columnas -->
                      <div class="row">
                        <?php
                          $permisoCount = 0;
                          foreach ($permisos as $permiso):
                            // Abrir una nueva columna cada 10 permisos
                            if ($permisoCount % 10 === 0) {
                              echo '<div class="col-lg-6 mb-3">';  // Puedes ajustar el tamaño de las columnas
                            }
                        ?>
                          <div class="form-check form-switch ms-3">
                            <input class="form-check-input permiso_<?php echo md5($categoria); ?>" type="checkbox"
                              value="<?php echo $permiso['id']; ?>"
                              id="permiso_id<?php echo $permiso['id']; ?>"
                              name="permisos[]"
                              <?php echo isset($asignado[$permiso['id']]) && $asignado[$permiso['id']] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="permiso_id<?php echo $permiso['id']; ?>">
                              <?php echo $permiso['nombre']; ?>
                            </label>
                          </div>
                        <?php
                            $permisoCount++;
                            // Cerrar la columna cada 10 permisos
                            if ($permisoCount % 10 === 0) {
                              echo '</div>';
                            }
                          endforeach;
                          // Cerrar la última columna si no tiene exactamente 10 permisos
                          if ($permisoCount % 10 !== 0) {
                            echo '</div>';
                          }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>

  <!-- Script para seleccionar/desmarcar todos los permisos de una categoría -->
  <script>
    function toggleCategoryPermisos(categoriaHash) {
      var checkAll = document.getElementById('checkAll_' + categoriaHash);
      var permisos = document.querySelectorAll('.permiso_' + categoriaHash);

      permisos.forEach(function(checkbox) {
        checkbox.checked = checkAll.checked;
      });
    }
  </script>
