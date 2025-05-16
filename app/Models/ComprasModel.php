<?php

namespace App\Models;

use CodeIgniter\Model;

class ComprasModel extends Model
{
    /**consultamos en la documentaci'on de codeigniter modeling data */
    protected $table      = 'compras';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'folio', 'total', 'activo', 'usuarios_id'
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

    public function insertaCompra($compras_id, $total, $id_usuario){
        
        $this->insert([
            'folio'=> $compras_id,
            'total'=> $total,
            'usuarios_id'=> $id_usuario
        ]);
        //este return me va a traer el id insertado de la  tabla compras lo vamos a utilizar pra poder insertarlo en la tabla detalle
        return $this->insertID();

    }
    public function comprasTotalDia($fecha)
    {
        $this->select("sum(total) as total");
        $where = "activo = 1 AND DATE(fecha_alta)= '$fecha'";
        //print_r($this->getLastQuery());
        return $this->where($where)->first();
    }
     
}
