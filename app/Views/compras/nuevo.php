<?php
$compra_id = uniqid(); //funcion de php que nos va a generar un id unico que va a tener fecha hora y no se repite
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- recibimos la variable titulo del controlador y la mostramos con php en la vista -->


            <!-- Creamos un boton para agregar registro y ver los eliminados autocomplete para que no autocomplete el formulario -->
            <form method="POST" id= "form_compra" name= "form_compra"form_compra action="<?php echo base_url(); ?>compras/guarda" autocomplete="off">
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-4">
                            <!-- -->
                            <input type="hidden" id="producto_id" name="producto_id" />
                            <!--Necesitamos enviar el id de la compra  -->
                            <input type="hidden" id="compra_id" name="compra_id" value = "<?php echo $compra_id; ?>" />
                            <label>Codigo</label>

                            <br>
                            <!-- agregamos set_value en  los campos para que al momento de dejar un campo vacio y se ejecute la validacion recuerde el dato que si 
                             ingreso el usuario-->
                            <!-- Dentro del input de codigo llamamos la funcion buscarProducto usando el onKeyup evento, this= es el tag o nombre del elemento,
                                this.value = lo que escribio el usuario  -->

                            <input class="form-control" id="codigo" name="codigo" type="text" placeholder="Escribe el código y enter" onkeyup="buscarProducto(event, this, this.value)" autofocus />

                            <!-- agregamos otro label para mostar el mensaje de error si encontro o no el producto-->
                            <br>
                            <label for="codigo" id="resultado_error" style="color:red"></label>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Nombre del producto</label>
                            <br>
                            <input class="form-control" id="nombre" name="nombre" type="text" disabled />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Cantidad</label>
                            <br>
                            <!-- agregamos set_value en  los campos para que al momento de dejar un campo vacio y se ejecute la validacion recuerde el dato que si 
                             ingreso el usuario-->
                            <input class="form-control" id="cantidad" name="cantidad" type="text" />
                        </div>
                    </div>

                    <br>

                </div>
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-4">
                            <label>Precio de compra</label>
                            <br>
                            <!-- agregamos set_value en  los campos para que al momento de dejar un campo vacio y se ejecute la validacion recuerde el dato que si 
                             ingreso el usuario-->
                            <input class="form-control" id="precio_compra" name="precio_compra" type="text" disabled />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Subtotal</label>
                            <br>
                            <input class="form-control" id="subtotal" name="subtotal" type="text" disabled />
                        </div>
                        <div class="col-12 col-sm-4">
                            <!--agregamos un espacio en blanco <br>&nbsp; para que alinear el boton con los campos -->
                            <label><br>&nbsp;</label>
                            <!-- agregamos set_value en  los campos para que al momento de dejar un campo vacio y se ejecute la validacion recuerde el dato que si 
                             ingreso el usuario-->
                            <button id="agregar_producto" name="agregar_producto" type="button" class="btn btn-primary" onclick="agregarProducto(producto_id.value, cantidad.value,'<?php echo $compra_id ?>')">Agregar producto </button>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <table id="tablaProductos" class="table table-hover table-striped table-sm table-responsive tablaProductos" width="100%">
                            <thead class="thead-dark">
                                <th>#</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th width="1%"></th>
                            </thead>
                            <tbody></tbody>

                        </table>

                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 offset-md-6">
                            <label style="font-weight: bold; font-size: 30px; text-align: center;">Total $</label>
                            <input type="text" id="total" name="total" size="7" readonly="true" value="0.00" style="font-weight: bold; font-size: 30px; text-align: center;">
                            <button type="button" id="completa_compra" class="btn btn-success" data-toggle="modal" data-target="#modalError">Completar compra</button>
                        </div>

            </form>

        </div>
    </main>
    <script>
        $(document).ready(function() {
//creamos el evento para guardar la compra con los datos que quedaron en la tabla usando el identificador del boton completa_compra
$("#completa_compra").click(function(){
//traemos el numero de filas que tiene la tabla cuando sea tr, si n fila es menor a dos significa que no hemos agregado ninguna que solo tiene el encabezado
    let nFila = $("#tablaProductos tr").length;
    if(nFila < 2){
        //mensaje en modal pendiente en caso de que la tbla no tenga registros
        $('#modalError').modal('show');

    }else{
        $("#form_compra").submit();//enviamos la compra que quede en la tabla usando el nombre del formulario

    }
    //eliminamos el evento generado por el modal para evitar conflicto ya que en este caso presenta 
    //pantalla oscura al cerrar el mensaje error  del modal para informar que debe agregar al menos
    //un registro en la compra y bloquea la aplicaci'on
    $('#modalError').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
            });
}); 
            

        });
        /* Creamos la funcion buscar producto usando jquery e evento cuando presione la tecla, tagcodigo es el nombre del campo y el codigo es el codigo que esta agregando el usuario */
        function buscarProducto(e, tagCodigo, codigo) {
            var enterKey = 13; // es el numero 13 en el codigo acsii que corresponde a enter (retorno del carro) CR
            if (codigo != '') {
                if (e.which == enterKey) { //cuando el usuario presione la tecla enter
                    $.ajax({ //inicia contenido de ajax
                        //Es necesario crear la funcion buscarPorCodigo en nuestro controlador productos
                        url: '<?php echo base_url(); ?>productos/buscarPorCodigo/' + codigo,
                        dataType: 'json',
                        success: function(resultado) { //en caso de que sea correcto va a tener una funcion
                            if (resultado == 0) { //si es igual a cero puede  que no encuentre el producto
                                $(tagCodigo).val(''); //par vaciar el codigo si no  encontro nada o tuvo algun error y dejar el tag vacio   
                            } else {
                                //si el resultado no es igual a cero entonces 
                                $(tagCodigo).removeClass('has-error');
                                $("#resultado_error").html(resultado.error); //El tag #resultado_error para que muestre el mensaje de error si logro encontrar el producto se coloca html porque es un label
                                if (resultado.existe) {
                                    //como son input se utiliza val
                                    $("#producto_id").val(resultado.datos.id); //Envia el resultado de la columna al input hidden que creamos en el formulario para producto_id y asi sucesivamente con los demas campos
                                    $("#nombre").val(resultado.datos.nombre);
                                    $("#cantidad").val(1); //dejamos predefinido para que nos muestre como cantidad 1
                                    $("#precio_compra").val(resultado.datos.precio_compra);
                                    $("#subtotal").val(resultado.datos.precio_compra);
                                    $("#cantidad").focus(); //cuando se encuentre el producto se vaya a la cantidad automaticamente

                                } else { //en caso de que no encuentre el producto deja los campos vacios con ''
                                    $("#producto_id").val(''); //Envia el resultado de la columna al input hidden que creamos en el formulario para producto_id
                                    $("#nombre").val('');
                                    $("#cantidad").val(''); //dejamos predefinido para que nos muestre como cantidad 1
                                    $("#precio_compra").val('');
                                    $("#subtotal").val('');


                                }


                            }

                        }

                    });

                }


            }

        }

        function actualizarSubtotal() {
            // Obtiene el valor de la cantidad y el precio de compra ingresados por el usuario
            var cantidad = parseFloat($('#cantidad').val());
            var precioCompra = parseFloat($('#precio_compra').val());

            // Calcula el subtotal multiplicando la cantidad por el precio de compra
            var subtotal = cantidad * precioCompra;

            // Actualiza el campo del subtotal con el valor calculado, formateado a 2 decimales
            $('#subtotal').val(subtotal.toFixed(2));

            // Llama a la función actualizarTotal para recalcular el total general
            actualizarTotal();
        }


        function actualizarTotal() {
            var total = 0; // Inicializa la variable total en 0

            // Recorre cada fila en el tbody de la tabla de productos
            $('#tablaProductos tbody tr').each(function() {
                // Suma el valor del subtotal (clase .total) de cada fila al total general
                total += parseFloat($(this).find('.total').text());
            });

            // Actualiza el campo del total con el valor calculado, formateado a 2 decimales
            $('#total').val(total.toFixed(2));
        }


        function agregarProducto(producto_id, cantidad, compra_id) {
            if (producto_id != null && producto_id != 0 && cantidad > 0) {
                $.ajax({
                    // Es necesario crear la función buscarPorCodigo en nuestro controlador productos
                    url: '<?php echo base_url(); ?>temporalCompra/insertarCompra/' + producto_id + "/" + cantidad + "/" + compra_id,
                    success: function(resultado) {
                        if (resultado == 0) {
                            // no envía nada si el resultado es cero
                        } else {
                            var resultado = JSON.parse(resultado);
                            if (resultado.error == '') {
                                // Limpia el contenido del tbody
                                $("#tablaProductos tbody").empty();
                                // Inserta los datos recibidos en el tbody
                                $("#tablaProductos tbody").append(resultado.datos);
                                // Actualiza el campo de total
                                $("#total").val(resultado.total);

                                // Vacía los campos del formulario
                                $("#producto_id").val('');
                                $("#codigo").val('');
                                $("#nombre").val('');
                                $("#cantidad").val('');
                                $("#precio_compra").val('');
                                $("#subtotal").val('');
                            }
                        }
                    }
                });
            }
        }
        function eliminaProducto(producto_id, compra_id) {
    $.ajax({
        url: '<?php echo base_url(); ?>temporalCompra/eliminar/' + producto_id + "/" + compra_id,
        dataType: 'json',
        success: function(resultado) {
            if (resultado == 0) {
                // Si el resultado es cero, no hace nada (esto puede ser innecesario y se puede eliminar)
            } else {
                // No es necesario parsear el resultado porque ya se especificó dataType: 'json'
                $("#tablaProductos tbody").empty(); // Limpia el contenido del tbody
                $("#tablaProductos tbody").append(resultado.datos); // Inserta los datos recibidos en el tbody
                $("#total").val(resultado.total); // Actualiza el campo de total
            }
        }
    });
}

        

        
    </script>
     <!-- Modal -->
<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="modalErrorLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalErrorLabel">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        La tabla debe tener al menos un producto para completar la compra.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>