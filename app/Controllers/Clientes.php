<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientesModel;

class Clientes extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $clientes;
    //creamos la variable reglas para hacer validaciones
    protected $reglas;
    protected $session;
    //relaci'on controlador 
    //con este constructor importamod el modelo de clientes para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->clientes = new ClientesModel();
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
            'direccion' => [
                'rules' => 'required|min_length[1]|max_length[50]|regex_match[/^[\p{L}0-9\s#\-]+$/u]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length[1]' => 'El campo {field} debe tener minimo 1 caracteres.',
                    'max_length[50]' => 'El campo {field} supera los 50 caracteres, utilice nombres cortos y legibles.',
                    'regex_match' => 'El campo {field} solo puede contener letras, numeros y espacios.'

                ]
            ],
            'telefono' => [
                'rules' => 'required|min_length[1]|max_length[20]|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length[1]' => 'El campo {field} debe tener minimo 1 caracteres.',
                    'max_length[20]' => 'El campo {field} supera los 20 caracteres, utilice nombres cortos y legibles.',
                    'numeric' => 'El campo {field} debe contener valores numericos.'

                ]
            ],
            'correo' => [
                'rules' => 'required|min_length[1]|max_length[254]|valid_email',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length[1]' => 'El campo {field} debe tener minimo 1 caracteres.',
                    'max_length[254]' => 'El campo {field} supera los 4 caracteres, utilice nombres cortos y legibles.',
                    'valid_email' => 'El campo {field} debe ser un email valido'

                ]
            ]

        ];
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla clientes
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de clientes con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoClientesCatalogo');
        //consulta a la base de datos tabla clientes trae todos los registros cuando activo sea = a la variable
        $clientes = $this->clientes->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista

        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Clientes',
            'datos' => $clientes,
        ]);

        echo view('header', $data);
        echo view('clientes/clientes', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        $this->verificarAcceso('tienePermisoClientesAgregar');

        $data =array_merge($this->permisosMenus,['titulo' => 'Agregar cliente']);

        echo view('header',$data);
        echo view('clientes/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function insertar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {

            $this->clientes->save([
                'nombre' => $this->request->getpost('nombre'),
                'direccion' => $this->request->getpost('direccion'),
                'telefono' => $this->request->getpost('telefono'),
                'correo' => $this->request->getpost('correo'),
            ]);
            return redirect()->to(base_url() . 'clientes');
        } else {
            /**imprimimos nuevamente el formulario si no se cumple la condici'on
             * podemos usar el arreglo del formulario inicial de nuevo y le agragamos $this->validator para que nos devuelva
            en el formulario las validaciones que no se cumplieron
             */
            $data = ['titulo' => 'Agregar cliente', 'validation' => $this->validator];

            echo view('header');
            echo view('clientes/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario para esta acci'on
        $this->verificarAcceso('tienePermisoClientesEditar');
        $cliente = $this->clientes->where('id', $id)->first(); //trae el dato que vamos a editar en una consulta
        if ($valid != null) { //si le enviaron la informacion toma data y ademas el valor validation valid 
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar cliente', 'datos' => $cliente, 'validation' => $valid]); //enviamos el dato consultado a la vista para despues ser editado en la variable datos

        } else {
            //si la variable validation viene nula lo dejamos como estaba
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar cliente', 'datos' => $cliente]);
        }


        echo view('header',$data);
        echo view('clientes/editar', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function actualizar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->clientes->update($this->request->getpost('id'), [
                'nombre' => $this->request->getpost('nombre'),
                'direccion' => $this->request->getpost('direccion'),
                'telefono' => $this->request->getpost('telefono'),
                'correo' => $this->request->getpost('correo'),
            ]);
            return redirect()->to(base_url() . 'clientes');
        } else {
            //En caso de la validacion sea nula
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar clientes no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista clientes
    public function eliminar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoClientesEliminar');
        $this->clientes->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'clientes');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de clientes con estado 
    {
        $this->verificarAcceso('tienePermisoClientesEliminados');
        //consulta a la base de datos tabla clientes trae todos los registros cuando activo sea = 0 la variable
        $clientes = $this->clientes->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus,['titulo' => 'Clientes eliminados', 'datos' => $clientes]);

        echo view('header',$data);
        echo view('clientes/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoClientesReingresar');
        $this->clientes->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'clientes');
    }

    public function autocompleteData() //metodo para autocompletar en el formulario de caja y poder realizar ventas
    {
        $returnData = array();
        $valor = $this->request->getGet('term'); //obtenemos la informacion del evento y la asignamos al valor term de termino
        $clientes = $this->clientes->like('nombre', $valor)->where('activo', 1)->findAll(); //query donde filtramos por activo y ademas por nombre de cliente
        //like es para que busque en toda la palabra lo que se asemeje al termino que estamos colocando y nos traiga todos los resultados con findAll

        if (!empty($clientes)) { //validamos que si envie algo
            foreach ($clientes as $row) {
                $data['id'] = $row['id']; //Se le asigna la fila o la columna que se llama id
                $data['value'] = $row['nombre']; //Es necesatio agregar al arreglo el nobre y asignarlo a la variable value para que lo reconozca el autocomplete
                array_push($returnData, $data); //agregamos al arreglo returnData y los datos que van en ese arreglo



            }
        }
        echo json_encode($returnData); //retornamos el arreglo

    }
}
