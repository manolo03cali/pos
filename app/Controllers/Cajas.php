<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CajasModel;
use App\Models\ArqueoCajaModel;
use App\Models\VentasModel;

class Cajas extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string

    protected $cajas;
    //creamos la variable reglas para hacer validaciones
    protected $reglas;
    protected $session;
    protected $ventasModel;
    protected $arqueoModel;
    //relaci'on controlador 
    //con este constructor importamod el modelo de cajas para interactuar con el
    public function __construct()
    {
        $this->session = session();
        $this->cajas = new CajasModel();
        $this->arqueoModel = new ArqueoCajaModel();
        $this->ventasModel = new VentasModel();
        //incluimos helper form para que trabajemos correctamente con el formulario y aplicar validaciones en cada campo
        helper(['form']);
        $this->reglas = [
            'numero_caja' => [
                'rules' => 'required|min_length[3]|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length[1]' => 'El campo {field} debe tener minimo 1 caracteres.',
                    'numeric' => 'El campo {field} debe contener valores numericos.'

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
            'folio' => [
                'rules' => 'required|min_length[1]|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'min_length[1]' => 'El campo {field} debe tener minimo 1 caracteres.',
                    'numeric' => 'El campo remisión debe contener valores numericos.'

                ]
            ]

        ];
    }
    // relaci'on controlador vista
    //funci'on para consultar todos los registros de la tabla cajas
    public function index($activo = 1) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de cajas con estado 1
    {
        //validamos si no existe el usuario esta logueado y hacemos una negaci'on 
        if (!isset($this->session->id_usuario)) {
            //si el usuario no esta logueado retorne a la url de login
            return redirect()->to(base_url());
        }
        //verifico si el usuario tiene permisos de acceso para ver el catalogo por url
        $this->verificarAcceso('tienePermisoCajasCatalogo');
        //consulta a la base de datos tabla cajas trae todos los registros cuando activo sea = a la variable
        $cajas = $this->cajas->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista ademas enviamos los premisos de menus precargados al header
        $data = array_merge($this->permisosMenus, ['titulo' => 'Cajas', 'datos' => $cajas]);


        echo view('header', $data);
        echo view('cajas/cajas', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        //verifico permisos del usuario a este modulo
        $this->verificarAcceso('tienePermisoCajasAgregar');
        $data = array_merge($this->permisosMenus,['titulo' => 'Agregar caja']);

        echo view('header',$data);
        echo view('cajas/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function insertar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {

            $this->cajas->save([
                'numero_caja' => $this->request->getpost('numero_caja'),
                'nombre' => $this->request->getpost('nombre'),
                'folio' => $this->request->getpost('folio'),

            ]);
            return redirect()->to(base_url() . 'cajas');
        } else {
            /**imprimimos nuevamente el formulario si no se cumple la condici'on
             * podemos usar el arreglo del formulario inicial de nuevo y le agragamos $this->validator para que nos devuelva
            en el formulario las validaciones que no se cumplieron
             */
            $data = ['titulo' => 'Agregar caja', 'validation' => $this->validator];

            echo view('header');
            echo view('cajas/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoCajasEditar');
        $caja = $this->cajas->where('id', $id)->first(); //trae el dato que vamos a editar en una consulta
        if ($valid != null) { //si le enviaron la informacion toma data y ademas el valor validation valid 
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar caja', 'datos' => $caja, 'validation' => $valid]); //enviamos el dato consultado a la vista para despues ser editado en la variable datos

        } else {
            //si la variable validation viene nula lo dejamos como estaba
            $data = array_merge($this->permisosMenus,['titulo' => 'Editar caja', 'datos' => $caja]);
        }


        echo view('header',$data);
        echo view('cajas/editar', $data);
        echo view('footer');
    }
    //funcion para agregar registros
    public function actualizar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST, en el parentesis de validate traemos la variable reglas */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {
            $this->cajas->update($this->request->getpost('id'), [
                'numero_caja' => $this->request->getpost('numero_caja'),
                'nombre' => $this->request->getpost('nombre'),
                'folio' => $this->request->getpost('folio'),

            ]);
            return redirect()->to(base_url() . 'cajas');
        } else {
            //En caso de la validacion sea nula
            return $this->editar($this->request->getpost('id'), $this->validator);
        }
    }
    //funcion para eliminar cajas no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista cajas
    public function eliminar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoCajasEliminar');
        $this->cajas->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'cajas');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de cajas con estado 
    {
        //verifico permisos del usuario a este modulo
        $this->verificarAcceso('tienePermisoCajasEliminados');
        //consulta a la base de datos tabla cajas trae todos los registros cuando activo sea = 0 la variable
        $cajas = $this->cajas->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus,['titulo' => 'Cajas eliminadas', 'datos' => $cajas]);

        echo view('header',$data);
        echo view('cajas/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoCajasReingresar');
        $this->cajas->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'cajas');
    }
    //funci'on para consultar el catalogo de arqueos
    public function arqueo($idCaja)
    {
        //verifico permisos del usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoCajasArqueo');
        
        $cajaUsuarioId = $this->session->cajas_id;
        $rolUsuario = $this->session->roles_id; // Campo que almacena el rol del usuario

        // Si el rol del usuario es "superadmin", no necesitamos verificar la caja
        if ($rolUsuario !== '1' && $idCaja != $cajaUsuarioId) {
            // Mostrar un mensaje de error o redirigir a una página de acceso denegado
            $data = array_merge($this->permisosMenus,['titulo' => 'Error de permisos', 'mensaje' => 'No tienes permiso para abrir o cerrar esta caja.']);
            echo view('header',$data);
            echo view('errors/html/error_401', $data);
            echo view('footer');
        }
        //en el modelo creamos un metodo llamado getDatos en el cual aplicamos los joins necesarios luego lo llamamos aqui
        $arqueos = $this->arqueoModel->getDatos($idCaja);
        $data = array_merge($this->permisosMenus,['titulo' => 'Cierres de caja', 'datos' => $arqueos]);

        echo view('header',$data);
        echo view('cajas/arqueos', $data);
        echo view('footer');
    }
    public function nuevo_arqueo()
    {
        $existe = $this->arqueoModel->where(['cajas_id' => $this->session->cajas_id, 'estatus' => 1])->countAllResults(); //validacion para no crear nuevas cajas abiertas
        //si existe una caja que no ha cerrado validamos si existe un registro con caja abierta  solo quiero saber cuantos registros hay en estas condiciones para eso 
        //usamos el countAllResult
        if ($existe > 0) {
            $data = ['titulo' => 'Error apertura de caja', 'mensaje' => '"Ya existe una caja abierta para este usuario, verifique nuevamente."'];
            echo view('header');
            echo view('errors/html/error_401', $data);
            echo view('footer');
        } else {
            if ($this->request->getMethod() == "POST") { // Verificación de si es un método POST
                $fecha = date('Y-m-d h:i:s'); // Por si el usuario cambia la fecha, el método la toma directamente del servidor
                $this->arqueoModel->save([
                    'fecha_inicio' => $fecha,
                    'monto_inicial' => $this->request->getPost('monto_inicial'),
                    'estatus' => 1,
                    'cajas_id' => $this->session->cajas_id,
                    'usuarios_id' => $this->session->id_usuario
                ]);
                return redirect()->to(base_url() . 'cajas');
            } else { // si no existe post, el usuario está solicitando que le muestre el formulario
                $caja = $this->cajas->where('id', $this->session->cajas_id)->first();

                $data = ['titulo' => 'Apertura de caja', 'caja' => $caja, 'session' => $this->session];
                echo view('header');
                echo view('cajas/nuevo_arqueo', $data);
                echo view('footer');
            }
        }
    }
    public function cerrarCaja()
    {
        if ($this->request->getMethod() == "POST") { // Verificación de si es un método POST
            $fecha = date('Y-m-d h:i:s'); // Por si el usuario cambia la fecha, el método la toma directamente del servidor
            $this->arqueoModel->update(
                $this->request->getPost('id_arqueo'),
                [
                    'fecha_fin' => $fecha,
                    'monto_final' => $this->request->getPost('monto_final'),
                    'total_ventas' => $this->request->getPost('total_ventas'),
                    'estatus' => 0,
                ]
            );
            return redirect()->to(base_url() . 'cajas');
        } else { // si no existe post, el usuario está solicitando que le muestre el formulario
            $montoTotal = $this->ventasModel->totalDia(Date('Y-m-d'));
            $numVentasDia = $this->ventasModel->numVentasDia(Date('Y-m-d'));
            $arqueo = $this->arqueoModel->where(['cajas_id' => $this->session->cajas_id, 'estatus' => 1])->first();
            $caja = $this->cajas->where('id', $this->session->cajas_id)->first();
            $data = [
                'titulo' => 'Cerrar  caja',
                'caja' => $caja,
                'session' => $this->session,
                'arqueo' => $arqueo,
                'montototal' => $montoTotal,
                'numVentasDia' => $numVentasDia
            ];
            echo view('header');
            echo view('cajas/cerrar_caja', $data);
            echo view('footer');
        }
    }
}
