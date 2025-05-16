<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Jose Manuel Quintero Ferreira <?php echo (date(' Y')) ?> </div>
            <div>
                <a href="http://motoaccesorios.com.co" target="_blank">MotoAccesorios</a>
                &middot;
                <a href="http://acikalarte.com.co" target="_blank"> Acikalarte</a>
            </div>
        </div>
    </div>
</footer>
</div>
</div>
<!-- Campo oculto para el mensaje de éxito el cual es usado por el SweetAlert-->
<?php if ($successMessage = session()->getFlashdata('success')): ?>
    <input type="hidden" id="successMessage" value="<?php echo $successMessage; ?>">
    <?php session()->remove('success'); ?>
<?php endif; ?>
 
 

<!-- Bootstrap JS (incluye Popper) en este caso no lo incluye -->
<script src="<?php  echo base_url(); ?>js/bootstrap.bundle.min.js" defer></script>
<!-- SweetAlert2 JS -->
<script src="<?php echo base_url(); ?>js/sweetalert2.all.min.js"  ></script>
<script src="<?php echo base_url(); ?>js/scripts.js" defer></script>
<script src="<?php echo base_url(); ?>js/Chart.min.js" defer></script>
<script src="<?php echo base_url(); ?>assets/demo/chart-area-demo.js" defer></script>
<script src="<?php echo base_url(); ?>assets/demo/chart-bar-demo.js" defer></script>
<script src="<?php echo base_url(); ?>assets/demo/chart-pie-demo.js" defer></script>
<script src="<?php echo base_url(); ?>js/simple-datatables.min.js" defer></script>
<script src="<?php echo base_url(); ?>js/datatables-simple-demo.js" defer></script>
<!-- Popper JS (si no está incluido en Bootstrap.bundle, en este caso no esta incluido) -->
<script src="<?php echo base_url(); ?>js/popper.min.js" defer></script>
<!-- Bootstrap JS (si no usas Bootstrap.bundle.min.js) -->
<script src="<?php //echo base_url(); ?>js/bootstrap.min.js" defer></script>
<!-- Scripts personalizados -->
<script src="<?php echo base_url(); ?>js/miscripts.js" defer></script>
</body>
</html>