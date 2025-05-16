<?php
namespace App\Models;
use CodeIgniter\Model;

class ArqueoCajaModel extends Model
{
    /**consultamos en la documentaci'on de codeigniter modeling data */
    protected $table      = 'arqueo_caja';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['fecha_inicio', 'fecha_fin','monto_inicial','monto_final', 'total_ventas','estatus','cajas_id','usuarios_id'];

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

//metodo para obtener los arqueos * todolos datos que tiene la tabla
public function getDatos($idCaja){
    $this->select('arqueo_caja.*, cajas.nombre');//para saber el nombre de que caja estamos haciendo el arqueo 
    $this->join('cajas','arqueo_caja.cajas_id=cajas.id');//innerjoin entre cajas y arqueocajas
    $this->where('arqueo_caja.cajas_id', $idCaja);
    $this->orderBy('arqueo_caja.id', 'DESC');

    $datos = $this->findAll();//guardo y lo ejecuto con findAll
   // echo $this->getLastQuery();
    return $datos;
}
// public function verificaArqueo($id)
// {
//     // Inicializa la variable de acceso
//     $existeArqueo = false;

//     // Realiza la selección y unión de tablas
//     $existe = $this->select('arqueos.id')
//                    //->join('permisos', 'detalle_roles_permisos.permiso_id = permisos.id')
//                    ->where(['arqueos.id' => $id,])
//                    ->first();

//     // Imprime la consulta para depuración 
//    // echo $this->getLastQuery();

//     // Verifica si la consulta devolvió algún resultado
//     if ($existe !== null) {
//         $existeArqueo = true;
//     }

//     // Retorna si el rol tiene acceso
//     return $existeArqueo;
// }

}

?>