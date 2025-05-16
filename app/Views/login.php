<?php
// Traemos los datos de mi sesión para poder asignarla header en los iconos apropiados
$user_session = session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>POS CDP</title>
    <!-- Carga de estilos CSS primero -->
    <link href="<?php echo base_url(); ?>css/styles.css" rel="stylesheet" />
    <!-- Carga de JavaScript con defer para evitar bloqueos -->
    <script src="<?php echo base_url(); ?>js/all.js" defer></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Iniciar sesión</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="<?php echo base_url(); ?>usuarios/valida" autocomplete="off">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Ingresa tu usuario" />
                                            <label for="usuario">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="password" name="password" type="password" placeholder="Ingresa tu Contraseña" />
                                            <label for="password">Contraseña</label>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" type='submit'>Login</button>
                                        </div>
                                        <?php if (isset($validation)) { ?>
                                            <div class="alert alert-danger">
                                                <?php echo $validation->listErrors(); ?>
                                            </div>
                                        <?php } ?>
                                        <?php if (isset($error)) { ?>
                                            <div class="alert alert-danger">
                                                <?php echo $error; ?>
                                            </div>
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Jose Manuel Quintero Ferreira <?php echo (date(' Y')) ?></div>
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

    <!-- Mover scripts de JavaScript al final para evitar bloqueos en la carga inicial -->
    <script src="<?php echo base_url(); ?>js/bootstrap.bundle.min.js" defer></script>
    <script src="<?php echo base_url(); ?>js/scripts.js" defer></script>
</body>
</html>
