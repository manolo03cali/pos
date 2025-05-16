<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class VentasModel extends Model
{
    /**consultamos en la documentaci'on de codeigniter modeling data */
    protected $table      = 'ventas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'folio',
        'total',
        'activo',
        'forma_pago',
        'usuario_id',
        'caja_id',
        'cliente_id'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edit';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function insertaVenta($venta_id, $total, $usuario_id, $caja_id, $cliente_id, $forma_pago)
    {


        $this->insert([
            'folio' => $venta_id,
            'total' => $total,
            'usuario_id' => $usuario_id,
            'caja_id' => $caja_id,
            'cliente_id' => $cliente_id,
            'forma_pago' => $forma_pago
        ]);
        //este return me va a traer el id insertado de la  tabla compras lo vamos a utilizar pra poder insertarlo en la tabla detalle
        return $this->insertID();
    }
    //metodo para obtener todas las ventas
    public function obtener($activo = 1)
    {


        $this->select('ventas.*, u.usuario as cajero, c.nombre as cliente'); // para que me traiga todas las ventas y traemos los campos requeridos
        $this->join('usuarios as u', 'ventas.usuario_id = u.id'); // INNER JOIN PARA LAS RELACIONES
        $this->join('clientes as c', 'ventas.cliente_id = c.id'); // INNER JOIN 
        $this->where('ventas.activo', $activo);
        $this->orderBy('ventas.fecha_alta', 'DESC');

        $datos = $this->findAll();
        //print_r($this->getLastQuery());//funci'on de codeigniter que nos permite visualizar errores en nuestra consulta y la consulta en si para copiarla y ejecutarla directamente en la base de datos y 
        //hacer pruebas y ajustes en nuestra consulta como mostrar errores en pantalla por lo pronto despues de pruebas la podemos deshabilitar

        //este return me va a traer el id insertado de la  tabla compras lo vamos a utilizar pra poder insertarlo en la tabla detalle
        return $datos;
    }

    //metodo para traer las ventas del dia
    public function ventasDia($activo = 1, $fecha)
    {
        $this->select('ventas.*, u.usuario as cajero, c.nombre as cliente'); // para que me traiga todas las ventas y traemos los campos requeridos
        $this->join('usuarios as u', 'ventas.usuario_id = u.id'); // INNER JOIN PARA LAS RELACIONES
        $this->join('clientes as c', 'ventas.cliente_id = c.id'); // INNER JOIN 
        $this->where('ventas.activo', $activo);
        $this->where('DATE(ventas.fecha_alta)', $fecha); // Si la columna es de tipo datetime o date
        $this->orderBy('ventas.fecha_alta', 'DESC');

        $datos = $this->findAll();

        return $datos;
    }

    //no tengo que colocar la tabla porque ya estamos en el modelo de ventas
    //funci'n para sumar el total de ventas realizadas en la fecha indicada
    public function totalDia($fecha)
    {
        $this->select("sum(total) as total");
        $where = "activo = 1 AND DATE(fecha_alta)= '$fecha'";
        //print_r($this->getLastQuery());
        return $this->where($where)->first();
    }
    public function numVentasDia($fecha)
{
    // Selecciona todos los registros activos en la fecha especificada
   // $this->select("sum(total) as total");
    $this->where("activo", 1);
    $this->where("DATE(fecha_alta)", $fecha);
    
    // Cuenta el número de ventas
    return $this->countAllResults();
}
public function ventasSemanaPorDia($fecha)
{
    // Ajusta el rango de fechas para la semana en curso
    $inicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fecha)));
    $finSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fecha)));

    // Construye la consulta SQL
    $this->select("fecha_alta AS fecha, SUM(total) AS total_ventas"); // Selecciona fecha con alias
    $this->where("fecha_alta >= ", $inicioSemana);
    $this->where("fecha_alta <= ", $finSemana);
    $this->where("activo", 1);
    $this->groupBy("fecha");
    $this->orderBy("fecha", "ASC");

    // Ejecuta la consulta y devuelve los resultados
    return $this->findAll(); // Devuelve todos los resultados, con total_ventas por cada día
}
// Método para obtener ventas por rango de fechas y caja
public function ventasPorRangoDeFechas($fechaInicio, $fechaFin, $cajaId = null)
{
    // Inicialización del constructor de la consulta con JOINs y filtros de fecha
    $ventasBuilder = $this->select('ventas.*, u.usuario as cajero, c.nombre as cliente')
                          ->join('usuarios as u', 'ventas.usuario_id = u.id')
                          ->join('clientes as c', 'ventas.cliente_id = c.id')
                          ->where('DATE(ventas.fecha_alta) >=', $fechaInicio)
                          ->where('DATE(ventas.fecha_alta) <=', $fechaFin);

    // Si se especifica un ID de caja, agregar el filtro
    if (!is_null($cajaId) && (int)$cajaId !== 0) {
        $ventasBuilder->where('ventas.caja_id', $cajaId);
    }

    // Ordenar por la fecha de alta descendente
    $ventasBuilder->orderBy('ventas.fecha_alta', 'DESC');

    // Obtener los datos de la consulta en un solo paso, incluyendo el total de ventas
    $ventas = $ventasBuilder->findAll();

    // Calcular el total de ventas directamente en la misma consulta para evitar duplicar el código
    $totalVentas = array_sum(array_column($ventas, 'total'));

    return [
        'listadoVentas' => $ventas,
        'totalVentas' => $totalVentas
    ];
}

}
