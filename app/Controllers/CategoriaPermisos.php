<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriaPermisosModel;

class CategoriaPermisos extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $categoriaPermisos;
    //creamos la variable reglas para hacer validaciones
    protected $reglas;
    protected $session;
    
    //relaci'on controlador 
    //con este constructor importamod el modelo de categoriaPermisos para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->categoriaPermisos = new CategoriaPermisosModel();
        
        //incluimos helper form para que trabajemos correctamente con el formulario y aplicar validaciones en cada campo
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
            ],
        ];
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla categoriaPermisos
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de categoriaPermisos con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoCategoriaPermisosCatalogo');
        //consulta a la base de datos tabla categoriaPermisos trae todos los registros cuando activo sea = a la variable
        $categoriaPermisos = $this->categoriaPermisos->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista ademas enviamos los premisos de menus precargados al header
        $data = array_merge($this->permisosMenus, ['titulo' => 'Categoria Permisos', 'datos' => $categoriaPermisos]);


        echo view('header', $data);
        echo view('categoriaPermisos/categoriaPermisos', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        //verifico permisos del usuario a este modulo
        $this->verificarAcceso('tienePermisoCategoriaPermisosAgregar');
 
        $data = array_merge($this->permisosMenus,['titulo' => 'Agregar Categoria Permisos']);

        echo view('header',$data);
        echo view('categoriaPermisos/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function insertar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {

            $this->categoriaPermisos->save([
                'nombre' => $this->request->getpost('nombre'),
              

            ]);
            return redirect()->to(base_url() . 'categoriaPermisos');
        } else {
            /**imprimimos nuevamente el formulario si no se cumple la condici'on
             * podemos usar el arreglo del formulario inicial de nuevo y le agragamos $this->validator para que nos devuelva
            en el formulario las validaciones que no se cumplieron
             */
            $data = ['titulo' => 'Agregar Categoria Permisos', 'validation' => $this->validator];

            echo view('header');
            echo view('categoriaPermisos/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoCategoriaPermisosEditar');
        $caja = $this->categoriaPermisos->where('id', $id)->first(); //trae el dato que vamos a editar en una consulta
        if ($valid != null) { //si le enviaron la informacion toma data y ademas el valor validation valid 
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar categoria permisos', 'datos' => $caja, 'validation' => $valid]); //enviamos el dato consultado a la vista para despues ser editado en la variable datos

        } else {
            //si la variable validation viene nula lo dejamos como estaba
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar categoria permisos', 'datos' => $caja]);
        }


        echo view('header',$data);
        echo view('categoriaPermisos/editar', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function actualizar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->categoriaPermisos->update($this->request->getpost('id'), [
                'nombre' => $this->request->getpost('nombre'),
            ]);
            return redirect()->to(base_url() . 'categoriaPermisos');
        } else {
            //En caso de la validacion sea nula
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar categoriaPermisos no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista categoriaPermisos
    public function eliminar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoCategoriaPermisosEliminar');
        $this->categoriaPermisos->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'categoriaPermisos');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de categoriaPermisos con estado 
    {
        //verifico permisos del usuario a este modulo
        $this->verificarAcceso('tienePermisoCategoriaPermisosEliminados');
        //consulta a la base de datos tabla categoriaPermisos trae todos los registros cuando activo sea = 0 la variable
        $categoriaPermisos = $this->categoriaPermisos->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus,['titulo' => 'Categoria Permisos eliminados', 'datos' => $categoriaPermisos]);

        echo view('header',$data);
        echo view('categoriaPermisos/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoCategoriaPermisosReingresar');
        $this->categoriaPermisos->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'categoriaPermisos');
    }
   
   
   
   
}
