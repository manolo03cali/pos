<?php

namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model
{
    /**consultamos en la documentaci'on de codeigniter modeling data */
    protected $table      = 'logs';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_usuario', 'evento', 'ip', 'detalles'];

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

    //metodo para obtener todas los logs
    public function obtener()
    {


        $this->select('logs.*, u.usuario as usuario'); // para que me traiga todas los logs y traemos los campos requeridos
        $this->join('usuarios as u', 'logs.id_usuario = u.id'); // INNER JOIN PARA LAS RELACIONES
        $this->orderBy('logs.fecha_alta', 'DESC');
        $datos = $this->findAll();
        //print_r($this->getLastQuery());//funci'on de codeigniter que nos permite visualizar errores en nuestra consulta y la consulta en si para copiarla y ejecutarla directamente en la base de datos y 
        //hacer pruebas y ajustes en nuestra consulta como mostrar errores en pantalla por lo pronto despues de pruebas la podemos deshabilitar

        //este return me va a traer el id insertado de la  tabla compras lo vamos a utilizar pra poder insertarlo en la tabla detalle
        return $datos;
    }
}
