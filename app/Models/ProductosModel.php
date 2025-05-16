<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    /**consultamos en la documentaci'on de codeigniter modeling data */
    protected $table      = 'productos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'codigo', 'nombre', 'precio_venta', 'precio_compra', 'existencias', 'stock_minimo',
        'inventariable', 'unidades_id', 'categorias_id', 'activo'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edit';
    protected $deletedField  = 'deleted_at';

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
    //Funcion para actualizar el stock de los productos
public function actualizaStock($producto_id, $cantidad, $operador='+'){//agregamos valor predefinido por si no recibe operador porque teneos metodos que no envian operador com es el caso de compras

    $this->set('existencias', "existencias$operador $cantidad", FALSE);//SET existencias = 'existencias+1' esto es incorrecto por eso agregamos El false es necesario para que nos lo envie comonumero y no como cadena 
    $this->where('id',$producto_id);
    $this->update();

}
public function totalProductos(){//agregamos valor predefinido por si no recibe operador porque teneos metodos que no envian operador com es el caso de compras
   return $this->where('activo',1)->countAllResults();//num_rows cuenta los resultados de la consulta
    

}
//Metodo que solo cuenta y da el total del productos con stock minimo
public function productosMinimo(){//agregamos valor predefinido por si no recibe operador porque teneos metodos que no envian operador com es el caso de compras
    $where="stock_minimo >= existencias AND inventariable =1 AND activo = 1";
     $this->where($where);
     $sql = $this->countAllResults();
     return $sql;
 
 }
//mostramos todos los productos que tienen stock por debajo del minimo
 public function getproductosMinimo(){//agregamos valor predefinido por si no recibe operador porque teneos metodos que no envian operador com es el caso de compras
    $where="stock_minimo >= existencias AND inventariable =1 AND activo = 1";
    return $this->where($where)->findAll();
     
 
 }

public function nombreycantProductosMasVendidos()
{
    // Define el mes y año actuales
    $mes = date('m');  // Mes actual (formato MM)
    $anio = date('Y'); // Año actual (formato YYYY)

    // Define el rango de fechas para el mes actual
    $fechaInicio = "$anio-$mes-01";
    $fechaFin = date("Y-m-t", strtotime($fechaInicio)); // Último día del mes

    // Selecciona el nombre del producto y la cantidad total vendida
    $this->select("productos.nombre, SUM(detalle_venta.cantidad) AS cantidad_vendida")
         ->join("detalle_venta", "productos.id = detalle_venta.producto_id") // Verifica que las columnas sean correctas
         ->where("detalle_venta.fecha_alta >=", $fechaInicio)
         ->where("detalle_venta.fecha_alta <=", $fechaFin)
         ->groupBy("productos.id")
         ->orderBy("cantidad_vendida", "DESC")
         ->limit(15); // Limita a los 10 productos más vendidos

    // Ejecuta la consulta y devuelve los resultados
    //echo $this->getLastQuery(); // Esto muestra la consulta generada (solo para depuración)
    return $this->findAll(); // Devuelve todos los resultados
}
public function listadoProductosActivos(){//agregamos valor predefinido por si no recibe operador porque teneos metodos que no envian operador com es el caso de compras
    $where="activo = 1";
     $this->where($where)
            ->orderBy("nombre", "DESC");
     $sql = $this->findAll();
     return $sql;
 
 }

}
