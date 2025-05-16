<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnidadesModel;

class Unidades extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $unidades;
    //creamos la variable reglas para hacer validaciones
    protected $reglas;
    protected $session;
    //relaci'on controlador 
    //con este constructor importamod el modelo de unidades para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->unidades = new UnidadesModel();
        //incluimos helper form para que trabajemos correctamente con el formulario y aplicar validaciones en cada campo
        helper(['form']);
        $this->reglas = [
            'nombre' => [
                'rules' => 'required|min_length[3]|max_length[50]|alpha',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length[3]' => 'El campo {field} debe tener minimo 3 caracteres.',
                    'min_length[50]' => 'El campo {field} supera los 50 caracteres, utilice nombres cortos y legibles.',
                    'alpha' => 'El campo {field} solo puede contener caracteres alfabéticos.',
                ]
            ],
            'nombre_corto' => [
                'rules' => 'required|min_length[1]|max_length[4]|alpha',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length[1]' => 'El campo {field} debe tener minimo 1 caracteres.',
                    'min_length[4]' => 'El campo {field} supera los 4 caracteres, utilice nombres cortos y legibles.',
                    'alpha' => 'El campo {field} solo puede contener caracteres alfabéticos.'
                ]
            ]
        ];
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla unidades
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver este modulo
        $this->verificarAcceso('tienePermisoUnidadesCatalogo');

        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = a la variable
        $unidades = $this->unidades->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Unidades',
            'datos' => $unidades,
        ]);


        echo view('header', $data);
        echo view('unidades/unidades', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        $this->verificarAcceso('tienePermisoUnidadesAgregar');
        $data = array_merge($this->permisosMenus, ['titulo' => 'Agregar unidad']);

        echo view('header',$data);
        echo view('unidades/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function insertar()
    {
        //validamos permisos del usuario para insertar
        $this->verificarAcceso('tienePermisoUnidadesAgregar');
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {

            $this->unidades->save([
                'nombre' => $this->request->getpost('nombre'),
                'nombre_corto' => $this->request->getpost('nombre_corto')
            ]);
            return redirect()->to(base_url() . 'unidades');
        } else {
            /**imprimimos nuevamente el formulario si no se cumple la condici'on
             * podemos usar el arreglo del formulario inicial de nuevo y le agragamos $this->validator para que nos devuelva
            en el formulario las validaciones que no se cumplieron
             */
            $data = array_merge($this->permisosMenus,['titulo' => 'Agregar unidad', 'validation' => $this->validator]);

            echo view('header',$data);
            echo view('unidades/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario para este modulo
        $this->verificarAcceso('tienePermisoUnidadesEditar');
        $unidad = $this->unidades->where('id', $id)->first(); //trae el dato que vamos a editar en una consulta
        if ($valid != null) { //si le enviaron la informacion toma data y ademas el valor validation valid 
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar unidad', 'datos' => $unidad, 'validation' => $valid]); //enviamos el dato consultado a la vista para despues ser editado en la variable datos

        } else {
            //si la variable validation viene nula lo dejamos como estaba
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar unidad', 'datos' => $unidad]);
        }


        echo view('header',$data);
        echo view('unidades/editar', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function actualizar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->unidades->update($this->request->getpost('id'), ['nombre' => $this->request->getpost('nombre'), 'nombre_corto' => $this->request->getpost('nombre_corto')]);
            return redirect()->to(base_url() . 'unidades');
        } else {
            //En caso de la validacion sea nula
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar unidades no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista unidades
    public function eliminar($id)
    {
        //verifico que el usuario tenga permisos para este modulo o acci'on
        $this->verificarAcceso('tienePermisoUnidadesEliminar');
        $this->unidades->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'unidades');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 
    {
        //verifico si el usuario tiene permisos de acceso para ver este modulo
        $this->verificarAcceso('tienePermisoUnidadesEliminados');
        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = 0 la variable
        $unidades = $this->unidades->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus,['titulo' => 'Unidades eliminadas', 'datos' => $unidades]);

        echo view('header',$data);
        echo view('unidades/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        $this->verificarAcceso('tienePermisoUnidadesReingresar');
        $this->unidades->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'unidades');
    }
}
