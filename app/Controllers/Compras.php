<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprasModel;
//es necesario llamar el modelo temporal compra para poder traer los datos de la compra final y guardarlos en la tabla compra
use App\Models\TemporalMovimientoModel;
use App\Models\DetalleCompraModel;
use App\Models\ProductosModel; //llamamos el modelo de productos para mediante un metod actualizar el stock
use App\Models\ConfiguracionModel;

class Compras extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $compras;
    protected $temporal_movimiento;
    protected $detalle_compra;
    protected $productos;
    protected $configuracion;
    protected $session;

    protected $reglas;

    public function __construct()
    {
        $this->session = session();
        $this->compras = new ComprasModel();
        $this->detalle_compra = new DetalleCompraModel();
        $this->configuracion = new ConfiguracionModel();
        $this->productos = new ProductosModel();
        //incluimos helper form para que trabajemos correctamente con el formulario y aplicar validaciones en cada campo
        helper(['form']);
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla compras
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de compras con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoComprasCatalogo');
        //consulta a la base de datos tabla compras trae todos los registros cuando activo sea = a la variable
        $compras = $this->compras->where('activo', $activo)->orderBy('fecha_alta', 'DESC')->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Compras',
            'compras' => $compras,
        ]);

        echo view('header', $data);
        echo view('compras/compras', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoComprasAgregar');
        $data = array_merge($this->permisosMenus);
        echo view('header',$data);
        echo view('compras/nuevo');
        echo view('footer');
    }
    //funcion para agregar registros en la tabla compras y eliminarlos de la tabla temporal movimiento al mmomento de confirmar la compra

    public function guarda()
    {
        // Recibimos el id de la compra del formulario
        $compra_id = $this->request->getPost('compra_id');
        $total = $this->request->getPost('total');

        // Quitar el formato de number_format eliminando comas y convertir a número flotante recuerda que le habiamos dado formato para mostrarlo formateado en la vista 
        //entonces es necesario quitarle ese format_number al total para poder guardarlo en la base de datos correctamente.
        // $totalSinFormato = (float)str_replace(',', '', $total);//esta es una forma de limpiar el total
        $totalSinFormato = preg_replace('/[\$,]/', '', $total); //otra forma de limpiar el formato del total para quitar el simbolo pesos, la coma y en las comillas vacias estamos diciendo 
        //que va a ser reemplazado por vacio esos caracteres no es bueno eliminar el punto porque el valor puede venir con decimales

        // Traemos los datos de la sesión para guardarlos en la compra
        $session = session();

        // Usamos el método insertar creado en el modelo para insertar la compra
        $resultadoId = $this->compras->insertaCompra($compra_id, $totalSinFormato, $session->id_usuario);

        // Solo lo vamos a usar en este método
        $this->temporal_movimiento = new TemporalMovimientoModel();

        if ($resultadoId) {
            $resultadoCompra = $this->temporal_movimiento->porCompra($compra_id);

            foreach ($resultadoCompra as $row) {
                $precioFormateado = number_format((float)$row['precio'], 2, '.', '');

                $this->detalle_compra->save([
                    'compra_id' => $resultadoId,
                    'producto_id' => $row['producto_id'],
                    'nombre' => $row['nombre'],
                    'cantidad' => $row['cantidad'],
                    'precio' => $precioFormateado,
                ]);

                $this->productos = new ProductosModel();
                $this->productos->actualizaStock($row['producto_id'], $row['cantidad']);
            }

            // Eliminamos la compra temporal
            $this->temporal_movimiento->eliminarCompra($compra_id); //es el id de la compra temporal ojo
        }

        return redirect()->to(base_url() . "compras/muestraCompraPdf/" . $resultadoId); //redireccionamos al metodo que nos genera el pdf despues de finalizar la compra
    }

    //creamos la funcion para visualizar las compras en pdf previo debemos descargar la libreria fpdf http://www.fpdf.org/ y extraer su contenido en la carpeta
    // app/ThirdParty para poder usarla en el proyecto
    function muestraCompraPdf($compra_id)
    {
        $data['compra_id'] = $compra_id;



        echo view('header');
        echo view('compras/ver_compra_pdf', $data);
        echo view('footer');
    }

    function generaCompraPdf($compra_id)
    {
        // Necesitamos la tabla de la compra y la configuración de la empresa
        // Consulta con datos de la compra
        $datosCompra = $this->compras->where('id', $compra_id)->first();
        if (!$datosCompra) {
            // Maneja el caso donde no se encuentra la compra
            die("Compra no encontrada");
        }

        // Traemos el detalle de esta compra haciendo varias consultas en una sola línea
        $detalleCompra = $this->detalle_compra->select('*')->where('compra_id', $compra_id)->findAll();
        if (!$detalleCompra) {
            // Maneja el caso donde no se encuentra el detalle de la compra
            die("Detalles de la compra no encontrados");
        }

        // Mostrar el nombre de la tienda y su dirección los traemos de la tabla configuracion en base de datos
        //ojo la consulta es diferente porque esta tabla en particular se creo de manera diferente
        //get getRow y de esta forma no nostrae el resultado como arreglos sino como objetos y solicitamor el campo solicitado en este caso valor
        $nombreTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;

        if (!$nombreTienda || !$direccionTienda) {
            // Maneja el caso donde no se encuentra la configuración de la tienda
            die("Configuración de la tienda no encontrada");
        }

        // Creación de nuestro PDF
        //     //creaci'on de nuestro pdf para lo cual necesitamos la libreria fpdf, es necesario cargarla primero en el app/autoload/classmap de nuestro proyecto
        //     // se coloca la diagonal inversa para que lo detecte
        $pdf = new \FPDF('P', 'mm', 'Letter'); // Orientación, medida, tamaño carta, se coloca diagonal inversa \ para que detecte la libreria
        $pdf->AddPage(); // Agregamos una página
        $pdf->SetMargins(10, 10, 10); // Márgenes del documento: izquierda, arriba, derecha
        $pdf->SetTitle('Compra'); // Título
        $pdf->SetFont('Arial', 'B', 10); // Definimos tipo de letra, estilo negrilla, tamaño 10 puntos
        $pdf->image(base_url() . '/images/logotipo.png', 185, 10, 20, 20, 'png'); // es necesario crear el directorio images en  la raiz del proyecto posicion en x, en y en ancho y alto

        // Añadir contenido al PDF
        //informaci'on de la tienda
        $pdf->Cell(195, 5, "Entrada de productos", 0, 1, 'C');
        $pdf->Cell(50, 5, $nombreTienda, 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 9); //podemos modificar la fuente y aplicarla al texto siguiente, para el manejo de tildes debemos decodificar con mb_convert_encoding
        $pdf->Cell(35, 5, mb_convert_encoding("Dirección de la tienda:", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9); //podemos modificar la fuente y aplicarla al texto siguiente
        $pdf->Cell(20, 5, $direccionTienda, 0, 1, 'L'); //el uno indica el salto de linea si cambia a cero queda en la misma fila
        //$pdf->Ln(10); // Espacio en blanco

        // Información de la compra
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 5, "# de Compra:", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(5, 5, $datosCompra['id'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, ("Fecha de compra:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9); //podemos modificar la fuente y aplicarla al texto siguiente
        $pdf->Cell(20, 5, $datosCompra['fecha_alta'], 0, 1, 'L'); //el uno indica el salto de linea si cambia a cero queda en la misma fila
        $pdf->Ln(10); // Espacio en blanco
        //cabecera de la tabla
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetFillColor(0, 0, 0,); //barra color negro en el texto
        $pdf->SetTextColor(255, 255, 255); //color del texto blanco
        $pdf->Cell(196, 5, 'Detalle de productos', 1, 1, 'C', 1); //solo toma valores de los colores si al final le colocamos 1 de lo contrario no lo toma

        $pdf->SetTextColor(0, 0, 0); //restablecemos el color a negro para continuar agregando informaci'on


        $pdf->Cell(14, 5, 'No', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Codigo', 1, 0, 'L');
        $pdf->Cell(77, 5, 'Nombre', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Precio', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Cantidad', 1, 0, 'L');
        $pdf->Cell(30, 5, 'Importe', 1, 1, 'L'); //para el salto de linea en el segundo item despues del texto agregamos 1
        //fin cabecera de la tabla   


        // Detalle de la compra
        $contador = 1;  //definimos un contador para ir enumerando los productos de la compra

        foreach ($detalleCompra as $detalle) {
            $pdf->Cell(14, 5, $contador, 1, 0, 'L');
            $pdf->Cell(25, 5, $detalle['compra_id'], 1, 0, 'L');
            $pdf->Cell(77, 5, mb_convert_encoding($detalle['nombre'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
            $pdf->Cell(25, 5, '$' . $detalle['precio'], 1, 0, 'L');
            $pdf->Cell(25, 5, $detalle['cantidad'], 1, 0, 'L');
            $importe = number_format($detalle['cantidad'] * $detalle['precio'], 2, '.', ','); //operamos precio por cantidad para obtener el importe en el reporte y le damos formato
            $pdf->Cell(30, 5, '$' . $importe, 1, 1, 'L');
            $contador++;
        }
        $pdf->Ln(10); // Espacio en blanco
        $pdf->SetFont('Arial', 'B', 9); //agregaos fuente al total, negrita y tama;o
        $pdf->Cell(195, 5, 'Total $' . number_format($datosCompra['total'], 2, '.', ','), 0, 1, 'R'); //traemos el todal de la base de datos que viene de la variable $datosCompra y le damos formato
        //0,1,'R'no tenga controno si tenga salto de linea y este a la derecha

        // Configuración del tipo de contenido para el navegador
        $this->response->setHeader('Content-Type', 'application/pdf');

        // Generar y enviar el PDF al navegador I para que lo envie al navegador
        $pdf->Output("compra_pdf.pdf", "I");
    }

    public function eliminar($id)
    {
         //verifico permisos del usuario para acceder a este modulo o acci'on
         $this->verificarAcceso('tienePermisoComprasEliminar');
        $detalles = $this->detalle_compra->where('compra_id', $id)->findAll();
        foreach ($detalles as $detalle) {

            $this->productos->actualizaStock($detalle['producto_id'], $detalle['cantidad'], '-');
        }
        //cambiamos el status de nuestra compra buscamos en la tabla compras por id el registro y el campo que queremos modificar en este caso el campo activo  
        $this->compras->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'compras'); //para que me lleve al controlador ventas

    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de clientes con estado 
    {
        //verifico permisos del usuario para acceder a este modulo o acci'on
        $this->verificarAcceso('tienePermisoComprasEliminados');
        $compras = $this->compras->where('activo', $activo)->orderBy('fecha_alta', 'DESC')->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus,['titulo' => 'Compras Eliminadas', 'compras' => $compras]);
        echo view('header',$data);
        echo view('compras/eliminados', $data);
        echo view('footer');
    }
}
