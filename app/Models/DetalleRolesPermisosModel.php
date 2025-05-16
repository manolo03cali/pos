<?php
namespace App\Models;
use CodeIgniter\Model;

class DetalleRolesPermisosModel extends Model
{
    /**consultamos en la documentaci'on de codeigniter modeling data */
    protected $table      = 'detalle_roles_permisos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['rol_id', 'permiso_id'];

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

    public function verificaPermisos($idRol, $permiso)
{
    // Inicializa la variable de acceso
    $tieneAcceso = false;

    // Realiza la selección y unión de tablas
    $existe = $this->select('detalle_roles_permisos.id')
                   ->join('permisos', 'detalle_roles_permisos.permiso_id = permisos.id')
                   ->where(['detalle_roles_permisos.rol_id' => $idRol, 'permisos.nombre' => $permiso])
                   ->first();

    // Imprime la consulta para depuración 
   // echo $this->getLastQuery();

    // Verifica si la consulta devolvió algún resultado
    if ($existe !== null) {
        $tieneAcceso = true;
    }

    // Retorna si el rol tiene acceso
    return $tieneAcceso;
}





}

?>