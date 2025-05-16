<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!--creamos un id unico usando la funci'on uniqid -->
            <h4 class="mt-4"><?php echo $titulo; ?></h4>
            <?php $idVentatmp = uniqid(); ?>

            <br>
            <form id="form_venta" name="form_venta" class="form-horizontal" method="POST" action="<?php echo base_url(); ?>ventas/guarda" autocomplete="off">
                <!--enviamos el id oculto  -->
                <input type="hidden" id="venta_id" name="venta_id" value="<?php echo $idVentatmp; ?>" />


                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <!--usamos un widget jquery ui para mostrar el listado de cliente de manera dinamica -->
                            <div class="ui-widget">
                                <label>Cliente</label>
                                <input type="hidden" id="cliente_id" name="cliente_id" value="1" />
                                <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Escribe el nombre del cliente" value="Consumidor final" onkeyup="autocompleteData()" autocomplete="off" />

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Forma de pago</label>
                            <select id="forma_pago" name="forma_pago" class="form-control" required>
                                <option value="001">Efectivo</option>
                                <option value="002">Tarjeta</option>
                                <option value="001">Transferencia</option>

                            </select>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <!-- para agregar filas-->
                    <div class="row">
                        <!-- para que no utilice toda la pantalla que solo abarque 6 columnas de toda la pantalla-->
                        <div class="col-12 col-sm-4">

                            <input type="hidden" id="producto_id" name="producto_id" />



                            <!-- Dentro del input de codigo llamamos la funcion buscarProducto usando el onKeyup evento, this= es el tag o nombre del elemento,
                                this.value = lo que escribio el usuario  -->
                            <label>Codigo de barras</label>
                            <input class="form-control" id="codigo" name="codigo" type="text" placeholder="Escribe el código y enter" onkeyup="agregarProducto(event,this.value,1,<?php echo $idVentatmp ?>);" autofocus />


                            <br>

                        </div>
                        <!-- agregamos otro label para mostar el mensaje de error si encontro o no el producto-->
                        <div class="col-sm-2">
                            <label for="codigo" id="resultado_error" style="color:red"></label>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4">

                            <br>
                            <label style="font-weight: bold; font-size: 30px; text-align: center;">Total $</label>
                            <input type="text" id="total" name="total" size="7" readonly="true" value="0.00" style="font-weight: bold; font-size: 30px; text-align: center;">
                        </div>
                    </div>
                </div>
                <div class="form-group">

                    <div class="col-12 col-sm-4">
                        <button type="button" id="completa_venta" class="btn btn-success" data-toggle="modal" data-target="#modalError">Completar Venta</button>
                    </div>
                </div>
                <div class="row">
                    <table id="tablaProductos" class="table table-hover table-striped table-sm table-responsive tablaProductos" width="100%">
                        <thead class="thead-dark">
                            <th>#</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th width="1%"></th>
                        </thead>
                        <tbody></tbody>

                    </table>

                </div>
        </div>
        </form>

    </main>

    <script>
        $(function() {
            $('#cliente').autocomplete({
                source: "<?php echo base_url(); ?>clientes/autocompleteData", //el catalogo viene de un metodo que vamos a tener en el controlador clientes
                minLength: 3, //indicamos que inicie la consulta del cliente por codigo unicamente cuando sean escritos tres caracteres porque de lo contrario con un solo caracter
                //traeria mucha informaci'on
                select: function(event, ui) {
                    event.preventDefault();
                    $('#cliente_id').val(ui.item.id); //la informacion que traemos del  origen de datos y se lo estamos asignando al id cliente del formulario
                    $('#cliente').val(ui.item.value); //Value trae el nombre en la consulta realizada por el metodo es necesario asignarlo a value para que le metodo lo reconozca
                }


            });
        });
        $(function() {
            $('#codigo').autocomplete({
                source: "<?php echo base_url(); ?>productos/autocompleteData", //el catalogo viene de un metodo que vamos a tener en el controlador productos
                minLength: 3, //indicamos que inicie la consulta del producto por codigo unicamente cuando sean escritos tres caracteres porque de lo contrario con un solo caracter
                //traeria mucha informaci'on
                select: function(event, ui) {
                    event.preventDefault();
                    $('#codigo').val(ui.item.id); //la informacion que traemos del  origen de datos y se lo estamos asignando al id producto y el producto
                    $('#codigo').val(ui.item.value); //Value trae el nombre en la consulta realizada por el metodo es necesario asignarlo a value para que le metodo lo reconozca
                    setTimeout(
                        function() {
                            e = jQuery.Event("keypress"); //evento para que se de automaticamente enter y pasar el producto a mi tabla
                            e.which = 13; //codigo enter en tabla de codigo ascci 
                            agregarProducto(e, ui.item.id, 1, '<?php echo $idVentatmp ?>'); //usamos el metodo agregar producto y le enviamos el evento, el id del producto obtenido de la consulta y el id temporal de la venta
                        }
                    )


                }


            });
        });

        function agregarProducto(e, producto_id, cantidad, venta_id) { //ademas enviamos el evento ademas se debe realizar otra validacion para que solo con enter se inserte la venta
            let enterKey = 13;
            if (codigo != '') {
                if (e.which == enterKey) {

                    if (producto_id != null && producto_id != 0 && cantidad > 0) {
                        $.ajax({
                            // Es necesario crear la función buscarPorCodigo en nuestro controlador productos
                            url: '<?php echo base_url(); ?>temporalCompra/insertarVenta/' + producto_id + "/" + cantidad + "/" + venta_id,
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
                                        $("#precio_venta").val('');
                                        $("#subtotal").val('');
                                    }
                                }
                            }
                        });
                    }
                }
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
        $(function(){//funcion para completar venta detecta el evento del boton completar venta
            $("#completa_venta").click(function(){
                let nfilas = $("#tablaProductos tr").length;//es necesario validar que al menos existan dos filas la del encabezado y un item para poder guardar la compra 
                if(nfilas <2){
                    alert("Debe agregar un producto");
                }else{
                    $("#form_venta").submit();

                }

            });


        });



    </script>
