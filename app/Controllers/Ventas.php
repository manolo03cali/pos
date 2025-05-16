<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VentasModel;
//es necesario llamar el modelo temporal venta para poder traer los datos de la venta final y guardarlos en la tabla venta
use App\Models\TemporalMovimientoModel;
use App\Models\DetalleVentaModel;
use App\Models\ProductosModel; //llamamos el modelo de productos para mediante un metod actualizar el stock
use App\Models\ConfiguracionModel;
use App\Models\ClientesModel;
use App\Models\CajasModel;
use App\Models\ArqueoCajaModel;



class Ventas extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $ventas;
    protected $temporal_movimiento;
    protected $detalle_venta;
    protected $productos;
    protected $configuracion;
    protected $clientes;
    protected $cajas;
    protected $session;
    protected $arqueoModel;
    protected $reglasReporteVentasFecha;

    public function __construct()
    {
        $this->ventas = new VentasModel();
        $this->detalle_venta = new DetalleVentaModel();
        $this->productos = new ProductosModel();
        $this->configuracion = new ConfiguracionModel();
        $this->clientes = new ClientesModel();
        $this->cajas = new CajasModel();
        $this->session = session();
        $this->arqueoModel = new ArqueoCajaModel();



        //incluimos helper form para que trabajemos correctamente con el formulario y aplicar validaciones en cada campo
        helper(['form']);
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla ventas
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de ventas con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoMenuVentas');
        //consulta a la base de datos tabla ventas trae todos los registros cuando activo sea = 1 y usamos el metodo que creamos en el modelo para este fin 
        $datos = $this->ventas->obtener(1);
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, ['titulo' => 'Ventas', 'datos' => $datos]);

        echo view('header', $data);
        echo view('ventas/ventas', $data);
        echo view('footer');
    }
    public function ventasDia($activo = 1,) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de ventas con estado 1
    {
        //consulta a la base de datos tabla ventas trae todos los registros cuando activo sea = 1 y usamos el metodo que creamos en el modelo para este fin 
        $hoy = date('Y-m-d');
        $datos = $this->ventas->ventasDia(1, $hoy);
        //la informaci'on que le vamos a envi-ar a la vista
        $data = ['titulo' => 'Ventas', 'datos' => $datos];

        echo view('header');
        echo view('ventas/ventas_caja', $data);
        echo view('footer');
    }

    //funcion para pasar al formulario de agregar registros
    public function venta()
    {
        $existe = $this->arqueoModel->where(['cajas_id' => $this->session->cajas_id, 'estatus' => 1])->countAllResults(); //validacion para no crear nuevas cajas abiertas
        //si existe una caja que no ha cerrado validamos si existe un registro con caja abierta  solo quiero saber cuantos registros hay en estas condiciones para eso 
        //usamos el countAllResult
        if ($existe < 1) {
            $data = ['titulo' => 'Error apertura de caja', 'mensaje' => '"Debes aperturar una caja para iniciar ventas."'];
            echo view('header');
            echo view('errors/html/error_401', $data);
            echo view('footer');
        } else {
            //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
            $this->verificarAcceso('tienePermisoMenuCaja');
            $data = array_merge($this->permisosMenus, ['titulo' => 'Ventas por caja']);
            echo view('header', $data);
            echo view('ventas/caja');
            echo view('footer');
        }
    }
    //funcion para agregar registros en la tabla ventas y eliminarlos de la tabla temporal movimiento al mmomento de confirmar la venta

    public function guarda()
    {

        // Recibimos el id de la venta del formulario
        $venta_id = $this->request->getPost('venta_id');
        $total = $this->request->getPost('total');
        $cliente_id = $this->request->getPost('cliente_id');
        $forma_pago = $this->request->getPost('forma_pago');



        // $session = session();
        //traemos el id de la caja que esta guardado en la session del usuario  con el fin de crear un consecutivo para cada caja
        $caja = $this->cajas->where('id', $this->session->cajas_id)->first();
        $folio = $caja['folio']; //asignamos el resultado de la busqueda en la variable folio y asignamos el campo folio
        // Quitar el formato de number_format eliminando comas y convertir a número flotante recuerda que le habiamos dado formato para mostrarlo formateado en la vista 
        //entonces es necesario quitarle ese format_number al total para poder guardarlo en la base de datos correctamente.
        // $totalSinFormato = (float)str_replace(',', '', $total);//esta es una forma de limpiar el total
        $totalSinFormato = preg_replace('/[\$,]/', '', $total); //otra forma de limpiar el formato del total para quitar el simbolo pesos, la coma y en las comillas vacias estamos diciendo 
        //que va a ser reemplazado por vacio esos caracteres no es bueno eliminar el punto porque el valor puede venir con decimales

        // Traemos los datos de la sesión para guardarlos en la venta



        // Usamos el método insertar creado en el modelo para insertar la venta
        $resultadoId = $this->ventas->insertaVenta($folio, $totalSinFormato, $this->session->id_usuario, $this->session->cajas_id, $cliente_id, $forma_pago,);

        // Solo lo vamos a usar en este método
        $this->temporal_movimiento = new TemporalMovimientoModel();

        if ($resultadoId) {
            $folio++; //incrementamos el folio para obtener un consecutivo al momento de la venta luego actualizamos en base de datos el folio incrementado para ser aplicado a la siguiente venta
            $this->cajas->update($this->session->cajas_id, ['folio' => $folio]);
            $resultadoCompra = $this->temporal_movimiento->porCompra($venta_id);

            foreach ($resultadoCompra as $row) {
                $precioFormateado = number_format((float)$row['precio'], 2, '.', '');

                $this->detalle_venta->save([
                    'venta_id' => $resultadoId,
                    'producto_id' => $row['producto_id'],
                    'nombre' => $row['nombre'],
                    'cantidad' => $row['cantidad'],
                    'precio' => $precioFormateado,
                ]);

                $this->productos = new ProductosModel();
                $this->productos->actualizaStock($row['producto_id'], $row['cantidad'], '-'); //envio el simbolo de menos para restar y descontar stock al vender
            }

            // Eliminamos la venta temporal
            $this->temporal_movimiento->eliminarCompra($venta_id); //es el id de la venta temporal ojo
        }

        return redirect()->to(base_url() . "ventas/muestraTicket/" . $resultadoId); //redireccionamos al metodo que nos genera el pdf despues de finalizar la venta
    }
    function muestraTicket($venta_id)
    {
        $data['venta_id'] = $venta_id;



        echo view('header');
        echo view('ventas/ver_ticket', $data);
        echo view('footer');
    }
    function generaTicket($venta_id)
    {
        // Necesitamos la tabla de la venta y la configuración de la empresa
        // Consulta con datos de la venta
        $datosVenta = $this->ventas->where('id', $venta_id)->first();
        if (!$datosVenta) {
            // Maneja el caso donde no se encuentra la venta
            die("Compra no encontrada");
        }

        // Traemos el detalle de esta venta haciendo varias consultas en una sola línea
        $detalleVenta = $this->detalle_venta->select('*')->where('venta_id', $venta_id)->findAll();
        if (!$detalleVenta) {
            // Maneja el caso donde no se encuentra el detalle de la venta
            die("Detalles de la venta no encontrados");
        }

        // Mostrar el nombre de la tienda y su dirección los traemos de la tabla configuracion en base de datos
        $nombreTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;
        $leyendaTienda = $this->configuracion->select('valor')->where('nombre', 'ticket_leyenda')->get()->getRow()->valor;
        $clienteid = $datosVenta['cliente_id'];
        $cliente = $this->clientes->select('nombre')->where('id', $clienteid)->get()->getRow(); //realizamos consulta a db tabla clientes para obtener el nombre
        // cliente a partir del cliente_id de los datosVenta
        $nombreCliente = $cliente->nombre;
        if (!$nombreTienda || !$direccionTienda) {
            // Maneja el caso donde no se encuentra la configuración de la tienda
            die("Configuración de la tienda no encontrada");
        }
        // Creación de nuestro PDF
        $pdf = new \FPDF('P', 'mm', array(80, 200)); // Orientación, medida, tamaño
        $pdf->AddPage(); // Agregamos una página
        $pdf->SetMargins(5, 5, 5); // Márgenes del documento
        $pdf->SetTitle('Venta'); // Título
        $pdf->SetFont('Arial', 'B', 10); // Definimos tipo de letra
        $pdf->Ln(15); // Espacio en blanco
        $pdf->image(base_url() . '/images/logotipo.png', 30, 5, 20, 20, 'png'); // Logo
        // Información de la tienda
        $pdf->Cell(50, 5, $nombreTienda, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 5, $direccionTienda, 0, 1, 'L');
        // Información de la venta
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 5, "# de Compra:", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 5, $datosVenta['id'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, "Fecha de venta:", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 5, $datosVenta['fecha_alta'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(28, 5, "Numero de ticket:", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 5, $datosVenta['folio'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(12, 5, "Cliente:", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 5, $nombreCliente, 0, 1, 'L');

        $pdf->Ln(10); // Espacio en blanco

        // Cabecera de la tabla
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(7, 5, 'Cant.', 0, 0, 'L');
        $pdf->Cell(35, 5, mb_convert_encoding("Descripción", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
        $pdf->Cell(15, 5, 'Importe', 0, 1, 'L');

        // Detalle de la venta
        //$pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $contador = 1;

        foreach ($detalleVenta as $detalle) {
            $pdf->Cell(7, 5, $detalle['cantidad'], 0, 0, 'L');
            $pdf->Cell(35, 5, mb_convert_encoding($detalle['nombre'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
            $pdf->Cell(15, 5, '$' . $detalle['precio'], 0, 0, 'L');
            $importe = number_format($detalle['cantidad'] * $detalle['precio'], 2, '.', ',');
            $pdf->Cell(15, 5, '$' . $importe, 0, 1, 'L');
            $contador++;
        }

        // Total
        $pdf->Ln(10); // Espacio en blanco
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(65, 5, 'Total $' . number_format($datosVenta['total'], 2, '.', ','), 0, 1, 'R');
        $pdf->Cell(30, 5, $datosVenta['forma_pago'], 0, 1, 'L');

        $pdf->Ln(10); // Espacio en blanco
        $pdf->MultiCell(70, 5, $leyendaTienda, 0, 'C', 0); //multicel en caso de que tenga varias lineas parametros no tiene bordes, centrado, que no tenga fondo

        // Configuración del tipo de contenido para el navegador
        $this->response->setHeader('Content-Type', 'application/pdf');

        // Generar y enviar el PDF al navegador
        $pdf->Output("venta_pdf.pdf", "I");
    }
    //metodo para eliminar  la venta recibe el id de la venta y realizamos una consulta a db a la tabla detlle venta que coincida con el id de la venta traemos todos los registros
    //luego recorremos los registros con el foreach y usamos nuevamente el metodo creado en el modelo de productos para actualizar el stock enviando los parametros que requiere el metodo
    //  y el operador suma
    public function eliminar($id)
    {
        //verifico permisos del usuario al modulo
        $this->verificarAcceso('tienePermisoVentasEliminar');
        $detalles = $this->detalle_venta->where('venta_id', $id)->findAll();

        // cliente a partir del cliente_id de los datosVenta
        foreach ($detalles as $detalle) {

            $this->productos->actualizaStock($detalle['producto_id'], $detalle['cantidad'], '+');
        }
        //cambiamos el status de nuestra venta buscamos en la tabla ventas por id el registro y el campo que queremos modificar en este caso activo 
        $this->ventas->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'ventas'); //para que me lleve al controlador ventas

    }
    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de clientes con estado 
    {
        //verifico permisos del usuario al modulo
        $this->verificarAcceso('tienePermisoVentasEliminados');
        $datos = $this->ventas->obtener(0);
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, ['titulo' => 'Ventas eliminados', 'datos' => $datos]);

        echo view('header', $data);
        echo view('ventas/eliminados', $data);
        echo view('footer');
    }
    function mostrarVentasDia()
    {
        echo view('header');
        echo view('ventas/ventas_dia');
        echo view('footer');
    }
    public function generaVentasDiaPdf()
    {
        $nombreTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;
        $pdf = new \FPDF('P', 'mm', 'Letter'); // Orientación, medida, tamaño carta, se coloca diagonal inversa \ para que detecte la libreria
        $pdf->AddPage(); // Agregamos una página
        $pdf->SetMargins(10, 10, 10); // Márgenes del documento: izquierda, arriba, derecha
        $pdf->SetTitle('Reporte ventas del dia'); // Título
        $pdf->SetFont('Arial', 'B', 10); // Definimos tipo de letra, estilo negrilla, tamaño 10 puntos
        $pdf->image(base_url() . '/images/logotipo.png', 10, 10, 20, 20, 'png'); // es necesario crear el directorio images en  la raiz del proyecto posicion en x, en y en ancho y alto
        $pdf->Cell(0, 5, mb_convert_encoding($nombreTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Cell(0, 5, mb_convert_encoding($direccionTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Cell(0, 5, mb_convert_encoding("Reporte ventas del dia", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Ln(20);

        $pdf->Cell(40, 5, ("Fecha"), 1, 0, "C"); //alto,ancho,--borde 1, 0 salto de linea centrado
        $pdf->Cell(40, 5, ("Folio"), 1, 0, "C");
        $pdf->Cell(60, 5, mb_convert_encoding("Cliente", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Cell(30, 5, ("Total"), 1, 0, "C");
        $pdf->Cell(30, 5, ("Cajero"), 1, 0, "C");
        $pdf->Ln(5);
        $hoy = date('Y-m-d');
        $ventas = $this->ventas->ventasDia(1, $hoy);

        foreach ($ventas as $venta) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 5, $venta['fecha_alta'], 1, 0, "C"); //alto,ancho,--borde 1, 0 salto de linea centrado
            $pdf->Cell(40, 5, $venta['folio'], 1, 0, "C");
            $pdf->Cell(60, 5, mb_convert_encoding($venta['cliente'], 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
            $pdf->Cell(30, 5, $venta['total'], 1, 0, "C");
            $pdf->Cell(30, 5, $venta['cajero'], 1, 0, "C");
            $pdf->Ln(5);
        }
        $pdf->Output('VentasDia.pdf', 'I');
    }
    // Muestra el formulario para seleccionar el reporte
 public function seleccionarReporteVentas()
 {
    
    //verifico permisos del usuario al modulo
        $this->verificarAcceso('tienePermisoMenuFormularioReporteVentas');
        $cajas = $this->cajas->where('activo', 1)->findAll();
        $data = array_merge($this->permisosMenus, ['titulo' => 'Generar reporte por fechas', 'cajas' => $cajas]);

     echo view('header',$data);
     echo view('ventas/seleccionarReporte',$data);
     echo view('footer');
 }
 public function mostrarReporteVentas()
{
    try {
        // Verifico que el usuario tiene permisos para generar el reporte por fechas
        $this->verificarAcceso('tienePermisoMenuFormularioReporteVentas');

        // Obtener los parámetros enviados con POST
        $fechaInicio = $this->request->getPost('fecha_inicio');
        $fechaFinal = $this->request->getPost('fecha_final');
        $cajaId = $this->request->getPost('caja_id');

        // Validar los parámetros
        if (empty($fechaInicio) || empty($fechaFinal)) {
            return redirect()->back()->with('error', 'Todos los campos son requeridos.');
        }
         // Validar que la fecha de inicio no sea posterior a la fecha final
         if ($fechaInicio > $fechaFinal) {
            return redirect()->back()->with('error', 'La fecha de inicio no puede ser posterior a la fecha final.');
        }

        // Validación adicional (formato de fecha y caja_id)
        if (!strtotime($fechaInicio) || !strtotime($fechaFinal)) {
            return redirect()->back()->with('error', 'Las fechas no tienen un formato válido.');
        }

        if (!is_numeric($cajaId)) {
            return redirect()->back()->with('error', 'El ID de la caja debe ser un número.');
        }

        // Cargar datos en el array $data
        $data = array_merge($this->permisosMenus, [
            'fecha_inicio' => $fechaInicio,
            'fecha_final' => $fechaFinal,
            'caja_id' => $cajaId
        ]);

        // Cargar las vistas con los datos usando 
        echo view('header', $data);
        echo view('ventas/ver_reporte', $data);
        echo view('footer');

    } catch (\Exception $e) {
        // Manejo de errores inesperados
        log_message('error', $e->getMessage());
        return redirect()->back()->with('error', 'Ocurrió un error al generar el reporte.');
    }
}
 
public function generaReporteVentas($fechaInicio, $fechaFinal, $cajaId)
{
    try {
        // Iniciar el buffer de salida
        ob_start();

        // Validar que los parámetros no sean vacíos
        if (empty($fechaInicio) || empty($fechaFinal)) {
            ob_end_clean(); // Limpiar el buffer antes de redirigir
            return redirect()->back()->with('error', 'Los campos fechas son requeridos.');
        }
         // Validar que la fecha de inicio no sea posterior a la fecha final
         if ($fechaInicio > $fechaFinal) {
            ob_end_clean(); // Limpiar el buffer antes de redirigir
            return redirect()->back()->with('error', 'La fecha de inicio no puede ser posterior a la fecha final.');
        }

        // Validar el formato de las fechas y cajaId
        if (!strtotime($fechaInicio) || !strtotime($fechaFinal)) {
            ob_end_clean();
            return redirect()->back()->with('error', 'Formato de fechas inválido.');
        }

        if (!is_numeric($cajaId)) {
            ob_end_clean();
            return redirect()->back()->with('error', 'ID de caja inválido.');
        }

        // Obtener la configuración de la tienda
        $configuracionTienda = $this->configuracion
            ->select('nombre, valor')
            ->whereIn('nombre', ['tienda_nombre', 'tienda_direccion'])
            ->get()->getResultArray();

        $nombreTienda = $configuracionTienda[0]['valor'];
        $direccionTienda = $configuracionTienda[1]['valor'];

        // Consultar las ventas según los parámetros
        if ($cajaId === '0') {
            $resultado = $this->ventas->ventasPorRangoDeFechas($fechaInicio, $fechaFinal);
        } else {
            $resultado = $this->ventas->ventasPorRangoDeFechas($fechaInicio, $fechaFinal, $cajaId);
        }

        // Extraer los datos y el total de ventas
        $ventas = $resultado['listadoVentas'];
        $totalVentas = $resultado['totalVentas'];

        // Crear el PDF con FPDF
        $pdf = new \FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle('Reporte de ventas por fecha');
        $pdf->SetFont('Arial', 'B', 10);

        // Añadir logo, nombre y dirección de la tienda
        $this->addEncabezadoPDF($pdf, $nombreTienda, $direccionTienda, $fechaInicio, $fechaFinal);

        // Encabezados de la tabla
        $this->addTablaEncabezadosPDF($pdf);

        // Procesar los resultados y agregarlos al PDF
        foreach ($ventas as $venta) {
            $this->addFilaVentaPDF($pdf, $venta);
        }

        // Mostrar el total de ventas
        $this->addTotalVentasPDF($pdf, $totalVentas);

        // Configurar el tipo de contenido para el navegador
        $this->response->setHeader('Content-Type', 'application/pdf');

        // Limpiar el buffer de salida y generar el PDF
        ob_end_clean();
        $pdf->Output('VentasFecha.pdf', 'I'); // Modo 'I' para incrustar el PDF en el navegador
    } catch (\Exception $e) {
        // Manejo de errores inesperados
        ob_end_clean();
        log_message('error', $e->getMessage());
        return redirect()->back()->with('error', 'Ocurrió un error al generar el reporte.');
    }
}

 
 // Funciones auxiliares para separar la lógica del PDF
 private function addEncabezadoPDF($pdf, $nombreTienda, $direccionTienda, $fechaInicio, $fechaFinal)
 {
     $pdf->image(base_url() . '/images/logotipo.png', 10, 10, 20, 20, 'png');
     $pdf->Cell(0, 5, mb_convert_encoding($nombreTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
     $pdf->Cell(0, 5, mb_convert_encoding($direccionTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
     $pdf->Cell(0, 5, mb_convert_encoding("Reporte de ventas", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
     $pdf->SetY(10);
     $pdf->SetX(-60);
     $pdf->Cell(50, 5, mb_convert_encoding("Periodo: $fechaInicio a $fechaFinal", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
     $pdf->SetX(-60);
     $pdf->Cell(50, 5, mb_convert_encoding("Fecha y hora: " . date('Y-m-d H:i:s'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
     $pdf->Ln(20);
 }
 
 private function addTablaEncabezadosPDF($pdf)
 {
     $pdf->Cell(40, 5, "Fecha", 1, 0, "C");
     $pdf->Cell(40, 5, "Folio", 1, 0, "C");
     $pdf->Cell(60, 5, mb_convert_encoding("Cliente", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
     $pdf->Cell(30, 5, "Total", 1, 0, "C");
     $pdf->Cell(30, 5, "Cajero", 1, 0, "C");
     $pdf->Ln(5);
 }
 
 private function addFilaVentaPDF($pdf, $venta)
 {
     $pdf->SetFont('Arial', '', 10);
     $pdf->Cell(40, 5, $venta['fecha_alta'], 1, 0, "C");
     $pdf->Cell(40, 5, $venta['folio'], 1, 0, "C");
     $pdf->Cell(60, 5, mb_convert_encoding($venta['cliente'], 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
     $pdf->Cell(30, 5, $venta['total'], 1, 0, "C");
     $pdf->Cell(30, 5, $venta['cajero'], 1, 0, "C");
     $pdf->Ln(5);
 }
 
 private function addTotalVentasPDF($pdf, $totalVentas)
 {
     $pdf->Ln(10);
     $pdf->SetFont('Arial', 'B', 10);
     $pdf->Cell(170, 5, "Total de ventas:", 1, 0, "R");
     $pdf->Cell(30, 5, number_format($totalVentas, 2), 1, 0, "C");
 }





 
}


