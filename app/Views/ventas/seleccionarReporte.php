<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors(); ?>
                </div>
            <?php elseif (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo session()->getFlashdata('error'); ?>
                </div>
            <?php elseif (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?php echo session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>

            <br>

            <form method="POST" action="<?php echo base_url('ventas/mostrarReporteVentas'); ?>" onsubmit="return validarFechas();">
                <div class="form-group">
                    <div class="row">
                        <!-- Campo de Fecha Inicio -->
                        <div class="col-sm-6">
                            <label>Fecha inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo set_value('fecha_inicio'); ?>" required >
                        </div>

                        <!-- Campo de Fecha Final -->
                        <div class="col-sm-6">
                            <label>Fecha final</label>
                            <input type="date" class="form-control" id="fecha_final" name="fecha_final" value="<?php echo set_value('fecha_final'); ?>" required >
                        </div>

                        <!-- Campo de Caja -->
                        <div class="col-12 col-sm-6">
                            <label>Caja</label>
                            <select class="form-control" id="caja_id" name="caja_id">
                                <option value="0" <?php echo set_value('caja_id') == '0' ? 'selected' : ''; ?>>Todas las cajas</option>
                                <?php foreach ($cajas as $caja): ?>
                                    <option value="<?php echo $caja['id']; ?>" <?php echo set_value('caja_id') == $caja['id'] ? 'selected' : ''; ?>>
                                        <?php echo $caja['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Generar Reporte</button>
            </form>
        </div>
    </main>


