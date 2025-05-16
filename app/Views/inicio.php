<div id="layoutSidenav_content">
    <main>
        <link href="<?php echo base_url(); ?>css/mistyles.css" rel="stylesheet" />
        <div class="container-fluid px-4">

            <br>

            <div class="row">
            <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                       
                        Total compras del dia:<br>  <?php echo '$' . $totalComprasDia['total'] ?>
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-truck"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?php echo base_url(); ?>compras">Ver detalles</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            Total ventas del dia: <br>  <?php echo '$' . $totaldia['total'] ?>
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?php echo base_url(); ?>ventas/ventas_caja">Ver detalles</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            Total productos activos: <br> <?php echo $total ?>
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-list"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?php echo base_url(); ?>productos">Ver detalles</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
               
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            Productos con stock minimo: <br>  <?php echo $minimos ?>
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-shopping-basket"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?php echo base_url(); ?>productos_minimo">Ver detalles</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area me-1"></i>
                                Ventas de la semana
                            </div>
                            <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-1"></i>
                                Productos con stock minimo
                            </div>
                            <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                        </div>
                    </div>
                



                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area me-1"></i>
                                Productos mas vendidos del mes
                            </div>
                            <div class="card-body"><canvas id="myPieChart" width="100%" height="40"></canvas></div>
                        </div>
                    </div>


                </div>

                <div class="col-4">
                    <a href="<?php echo base_url(); ?>inicio/excel" class="btn btn-primary">Genera excel</a>

                </div>




    </main>

   
    <script>
         /*-----------------------------------------------------*/
        var nombresDias = <?php echo $nombresDias; ?>;
        var totales = <?php echo $totales; ?>;
        var valorMaximo = <?php echo $valorMaximo; ?>; 
        /*-----------------------------------------------------*/
        var nombresProductos = <?php echo $nombresProductos; ?>; 
        var cantidadesVendidas = <?php echo $cantidadesVendidas; ?>; 
        /*-------------------------------------------------------*/
        var nombresProductosMinimo = <?php echo $nombresProductosMinimo;  ?>;
       var existencias = <?php echo $existencias; ?>;
       var stockMinimo = <?php echo $stockMinimo; ?>;
       
       
    </script>
   