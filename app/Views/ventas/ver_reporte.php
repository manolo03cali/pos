<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel">
                    <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px;">
                        <iframe class="embed-responsive-item" id="iframeReporteVentas" name="iframeReporteVentas" 
                                src="<?php  echo base_url()."ventas/generaReporteVentas/".$fecha_inicio.'/'.$fecha_final.'/'.$caja_id; ?>" >
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </main> 
   




