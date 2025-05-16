<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ConfiguracionModel;

class Configuracion extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $configuracion;
    //creamos la variable reglas para hacer validaciones
    protected $reglas;
    protected $session;
    //relaci'on controlador 
    //con este constructor importamod el modelo de configuracion para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->configuracion = new ConfiguracionModel();
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
            'tienda_rfc' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',


                ]
            ],
            'tienda_telefono' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'numeric' => 'El campo {field} solo puede contener caracteres numericos.',


                ]
            ],
            'tienda_email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'valid_email' => 'El campo {field} debe ser un email valido',


                ]
            ],
            'tienda_direccion' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',


                ]
            ],
            'ticket_leyenda' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',


                ]
            ]
        ];
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla configuracion
    public function index($errors = null) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de configuracion con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoConfiguracion');

        //consultamos todos los datos de la tabla configuracion y lo asignamos a un array asociativo       
        $configuracion = $this->configuracion->findAll();
        $config = [];
        //recorremos la tabla configuracion       
        foreach ($configuracion as $conf) {

            //Genera array asociativo dinámico nombre=>valor
            $config[$conf['nombre']] = $conf['valor'];
        }

        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Configuración Tienda',
            'datos' => $config,
            'errors' => $errors
        ]);
        echo view('header', $data);
        echo view('configuracion/configuracion', $data);
        echo view('footer');
    }




    public function actualizar()
    {
        
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            // Se utiliza una consulta diferente teniendo en cuenta que no estamos utilizando el id para actualizar sino el campo nombre cuando nombre sea igual a tienda_nombre
            $nuevonombre = $this->request->getPost('tienda_nombre');
            $nuevorfc = $this->request->getPost('tienda_rfc');
            $nuevotelefono = $this->request->getPost('tienda_telefono');
            $nuevoemail = $this->request->getPost('tienda_email');
            $nuevodireccion = $this->request->getPost('tienda_direccion');
            $nuevoleyenda = $this->request->getPost('ticket_leyenda');

            $this->configuracion->whereIn('nombre', ['tienda_nombre'])->set(['valor' => $nuevonombre])->update();
            $this->configuracion->whereIn('nombre', ['tienda_rfc'])->set(['valor' => $nuevorfc])->update();
            $this->configuracion->whereIn('nombre', ['tienda_telefono'])->set(['valor' => $nuevotelefono])->update();
            $this->configuracion->whereIn('nombre', ['tienda_email'])->set(['valor' => $nuevoemail])->update();
            $this->configuracion->whereIn('nombre', ['tienda_direccion'])->set(['valor' => $nuevodireccion])->update();
            $this->configuracion->whereIn('nombre', ['ticket_leyenda'])->set(['valor' => $nuevoleyenda])->update();


            // Verifica que se haya cargado el archivo correctamente
            $validacion = $this->validate([
                'tienda_logo' => [
                    'uploaded[tienda_logo]', // Verifica que se cargue con el campo correctamente
                    'mime_in[tienda_logo,image/png]', // Restricción de tipo de archivo ue se recibe en el campo tienda_logo en la vista valida el tipo de archivo
                    'max_size[tienda_logo,4096]', // Restricción de tamaño
                ]
            ]);

            if ($validacion) {
                $ruta_logo = "images/logotipo.png"; //ruta predefinida del logo
                if (file_exists($ruta_logo)) {
                    unlink($ruta_logo);
                }
                $img = $this->request->getFile('tienda_logo');
                $img->move('./images', 'logotipo.png'); // asignamos la ruta donde se va a guardar el archiv y cambiamos el nombre de la imagen a logotipo automaticamente
            } else {
                echo 'Error en la validacion';
                exit;
            }

            return redirect()->to(base_url() . 'configuracion');
        } else {
            //En caso de la validacion sea nula
            //return $this->index($this->request->getpost('nombre'),$this->validator);
            //consultamos todos los datos de la tabla configuracion y lo asignamos a un array asociativo       
            $configuracion = $this->configuracion->findAll();
            $config = [];
            //recorremos la tabla configuracion       
            foreach ($configuracion as $conf) {

                //Genera array asociativo dinámico nombre=>valor
                $config[$conf['nombre']] = $conf['valor'];
            }

            $data = ['titulo' => 'Configuracion Tienda', 'datos' => $config, 'validation' => $this->validator];

            echo view('header');
            echo view('configuracion/configuracion', $data);
            echo view('footer');
        }
    }
}
