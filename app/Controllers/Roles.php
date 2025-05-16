<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RolesModel;
use App\Models\PermisosModel;
use App\Models\DetalleRolesPermisosModel;


class Roles extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string
    protected $reglas; //variable para crear las reglas de validaci'on
    protected $roles;
    protected $permisos;
    protected $session;
    protected $detalleRoles;

    //relaci'on controlador 
    //con este constructor importamod el modelo de unidades para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->roles = new RolesModel();
        $this->permisos = new permisosModel();
        $this->detalleRoles = new DetalleRolesPermisosModel();
        helper(['form']);
        $this->reglas = [
            'nombre' => [
                'rules' => 'required|min_length[1]|max_length[50]|regex_match[/^[\p{L}0-9\s#\-]+$/u]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length' => 'El campo {field} debe tener al menos 1 carácter.',
                    'max_length' => 'El campo {field} no puede superar los 50 caracteres. Utilice nombres cortos y legibles.',
                    'regex_match' => 'El campo {field} solo puede contener letras, números, espacios, el símbolo # y guiones (-).',

                ]
            ]
        ];
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla roles
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoRolesCatalogo');

        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = 1
        $roles = $this->roles->where('activo', $activo)->orderBy('id', 'ASC')->findAll();
        //la informaci'on que le vamos a enviar a la vista
        //$data = ['titulo' => 'Roles', 'datos' => $roles];
        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Roles',
            'datos' => $roles,
        ]);

        echo view('header', $data);
        echo view('roles/roles', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        //Verificamos permisos del usuario al modulo
        $this->verificarAcceso('tienePermisoRolesAgregar');
        $data = array_merge($this->permisosMenus, ['titulo' => 'Agregar rol']);

        echo view('header', $data);
        echo view('roles/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function insertar()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->roles->save([
                'nombre' => $this->request->getpost('nombre')
            ]);
            return redirect()->to(base_url() . 'roles');
        } else {
            //return $this->nuevo($this->request->getpost('id'),$this->validator);
            $data = ['titulo' => 'Agregar rol', 'validation' => $this->validator];

            echo view('header');
            echo view('roles/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoRolesEditar');
        $rol = $this->roles->where('id', $id)->first(); //trae el dato que vamos a editar en una consulta
        $data = array_merge($this->permisosMenus, ['titulo' => 'Editar rol', 'datos' => $rol, 'validation' => $valid]); //enviamos el dato consultado a la vista para despues ser editado en la variable datos

        echo view('header', $data);
        echo view('roles/editar', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function actualizar()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->roles->update($this->request->getpost('id'), [
                'nombre' => $this->request->getpost('nombre')
            ]);
            return redirect()->to(base_url() . 'roles');
        } else {
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar unidades no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista unidades
    public function eliminar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoRolesEliminar');
        $this->roles->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'roles');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 
    {
        //Verificamos permisos del usuario al modulo
        $this->verificarAcceso('tienePermisoRolesEliminados');
        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = 0 la variable
        $roles = $this->roles->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, ['titulo' => 'Roles eliminados', 'datos' => $roles]);

        echo view('header', $data);
        echo view('roles/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoRolesReingresar');
        $this->roles->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'roles');
    }

    public function detalles($idRol, $successMessage = null)
    {
        // Verifico permisos del usuario para este módulo o acción
        $this->verificarAcceso('tienePermisoRolesDetalle');

        // Obtengo los permisos con sus respectivas categorías, este metodo fue creado en el modelo de permisosMOdel
        $permisos = $this->permisos->PermisosConCategorias();

        // Obtengo el nombre del rol
        $nombreRol = $this->roles->where('id', $idRol)->first()['nombre'];

        // Obtengo los permisos asignados al rol
        $permisosAsignados = $this->detalleRoles->where('rol_id', $idRol)->findAll();

        // Mapa para permisos asignados
        $datos = [];

        // Recorro los permisos asignados para marcarlos como true
        foreach ($permisosAsignados as $permisoAsignado) {
            $datos[$permisoAsignado['permiso_id']] = true;
        }

        // Agrupo los permisos por nombre de categoría
        $permisosAgrupados = [];
        foreach ($permisos as $permiso) {
            $categoria = $permiso['categoria'] ?: 'Sin categoría';  // Usamos el nombre de la categoría o 'Sin categoría'
            $permisosAgrupados[$categoria][] = $permiso;
        }

        // Preparar los datos para la vista
        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Asignación de permisos',
            'permisosAgrupados' => $permisosAgrupados,  // Enviar permisos agrupados por nombre de categoría
            'rol_id' => $idRol,
            'asignado' => $datos,
            'nombreRol' => $nombreRol,
            'success' => $successMessage  // Mensaje de éxito si existe
        ]);

        // Cargar las vistas
        echo view('header', $data);
        echo view('roles/detalles', $data);
        echo view('footer');
    }





    public function guardaPermisos()
    {
        if ($this->request->getMethod() == 'POST') {
            // Recibir el ID del rol desde los datos POST
            $idRol = $this->request->getPost('rol_id');

            // Recibir el array de permisos desde los datos POST
            $permisos = $this->request->getPost('permisos');

            // Verificar que el ID del rol no esté vacío
            if (!empty($idRol)) {
                // Limpiar los permisos anteriores del rol
                $this->detalleRoles->where('rol_id', $idRol)->delete();

                // Verificar si se han seleccionado permisos
                if (!empty($permisos)) {
                    // Guardar los nuevos permisos si existen
                    foreach ($permisos as $permiso) {
                        $data = [
                            'rol_id' => $idRol,
                            'permiso_id' => $permiso
                        ];
                        $this->detalleRoles->save($data);
                    }
                }
                session()->setFlashdata('success', 'permisos actualizados correctamente.');
                // Llamar al método detalles para mostrar los permisos actualizados y el mensaje de éxito
                // return redirect()->to('roles');

                return $this->detalles($idRol);
            } else {
                // Manejar el caso donde no se haya enviado el ID del rol
                return redirect()->back()->with('error', 'Error en la solicitud. Por favor, inténtelo de nuevo.');
            }
        }

        // Si la solicitud no es POST, redirigir a la página principal
        return redirect()->to('roles');
    }
}
