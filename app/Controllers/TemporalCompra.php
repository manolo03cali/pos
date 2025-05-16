<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TemporalMovimientoModel;
use App\Models\ProductosModel;

class TemporalCompra extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $temporal_movimiento, $productos;

    protected $reglas;
    //relaci'on controlador 
    //con este constructor importamod el modelo de temporal_movimiento para interactuar con el
    public function __construct()
    {
        $this->temporal_movimiento = new TemporalMovimientoModel();
        $this->productos = new ProductosModel();
    }

    //funcion para agregar registros en la tabla temporal
    public function insertarCompra($producto_id, $cantidad, $compra_id)
    {
        $error = '';
        $producto = $this->productos->where('id', $producto_id)->first();
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($producto) {
            //llamamos la consulta que creamos directamente en el modelo para verificar si existen los productos validaci'on para no agregar registro doble 
            $datosExiste = $this->temporal_movimiento->porIDProductoCompra($producto_id, $compra_id);

            if ($datosExiste) { //si existe significa que el registro esta en la tabla 
                //cantidad lo que trae mi tabla es decir lo que ya he registrado + $cantidad que estoy recibiendo en mi metodo para tener una nueva cantidad
                $cantidad = $datosExiste->cantidad + $cantidad; // no es necesario manejarlo como arreglo en este caso
                //subtotal se opera cantidad por el datosExiste y el precio que tiene registrado en la tabla temporal
                $subtotal = $cantidad * $datosExiste->precio;
                //Aqui en la vist se llama $compra_id y no folio pero hace referencia al valor que enviamos como folio al metodo actualizar
                $this->temporal_movimiento->actualizarProductoCompra($producto_id, $compra_id, $cantidad, $subtotal); //llamamos el metodo que actualiza la cantidad y el subtotal  proceso realizado en el modelo

            } else { //ocurre cuando no hemos ingresado el producto nuevamente consultamos los datos del producto en la base de datos para evitar el el usuario manipule el html
                // y modifique el valor del producto un ejemplo por cero a su favor
                $subtotal = $cantidad * $producto['precio_compra']; //en este caso si lo manejamos como arreglo

                //hacemos una insercion directa con save
                $this->temporal_movimiento->save([
                    'folio' => $compra_id, // el folio es el mismo id del producto
                    'producto_id' => $producto_id,
                    'codigo' => $producto['codigo'], // tambien lo traemos de la consulta no dejamos que el usuario lo envie directamente
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio_compra'],
                    'cantidad' => $cantidad, //lo traemos de la variable cantidad que acabamos de operar en este if
                    'subtotal' => $subtotal, //lo traemos de la variable subtotal que acabamos de operar en este if

                ]);
            }
        } else { //si el producto no existe
            $error = 'no existe el producto';
        }

        $res['datos'] = $this->cargaProductos($compra_id); //llamamos la funcion que creamos para cargar los productos y la enviamos en el arreglo res datos
        //separador de decimales '.' de miles ',' usando el number_format para que me mueste el total formtateado con miles y decimales
        $res['total'] = number_format($this->totalProductos($compra_id), 2, '.', ','); //llamamos la funcion que creamos para cargar subtotales y sumarlos para obtener el total
        $res['error'] = $error;
        echo json_encode($res);
    }
    //funcion para agregar registros en la tabla temporal
    public function insertarVenta($producto_id, $cantidad, $compra_id)
    {
        $error = '';
        $producto = $this->productos->where('id', $producto_id)->first();
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($producto) {
            //llamamos la consulta que creamos directamente en el modelo para verificar si existen los productos validaci'on para no agregar registro doble 
            $datosExiste = $this->temporal_movimiento->porIDProductoCompra($producto_id, $compra_id);

            if ($datosExiste) { //si existe significa que el registro esta en la tabla 
                //cantidad lo que trae mi tabla es decir lo que ya he registrado + $cantidad que estoy recibiendo en mi metodo para tener una nueva cantidad
                $cantidad = $datosExiste->cantidad + $cantidad; // no es necesario manejarlo como arreglo en este caso
                //subtotal se opera cantidad por el datosExiste y el precio que tiene registrado en la tabla temporal
                $subtotal = $cantidad * $datosExiste->precio;
                //Aqui en la vist se llama $compra_id y no folio pero hace referencia al valor que enviamos como folio al metodo actualizar
                $this->temporal_movimiento->actualizarProductoCompra($producto_id, $compra_id, $cantidad, $subtotal); //llamamos el metodo que actualiza la cantidad y el subtotal  proceso realizado en el modelo

            } else { //ocurre cuando no hemos ingresado el producto nuevamente consultamos los datos del producto en la base de datos para evitar el el usuario manipule el html
                // y modifique el valor del producto un ejemplo por cero a su favor
                $subtotal = $cantidad * $producto['precio_venta']; //en este caso si lo manejamos como arreglo

                //hacemos una insercion directa con save
                $this->temporal_movimiento->save([
                    'folio' => $compra_id, // el folio es el mismo id del producto
                    'producto_id' => $producto_id,
                    'codigo' => $producto['codigo'], // tambien lo traemos de la consulta no dejamos que el usuario lo envie directamente
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio_venta'],
                    'cantidad' => $cantidad, //lo traemos de la variable cantidad que acabamos de operar en este if
                    'subtotal' => $subtotal, //lo traemos de la variable subtotal que acabamos de operar en este if

                ]);
            }
        } else { //si el producto no existe
            $error = 'no existe el producto';
        }

        $res['datos'] = $this->cargaProductos($compra_id); //llamamos la funcion que creamos para cargar los productos y la enviamos en el arreglo res datos
        //separador de decimales '.' de miles ',' usando el number_format para que me mueste el total formtateado con miles y decimales
        $res['total'] = number_format($this->totalProductos($compra_id), 2, '.', ','); //llamamos la funcion que creamos para cargar subtotales y sumarlos para obtener el total
        $res['error'] = $error;
        echo json_encode($res);
    }




    public function cargaProductos($compra_id)
    {
        //funtion para crear la tabla concatenando html de acuerso a la informaci'on recibida de la base de datos temporalCompras

        $resultado = $this->temporal_movimiento->porCompra($compra_id);
        $fila = '';
        $numFila = 0;
        foreach ($resultado as $row) {
            $numFila++;
            $fila .= "<tr id ='fila" . $numFila . "'>";
            $fila .= "<td>" . $numFila . "</td>";
            $fila .= "<td>" . $row['codigo'] . "</td>";
            $fila .= "<td>" . $row['nombre'] . "</td>";
            $fila .= "<td>" . $row['precio'] . "</td>";
            $fila .= "<td>" . $row['cantidad'] . "</td>";
            $fila .= "<td>" . $row['subtotal'] . "</td>";
            $fila .= "<td><a onclick=\"eliminaProducto(" . $row['producto_id'] . ",'" . $compra_id . "')\" class = 'borrar'><span class='fas fa-fw fa-trash'></span></a></td>";
            $fila .= "</tr>";
        }
        return $fila;
    }
    public function totalProductos($compra_id)
    {
        //funtion para crear la tabla concatenando html de acuerso a la informaci'on recibida de la base de datos temporalCompras

        $resultado = $this->temporal_movimiento->porCompra($compra_id);
        $total = 0;

        foreach ($resultado as $row) {

            $total += $row['subtotal']; //traemos todos los subtotales y los vamos sumando para tener el total de la compra



        }

        return $total;
    }
    public function eliminaProducto($producto_id, $compra_id)
    {
        //buscamos el producto a eliminar pero por compra con el id de la compra, validamos si la compra existe luego si la cantidad de la compra por producto es mayor a 1
        //entonces actualice la cantidad restandole 1 y actualice el subtodal  cantidad por datosExiste
        $datosExiste = $this->temporal_movimiento->porIDProductoCompra($producto_id, $compra_id);

        if ($datosExiste) {
            if ($datosExiste->cantidad > 1) {
                $cantidad = $datosExiste->cantidad - 1;
                // se genera un nuevo subtotal
                $subtotal = $cantidad * $datosExiste->precio;
                //generamos una nueva actualizacion usando otro metodo previamente creado que actualiza la compra  en el modelo
                $this->temporal_movimiento->actualizarProductoCompra($producto_id, $compra_id, $cantidad, $subtotal);
            } else {
                //En caso de que solo exista una compra con cantidad =1 lo que hacemos es eliminar el registro de la tabla entonces 
                //llamamos otra funcion que creamos en el modelo para eliminar el registro de la tabla recuerda que $compra_id hace referencia al folio
                $this->temporal_movimiento->eliminarProductoCompra($producto_id, $compra_id);
            }
        }
        //luego es necesario cargar nuevamente la informaci'on de nuevo
        $res['datos'] = $this->cargaProductos($compra_id); //llamamos la funcion que creamos para cargar los productos y la enviamos en el arreglo res datos
        //separador de decimales '.' de miles ',' usando el number_format para que me mueste el total formtateado con miles y decimales
        $res['total'] = number_format($this->totalProductos($compra_id), 2, '.', ','); //llamamos la funcion que creamos para cargar subtotales y sumarlos para obtener el total
        $res['error'] = '';
        echo json_encode($res);
    }
}
