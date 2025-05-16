<?php

namespace App\Controllers;

use App\Models\ProductosModel;
use App\Models\VentasModel;
use App\Models\ComprasModel;
use Faker\Core\DateTime;
//Librerias  instaladas con composer para el maneo de hojas de excel con estilos
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class Inicio extends BaseController
{

    protected $productosModel;
    protected $ventasModel;
    protected $comprasModel;
    protected $session;
    public function __construct()
    {
        $this->productosModel = new ProductosModel();
        $this->ventasModel = new VentasModel();
        $this->comprasModel = new ComprasModel();
        $this->session = session();
    }
    public function index()
    {
        // Verifica si el usuario está logueado
        if (!isset($this->session->id_usuario)) {
            return redirect()->to(base_url());
        }

        // Carga los permisos de menús y submenús
        $permisosMenus = $this->cargarPermisosMenus();

        // Fecha de hoy
        $hoy = date('Y-m-d');

        // Obtén el total de ventas del día
        $totaldia = $this->ventasModel->totalDia($hoy);

        // Obtén el total de compras del día
        $totalComprasDia = $this->comprasModel->comprasTotalDia($hoy);
        // Obtén el total de productos registrados y activos y enviamos la variable a la vista
        $total = $this->productosModel->totalProductos();
        //Trae el conteo de productos con stock minimo y enviamos la variable a la vista
        $minimos = $this->productosModel->productosMinimo();
        //Obtenemos el listado de productos con stock minimo para el grafico--------------------------------
        $listadoMinimos = $this->productosModel->getproductosMinimo();
        // Inicializa los arrays para los datos del gráfico

        $nombresProductosMinimo = [];
        $existencias = [];
        $stockMinimo = [];

        // Recorre los productos más vendidos y organiza los datos para el gráfico
        foreach ($listadoMinimos as $producto) {
            $nombresProductosMinimo[] = $producto['nombre'];
            $existencias[] = (int) $producto['existencias'];
            $stockMinimo[] = (int) $producto['stock_minimo'];
        }
        //Estos datos los enviamos a la vista para usarlos en el grafico
        $nombresProductosMinimoJSON = json_encode($nombresProductosMinimo);
        $existenciasJSON = json_encode($existencias);
        $stockMinimoJSON = json_encode($stockMinimo);

        /*--------------------------------------------------------------*/

        // Obtener las ventas por semana y resultados variable a la vista----------------------------------------------------------------------
        $ventasPorSemana = $this->ventasModel->ventasSemanaPorDia($hoy);

        // Inicializa las variables para agrupar datos
        $datosAgrupados = [];
        $diasDeLaSemana = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Mapa de traducción de días en inglés a español
        $diasEnEspanol = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        // Agrupa los datos de ventas por día de la semana
        foreach ($ventasPorSemana as $venta) {
            $fecha = new \DateTime($venta['fecha']);
            $nombreDia = $fecha->format('l'); // Obtiene el nombre completo del día (por ejemplo, 'Monday')

            if (!isset($datosAgrupados[$nombreDia])) {
                $datosAgrupados[$nombreDia] = 0;
            }

            $datosAgrupados[$nombreDia] += $venta['total_ventas'];
        }

        // Prepara los datos para el gráfico
        $nombresDias = [];
        $totales = [];
        $valorMaximo = 0;

        foreach ($diasDeLaSemana as $dia) {
            $nombresDias[] = $diasEnEspanol[$dia]; // Traduce el nombre del día al español
            $totalDia = isset($datosAgrupados[$dia]) ? $datosAgrupados[$dia] : 0;
            $totales[] = $totalDia;

            // Actualiza el valor máximo para el rango del eje Y
            if ($totalDia > $valorMaximo) {
                $valorMaximo = $totalDia;
            }
        }

        // Obténer los productos más vendidos y sus cantidades usando el modelo------------------------------------------------------------------------
        $productosMasVendidos = $this->productosModel->nombreycantProductosMasVendidos();
        // Inicializa los arrays para los datos del gráfico
        $nombresProductos = [];
        $cantidadesVendidas = [];
        // Recorre los productos más vendidos y organiza los datos para el gráfico
        foreach ($productosMasVendidos as $producto) {
            $nombresProductos[] = $producto['nombre'];
            $cantidadesVendidas[] = (int) $producto['cantidad_vendida'];
        }

        // Convertir a JSON para JavaScript para enviarlos a la vista y usarlos en el grafico
        $nombresDiasJSON = json_encode($nombresDias);
        $totalesJSON = json_encode($totales);
        $nombresProductosJSON = json_encode($nombresProductos);
        $cantidadesVendidasJSON = json_encode($cantidadesVendidas);

        // Pasamos todos los datos recopilados a la vista
        $data = array_merge($permisosMenus, [

            'total' => $total,
            'totaldia' => $totaldia,
            'totalComprasDia' => $totalComprasDia,
            'minimos' => $minimos,
            /*------------------Datos para grafico de barras ventas de la semana-----------------------*/
            'nombresDias' => $nombresDiasJSON,
            'totales' => $totalesJSON,
            'valorMaximo' => $valorMaximo, // Pasar el valor máximo a la vista
            /*------------------Datos para grafico de productos mas vendidos en el mes----------------------*/
            'nombresProductos' => $nombresProductosJSON,
            'cantidadesVendidas' => $cantidadesVendidasJSON,
            /*------------------Datos para el grafico de productos con stock minimo----------------------*/
            'nombresProductosMinimo' => $nombresProductosMinimoJSON,
            'existencias' => $existenciasJSON,
            'stockMinimo' => $stockMinimoJSON,

        ]);

        echo view('header', $data);
        echo view('inicio', $data);
        echo view('footer');
    }



    public function excel()
    {

        //$helper->log('Create new Spreadsheet object');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        // Agregar datos a la hoja de cálculo
        $sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'Codigo');
        $sheet->setCellValue('C1', 'Nombre');
        $sheet->setCellValue('D1', 'Precio de venta');
        $sheet->setCellValue('E1', 'Existencias');
        // Establecer estilo para los títulos (negrita, alineación, color de fondo)
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'], // Texto blanco
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '4F81BD', // Color de fondo azul
                ],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => '000000'], // Bordes negros gruesos
                ],
            ],
        ]);

        $productos = $this->productosModel->where('activo', 1)->findAll();

        $row = 2;
        foreach ($productos as $producto) {
            $sheet->setCellValue('A' . $row, $producto['id']);
            $sheet->setCellValue('B' . $row, $producto['codigo']);
            $sheet->setCellValue('C' . $row, $producto['nombre']);
            $sheet->setCellValue('D' . $row, $producto['precio_venta']);
            $sheet->setCellValue('E' . $row, $producto['existencias']);

            $row++;
        }


        // Crear el archivo en formato Xlsx
        $writer = new Xlsx($spreadsheet);

        // Definir el nombre del archivo
        $fileName = 'mi_archivo.xlsx';

        // Configurar las cabeceras HTTP para la descarga del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo para descarga
        $writer->save('php://output');
        exit;
    }
}
