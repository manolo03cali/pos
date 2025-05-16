<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriasModel;

class Categorias extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string
    protected $reglas; //variable para crear las reglas de validaci'on
    protected $categorias;
    protected $session;
    //relaci'on controlador 
    //con este constructor importamod el modelo de unidades para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->categorias = new CategoriasModel();
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
    //funci'on para consultar todos los registros de la tabla unidades
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoCategoriasCatalogo');
        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = a la variable
        $categorias = $this->categorias->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista

        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Categorias',
            'datos' => $categorias,
        ]);

        echo view('header', $data);
        echo view('categorias/categorias', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        $this->verificarAcceso('tienePermisoCategoriasAgregar');
        $data = array_merge($this->permisosMenus,['titulo' => 'Agregar categoria']);

        echo view('header',$data);
        echo view('categorias/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function insertar()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->categorias->save([
                'nombre' => $this->request->getpost('nombre')
            ]);
            return redirect()->to(base_url() . 'categorias');
        } else {
            //return $this->nuevo($this->request->getpost('id'),$this->validator);
            $data = ['titulo' => 'Agregar categoria', 'validation' => $this->validator];

            echo view('header');
            echo view('categorias/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario a este modulo
        $this->verificarAcceso('tienePermisoCategoriasEditar');
        $categoria = $this->categorias->where('id', $id)->first(); //trae el dato que vamos a editar en una consulta
        $data = array_merge($this->permisosMenus,['titulo' => 'Editar categoria', 'datos' => $categoria, 'validation' => $valid]); //enviamos el dato consultado a la vista para despues ser editado en la variable datos

        echo view('header',$data);
        echo view('categorias/editar', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function actualizar()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->categorias->update($this->request->getpost('id'), [
                'nombre' => $this->request->getpost('nombre')
            ]);
            return redirect()->to(base_url() . 'categorias');
        } else {
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar unidades no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista unidades
    public function eliminar($id)
    {
        //verifico permisos del usuario a este modulo
        $this->verificarAcceso('tienePermisoCategoriasEliminar');
        $this->categorias->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'categorias');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 
    {
        //verifico permisos de modulo
        $this->verificarAcceso('tienePermisoCategoriasEliminados');
        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = 0 la variable
        $categorias = $this->categorias->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus,['titulo' => 'Categorias eliminadas', 'datos' => $categorias]);

        echo view('header',$data);
        echo view('categorias/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico que el usuario tiene permitos para este modulo
        $this->verificarAcceso('tienePermisoCategoriasReingresar');
        $this->categorias->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'categorias');
    }
}
