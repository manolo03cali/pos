<?php

namespace App\Models;

use CodeIgniter\Model;

class TemporalMovimientoModel extends Model
{
    /**consultamos en la documentaci'on de codeigniter modeling data */
    protected $table      = 'temporal_movimiento';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'folio', 'codigo', 'nombre', 'cantidad','precio','subtotal','producto_id'
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

    //es necesario agregar una funci'on voy a buscar en mi tabla si existe el producto que yo quiero agregar 
    public function porIDProductoCompra($producto_id, $folio){
        //forma diferente para realizar consultas desde el modelo 
        $this->select('*');
        $this->where('folio', $folio);
        $this->where('producto_id', $producto_id);
        $datos = $this->get()->getRow();//como objetos
        return $datos;
    }
    //consulta para traer todos los registros de la tabla temporalcompra por id
    public function porCompra($folio){
        //forma diferente para realizar consultas desde el modelo 
        $this->select('*');
        $this->where('folio', $folio);
        $datos = $this->findAll(); //con findall nos permite trabajar la consulta como arreglos
        return $datos;
    }
    //actualiza el producto temporalcompra a medida que lo agregamos y ya exista en la tabla es decir actualiza cantidad y subtotal a medida que el usuario agrega los productos
    public function actualizarProductoCompra($producto_id, $folio, $cantidad, $subtotal){
        //las dos columnas que vamos a actualizar
        $this->set('cantidad', $cantidad);
        $this->set('subtotal', $subtotal);
        //veriricamos el registro a actualizar con el producto_id y el folio
        $this->where('producto_id', $producto_id);
        $this->where('folio', $folio);
        $datos = $this->update(); //actualizamos el registro
        
    }
      //Creamos otra consulta para el caso en que al momento de eliminar u item de  temporalcompra su cantidad sea igual a 1 ya que no se actualiza si no que se elimina el registro
      public function eliminarProductoCompra($producto_id, $folio){
        //las dos columnas que vamos a actualizar
      
        //veriricamos el registro a eliminar con el producto_id y el folio
        $this->where('producto_id', $producto_id);
        $this->where('folio', $folio);
        $datos = $this->delete(); //eliminamos el registro
        
    }
//creamos una funcion para eliminar la compra temporal
    public function eliminarCompra($folio){
        
      
        //veriricamos el registro a eliminar con   el folio
       
        $this->where('folio', $folio);
        $this->delete(); //eliminamos el registro
        
    }
    
}
