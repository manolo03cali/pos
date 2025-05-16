<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;

class Logs extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string
    protected $logs;
    protected $session;
    //relaci'on controlador 
    //con este constructor importamod el modelo de unidades para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->logs = new LogsModel();
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla unidades
    public function index() //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de unidades con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoLogsCatalogo');
        //consulta a la base de datos tabla unidades trae todos los registros cuando activo sea = a la variable

        $datos = $this->logs->obtener();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, ['titulo' => 'Logs de acceso', 'datos' => $datos]);


        echo view('header', $data);
        echo view('logs/logs', $data);
        echo view('footer');
    }
}
