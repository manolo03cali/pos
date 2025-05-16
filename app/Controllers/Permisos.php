<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermisosModel;
use App\Models\CategoriaPermisosModel;

class Permisos extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string
    protected $reglas; //variable para crear las reglas de validaci'on
    protected $permisos;
    protected $session;
    protected $categoriaPermisos;
    //relaci'on controlador 
    //con este constructor importamod el modelo de unidades para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->permisos = new PermisosModel();
        $this->categoriaPermisos = new CategoriaPermisosModel();
        helper(['form']);
        $this->reglas = [
            'nombre' => [
                //en esta validacion se valida requerido y adicionalmente con el caracter | que sea unico y en parentesis
                //cuadrado indicamos la tabla de la base de datos y el campo 
                'rules' => 'required|min_length[3]|max_length[50]|regex_match[/^[\p{L}0-9\s#\-áéíóúÁÉÍÓÚñÑ]+$/u]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length' => 'El campo {field} debe tener minimo 3 caracteres.',
                    'max_length' => 'El campo {field} supera los 50 caracteres, utilice nombres cortos y legibles.',
                    'regex_match' => 'El campo {field} solo puede contener caracteres alfabéticos y numericos.',
                ]
            ],
            'tipo' => [
                //en esta validacion se valida requerido y adicionalmente con el caracter | que sea unico y en parentesis
                //cuadrado indicamos la tabla de la base de datos y el campo 
                'rules' => 'required|min_length[1]|max_length[50]|regex_match[/^[\p{L}0-9\s#\-áéíóúÁÉÍÓÚñÑ]+$/u]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length' => 'El campo {field} debe tener minimo 1 caracteres.',
                    'max_length' => 'El campo {field} supera los 50 caracteres, utilice nombres cortos y legibles.',
                    'regex_match' => 'El campo {field} solo puede contener caracteres alfabéticos y numericos.',
                ]
            ]

        ];
    }

    public function index($activo = 1)
    {
        // Validamos si no existe el usuario logueado
        if (!isset($this->session->id_usuario)) {
            return redirect()->to(base_url());
        }

        // Verifico si el usuario tiene permisos de acceso
        $this->verificarAcceso('tienePermisoPermisosCatalogo');
        $query = $this->permisos
            ->select('permisos.*, categoria_permisos.nombre as categoria_nombre')
            ->join('categoria_permisos', 'permisos.categoria_permisos_id = categoria_permisos.id', 'left') // Cambiar a LEFT JOIN
            ->where('permisos.activo', $activo)
            ->orderBy('permisos.fecha_alta', 'ASC')
            ->get();

        $permisos = $query->getResultArray();

        // La información que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Permisos',
            'datos' => $permisos,
        ]);

        echo view('header', $data);
        echo view('permisos/permisos', $data);
        echo view('footer');
    }

    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        //Verifico si el usuario tiene permisos a este modulo
        $this->verificarAcceso('tienePermisoPermisosAgregar');
        $categoriaspermisos = $this->categoriaPermisos->where('activo', 1)->findAll();
        $data = array_merge($this->permisosMenus, ['titulo' => 'Agregar Permiso', 'categoriasPermisos' => $categoriaspermisos]);

        echo view('header', $data);
        echo view('permisos/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function insertar()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->permisos->save([
                'nombre' => $this->request->getpost('nombre'),
                'tipo' => $this->request->getpost('tipo'),
                'categoria_permisos_id' => $this->request->getpost('categoria_permisos_id'),
            ]);
            return redirect()->to(base_url() . 'permisos');
        } else {
            //return $this->nuevo($this->request->getpost('id'),$this->validator);
            $data = ['titulo' => 'Agregar Permiso', 'validation' => $this->validator];

            echo view('header');
            echo view('permisos/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoPermisosEditar');
        $categoriaspermisos = $this->categoriaPermisos->where('activo', 1)->findAll();
        $Permiso = $this->permisos->where('id', $id)->first(); //trae el dato que vamos a editar en una consulta
        $data = array_merge($this->permisosMenus, ['titulo' => 'Editar Permiso', 'datos' => $Permiso, 'validation' => $valid, 'categoriasPermisos' => $categoriaspermisos]); //enviamos el dato consultado a la vista para despues ser editado en la variable datos

        echo view('header', $data);
        echo view('permisos/editar', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function actualizar()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->permisos->update($this->request->getpost('id'), [
                'nombre' => $this->request->getpost('nombre'),
                'tipo' => $this->request->getpost('tipo'),
                'categoria_permisos_id' => $this->request->getpost('categoria_permisos_id')
            ]);
            return redirect()->to(base_url() . 'permisos');
        } else {
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar unidades no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista unidades
    public function eliminar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoPermisosEliminar');
        $this->permisos->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'permisos');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 
    {
        //verifico si el usuario tiene permisos a este modulo
        $this->verificarAcceso('tienePermisoPermisosEliminados');
        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = 0 la variable
        $permisos = $this->permisos->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, ['titulo' => 'Permisos eliminados', 'datos' => $permisos]);

        echo view('header');
        echo view('permisos/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoPermisosReingresar');
        $this->permisos->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'permisos');
    }
}
