<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\RolesModel;
use App\Models\CajasModel;
use App\Models\LogsModel;


class Usuarios extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string
    //variables para almacenar los modelos que vamos a usar 
    protected $usuarios;
    protected $roles;
    protected $cajas;
    protected $logs;
    //Variables para crear reglas de validaci'on
    protected $reglasCrearUsuarios;
    protected $reglasLogin;
    protected $reglasActualizaUsuario;
    protected $reglasCambiarPassword;
    protected $session;


    //relaci'on controlador 
    //con este constructor importamod el modelo de usuarios para interactuar con el
    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->cajas = new CajasModel();
        $this->roles = new RolesModel();
        $this->logs = new LogsModel();
        $this->session = session();

        //incluimos helper form para que trabajemos correctamente con el formulario y aplicar validaciones en cada campo
        helper(['form']);
        $this->reglasCrearUsuarios = [
            'usuario' => [
                //en esta validacion se valida requerido y adicionalmente con el caracter | que sea unico y en parentesis
                //cuadrado indicamos la tabla de la base de datos y el campo 
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',


                ]
            ],
            'repassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'El campo repita password es obligatorio.',
                    'matches' => 'Las contrasenas no coinciden.',


                ]
            ],
            'nombre' => [
                'rules' => 'required|min_length[1]|max_length[50]|regex_match[/^[\p{L}0-9\s#\-]+$/u]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length' => 'El campo {field} debe tener al menos 1 carácter.',
                    'max_length' => 'El campo {field} no puede superar los 50 caracteres. Utilice nombres cortos y legibles.',
                    'regex_match' => 'El campo {field} solo puede contener letras, números, espacios, el símbolo # y guiones (-).',

                ]
            ],
            'cajas_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',

                ]
            ],
            'roles_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',

                ]
            ]


        ];
        $this->reglasActualizaUsuario = [
            'nombre' => [
                'rules' => 'required|min_length[1]|max_length[50]|regex_match[/^[\p{L}0-9\s#\-]+$/u]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length' => 'El campo {field} debe tener al menos 1 carácter.',
                    'max_length' => 'El campo {field} no puede superar los 50 caracteres. Utilice nombres cortos y legibles.',
                    'regex_match' => 'El campo {field} solo puede contener letras, números, espacios, el símbolo # y guiones (-).',

                ]
            ],
            'cajas_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',

                ]
            ],
            'roles_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',

                ]
            ]


        ];
        $this->reglasLogin = [
            'usuario' => [
                //en esta validacion se valida requerido y adicionalmente con el caracter | que sea unico y en parentesis
                //cuadrado indicamos la tabla de la base de datos y el campo 
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',


                ]
            ],
        ];
        $this->reglasCambiarPassword = [
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',


                ]
            ],
            'repassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'El campo repita password es obligatorio.',
                    'matches' => 'Las contrasenas no coinciden.',


                ]
            ],
        ];
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla usuarios
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de usuarios con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoUsuariosCatalogo');
        //consulta a la base de datos tabla usuarios trae todos los registros cuando activo sea = a la variable
        $usuarios = $this->usuarios->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, ['titulo' => 'Usuarios', 'datos' => $usuarios]);

        echo view('header', $data);
        echo view('usuarios/usuarios', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        //Verificamos permisos de acceso para pasar al formulario de ingreso de registros nuevos
        $this->verificarAcceso('tienePermisoUsuariosAgregar');
        //traemos las dos consultas de usuarios y categorias para relacionarlas al crear el nuevo usuario solo cambio la variable $activo a 1
        //Una vez obtenemos los datos los podemos enviar a la vista para poder usarlos en los select y option
        //para poder usar esos modelos debo declararlos en el constructor
        $roles = $this->roles->where('activo', 1)->findAll();
        $cajas = $this->cajas->where('activo', 1)->findAll();
         $data = array_merge($this->permisosMenus,['titulo' => 'Agregar usuario', 'roles' => $roles, 'cajas' => $cajas,]);
       

        echo view('header',$data);
        echo view('usuarios/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros

    public function insertar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglasCrearUsuarios)) {
            //Es necesario codificar la contrasena o cifrarla  el error que muestra es porque espera un string a pesar de que s ele envia el campo que nos envia el usuario en string
            //igual no genera problema en el sistema 
            $hash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

            $this->usuarios->save([
                //los nombre de la izquierda son los nombres de las columnas de la dba y los nombres de la derecha son de los campos recibidos mediante pos
                'usuario' => $this->request->getPost('usuario'),
                'password' => $hash,
                'nombre' => $this->request->getPost('nombre'),
                'roles_id' => $this->request->getPost('roles_id'),
                'cajas_id' => $this->request->getPost('cajas_id')
            ]);
            //nos retorna nuevamente alformulario de usuarios para ingresar un nuevo usuario
            return redirect()->to(base_url() . 'usuarios');
        } else {
            /**imprimimos nuevamente el formulario si no se cumple la condici'on
             * podemos usar el arreglo del formulario inicial de nuevo y le agragamos $this->validator para que nos devuelva
            en el formulario las validaciones que no se cumplieron
             */
            //$usuario = $this->usuarios->where('id', 1)->first();
            $usuarios = $this->usuarios->where('activo', 1)->findAll();
            $roles = $this->roles->where('activo', 1)->findAll();
            $cajas = $this->cajas->where('activo', 1)->findAll();
            $data = [
                'titulo' => 'Agregar usuario',
                'usuarios' => $usuarios,
                'roles' => $roles,
                'cajas' => $cajas,
                'validation' => $this->validator
            ];

            echo view('header');
            echo view('usuarios/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //validamos si el usuario tiene permisos a este modulo o acci'on
        $this->verificarAcceso('tienePermisoUsuariosEditar');
        $usuario = $this->usuarios->where('id', $id)->first();
        $usuarios = $this->usuarios->where('activo', 1)->findAll();
        $roles = $this->roles->where('activo', 1)->findAll();
        $cajas = $this->cajas->where('activo', 1)->findAll();
        if ($valid != null) { //si le enviaron la informacion toma data y ademas el valor validation valid         
            //Tambien es necesario enviar el catalogo de usuarios y categorias y el dato adicional el usuario que estoy consultando
            $data = array_merge($this->permisosMenus,[
                'titulo' => 'Editar usuario',
                'usuario' => $usuario,
                'usuarios' => $usuarios,
                'roles' => $roles,
                'cajas' => $cajas,
                'validation' => $valid
            ]);
        } else {
            $data = array_merge($this->permisosMenus,[
                'titulo' => 'Editar usuario',
                'usuarios' => $usuarios,
                'cajas' => $cajas,
                'usuario' => $usuario,
                'roles' => $roles
            ]);
        }


        echo view('header',$data);
        echo view('usuarios/editar', $data);
        echo view('footer');
    }
    //funcion para editar registros
    public function actualizar()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglasActualizaUsuario)) {
            $this->usuarios->update($this->request->getpost('id'), [
                'nombre' => $this->request->getpost('nombre'),
                'roles_id' => $this->request->getpost('roles_id'),
                'cajas_id' => $this->request->getpost('cajas_id')
            ]);
            return redirect()->to(base_url() . 'usuarios');
        } else {
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar usuarios no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista usuarios
    public function eliminar($id)
    {
         //verifico permisos del usuario para este modulo o acci'on
         $this->verificarAcceso('tienePermisoUsuariosEliminar');
        $this->usuarios->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'usuarios');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de usuarios con estado 
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoUsuariosEliminados');
        //consulta a la base de datos tabla usuarios trae todos los registros cuando activo sea = 0 la variable
        $usuarios = $this->usuarios->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus,['titulo' => 'Usuarios eliminados', 'datos' => $usuarios]);

        echo view('header',$data);
        echo view('usuarios/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoUsuariosReingresar');
        $this->usuarios->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'usuarios');
    }
    //Metodo para que al iniciar el sistema nos muestre el login que traemos de boostrap y lo agregamos al proyecto
    //Ojo para que los metodos login y valida funcionen verifica la ruta que debe ser post
    public function login()
    {
        //no utilizamos la base url porque esta en la raiz de mis vistas
        echo view('login');
    }
    //metodo que permite validar los campos del formulario login y compararlos con los de la base de datos para permitir el inicio de sesion
    //ojo solo traemos los datos del usuario y en el miemo petodo hacemos la comparacion de la contrase;a que traemos en los datos del usuario con la ingresada por el usuario en el formulario
    public function valida()
    {
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglasLogin)) {
            $usuario = $this->request->getPost('usuario');
            $password = $this->request->getPost('password');
            //Traemos los datos del usuario luego consultamos unicamente con el nombre o campo usuario y traemos el primer registro
            $datosUsuario = $this->usuarios->where('usuario', $usuario)->first();
            if ($datosUsuario != null) {
                //comparamos el password recibido del formulario con el password que viene incluido en los datos del usuario
                //la funcion password_verify ayuda a cifrar el password que nos envia el usuario y compararlo con el que viene de la base de datos
                //ojo las cadenas no son las mismas pero la funcion mencionada ayuda a validar la combinaci'on
                if (password_verify($password, $datosUsuario['password'])) {
                    //declaro los datos de la sesion
                    $datosSesion = [
                        'id_usuario' => $datosUsuario['id'],
                        'nombre' => $datosUsuario['nombre'],
                        'cajas_id' => $datosUsuario['cajas_id'],
                        'roles_id' => $datosUsuario['roles_id'],

                    ];
                    //obtenemos la ip de donde consultan
                    $ip = $_SERVER['REMOTE_ADDR'];
                    //ME PERMITE SABER QUE NAVEGADOR ESTA USANDO EL USUARIO
                    $detalles = $_SERVER['HTTP_USER_AGENT'];
                    //codigo para guardar los logs previo hay que crear la tabla en db el modelo, el controlador
                    $this->logs->save([
                        'id_usuario' => $datosUsuario['id'],
                        'evento' => 'Inicio de sesion',


                        'ip' => $ip,
                        'detalles' => $detalles,

                    ]);


                    $session = session();
                    $session->set($datosSesion);
                    //el return no redirige a la pagina configuracion en caso de que la validacion de los datos sea exitosa
                    return redirect()->to(base_url() . 'inicio');
                } else {
                    $data['error'] = "Datos incorrectos";
                    echo view('login', $data);
                }
            } else {
                $data['error'] = "Datos incorrectos";
                echo view('login', $data);
            }
        } else {
            $data = ['validation' => $this->validator];
            echo view('login', $data);
        }
    }
    public function logout()
    {

        $session = session();
        //obtenemos la ip de donde consultan
        $ip = $_SERVER['REMOTE_ADDR'];
        //ME PERMITE SABER QUE NAVEGADOR ESTA USANDO EL USUARIO
        $detalles = $_SERVER['HTTP_USER_AGENT'];
        //codigo para guardar los logs previo hay que crear la tabla en db el modelo, el controlador
        $this->logs->save([
            'id_usuario' => $session->id_usuario,
            'evento' => 'Cierre de sesion',


            'ip' => $ip,
            'detalles' => $detalles,

        ]);
        $session->destroy(); //session_destroy es lo mismo 
        return redirect()->to(base_url());
    }
    public function cambia_password()
    {
        $session = session();
        $usuario = $this->usuarios->where('id', $session->id_usuario)->first();
        //la informaci'on que le vamos a enviar a la vista
        $data = ['titulo' => 'Cambiar contraseña', 'usuario' => $usuario];

        echo view('header');
        echo view('usuarios/cambia_password', $data);
        echo view('footer');
    }
    public function actualizar_password()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglasCambiarPassword)) {
            //para poder hacer la actualizacion en la base de datos es necesario traer el id del usuario entonces esoe datos los cargamos de la session activa
            $session = session();
            //de la session activa solo traemos el id del usuario
            $idUsuario = $session->id_usuario;
            //Es necesario codificar o cifrar la contrasena ingresada por el usuario 
            $hash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            //actualizamos el id del usuario en el campo password por el hash que es el nuevo password codificado
            $this->usuarios->update($idUsuario, ['password' => $hash]);
            $usuario = $this->usuarios->where('id', $session->id_usuario)->first();
            //la informaci'on que le vamos a enviar a la vista
            $data = ['titulo' => 'Cambiar contraseña', 'usuario' => $usuario, 'mensaje' => 'contraseña actualizada'];

            echo view('header');
            echo view('usuarios/cambia_password', $data);
            echo view('footer');
        } else {
            //mostramos nuevamente la vista con los datos de la session para que el usuario intente nuevamente
            $session = session();
            $usuario = $this->usuarios->where('id', $session->id_usuario)->first();
            //la informaci'on que le vamos a enviar  el titulo, los datos del usuario y la validacion
            $data = ['titulo' => 'Cambiar contraseña', 'usuario' => $usuario, 'validation' => $this->validator];

            echo view('header');
            echo view('usuarios/cambia_password', $data);
            echo view('footer');
        }
    }
}
