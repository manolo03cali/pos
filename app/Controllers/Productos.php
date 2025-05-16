<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;
//es necesatio usar los modelos de CategoriasModel y UunidadesModel con el fin de consultar sus datos en la funci'on nuevo y enviarlos a la vista correspondiente para 
//mostrarlos en la lista de selecci'on select y option en la vista nuevo
use App\Models\CategoriasModel;
use App\Models\UnidadesModel;
use App\Models\DetalleRolesPermisosModel; //verificar si se puede hacer en el base controller una sola vez para que se aplique a todas las vistas por ahora lohacemos asi
use App\Models\PermisosModel;
use App\Models\RolesModel;
use App\Models\ConfiguracionModel;

class Productos extends BaseController
{
    //para poder mostrar las nuevas vistas creadas usando echo se debe eliminar el parametro :string
    protected $reglas; //variable para crear las reglas de validaci'on
    protected $reglasActualizar; //variable para crear las reglas de validaci'on
    protected $productos;
    protected $categorias;
    protected $unidades;
    protected $session;
    protected $detalleRoles;
    protected $permisos;
    protected $roles;
    protected $configuracion;


    //relaci'on controlador 
    //con este constructor importamod el modelo de productos para interactuar con el
    public function __construct()
    {

        $this->productos = new ProductosModel();
        //declaramos en el constructor categorias y unidades para poder usar sus consultas de la function nuevo y en su vista correspondiente
        $this->unidades = new UnidadesModel();
        $this->categorias = new CategoriasModel();
        $this->roles = new RolesModel();
        $this->permisos = new PermisosModel();
        $this->detalleRoles = new DetalleRolesPermisosModel();
        $this->configuracion = new ConfiguracionModel();
        $this->session = session();
        //$this->session = session();
        //incluimos helper form para que trabajemos correctamente con el formulario y aplicar validaciones en cada campo
        helper(['form']);
        $this->reglas = [
            'codigo' => [
                //en esta validacion se valida requerido y adicionalmente con el caracter | que sea unico y en parentesis
                //cuadrado indicamos la tabla de la base de datos y el campo 
                'rules' => 'required|is_unique[productos.codigo]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'is_unique' => 'El campo {field} debe ser unico.'
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
            'precio_venta' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'numeric' => 'El campo {field} solo puede contener caracteres numericos.'
                ]
            ],
            'precio_compra' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'numeric' => 'El campo {field} solo puede contener caracteres numericos.'
                ]
            ],
            'stock_minimo' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'numeric' => 'El campo {field} solo puede contener caracteres numericos.'
                ]
            ],
            'categorias_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Categoria es obligatorio.'
                ]
            ],
            'unidades_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Unidades es obligatorio.'
                ]
            ]

        ];
        $this->reglasActualizar = [
            'codigo' => [
                //en esta validacion se valida requerido y adicionalmente con el caracter | que sea unico y en parentesis
                //cuadrado indicamos la tabla de la base de datos y el campo 
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',

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
            'precio_venta' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'numeric' => 'El campo {field} solo puede contener caracteres numericos.'
                ]
            ],
            'precio_compra' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'numeric' => 'El campo {field} solo puede contener caracteres numericos.'
                ]
            ],
            'stock_minimo' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'numeric' => 'El campo {field} solo puede contener caracteres numericos.'
                ]
            ],
            'categorias_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Categoria es obligatorio.'
                ]
            ],
            'unidades_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Unidades es obligatorio.'
                ]
            ]

        ];
    }

    public function index()
    {


        //  Verifica si el usuario está logueado
        if (!isset($this->session->id_usuario)) {
            // Redirige al usuario a la página de login si no está logueado
            return redirect()->to(base_url());
        }

        // Verifica si el usuario tiene permiso para acceder al catálogo de productos en caso de que el usuario acceda por url
        //metodo creado en el BaseController para poder aplicarlo en todas las vistas en caso de que el usuario no tenga permisos el metodo verificaAcceso 
        //lo envia a una vista de error de acceso en caso de que si tenga acceso se ejecuta la logica siguiente
        $this->verificarAcceso('tienePermisoProductosCatalogo');

        // Si tiene permiso, obtén los productos activos
        $productos = $this->productos->where('activo', 1)->findAll();

        // Prepara los datos para pasar a la vista ademas pasamos los permisos Menu a la vista para
        // permitir o no permitir que el usuario vea el menu de acuerdo a sus permisos se cargan una sola vez desde el BaseController
        //y en los demas controladores se llama los permisos ya cargados y se envian al header
        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Productos',
            'datos' => $productos,
        ]);

        // Muestra las vistas
        echo view('header', $data);
        echo view('productos/productos', $data);
        echo view('footer');
    }



    public function productosMinimo() //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de productos con estado 1
    {
        $this->verificarAcceso('tienePermisoMenuReporteMinimos');
        //consulta a la base de datos tabla productos trae todos los registros cuando activo sea = a la variable
        $productos = $this->productos->where("stock_minimo >= existencias AND inventariable =1 AND activo = 1")->findAll();
        //la informaci'on que le vamos a enviar a la vista
        $data = array_merge($this->permisosMenus, [
            'titulo' => 'Productos con stock mimnimo',
            'datos' => $productos,
        ]);
       

        echo view('header', $data);
        echo view('productos/productos_minimo', $data);
        echo view('footer');
    }
    //funcion para pasar al formulario de agregar registros
    public function nuevo()
    {
        //verifico si el usuario tiene permisos para agregar productos
        $this->verificarAcceso('tienePermisoProductosAgregar');
        //traemos las dos consultas de unidades y categorias para relacionarlas al crear el nuevo producto solo cambio la variable $activo a 1
        //Una vez obtenemos los datos los podemos enviar a la vista para poder usarlos en los select y option
        //para poder usar esos modelos debo declararlos en el constructor
        $unidades = $this->unidades->where('activo', 1)->findAll();
        $categorias = $this->categorias->where('activo', 1)->findAll();
        $data = array_merge($this->permisosMenus, ['titulo' => 'Agregar producto', 'unidades' => $unidades, 'categorias' => $categorias,]);

        echo view('header', $data);
        echo view('productos/nuevo', $data);
        echo view('footer');
    }
    //funcion para agregar registros

    public function insertar()
    {
        /**Validamos los campos para que no se registren campos vacios y que el metodo de envio sea POST */
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglas)) {

            $this->productos->save([
                'codigo' => $this->request->getpost('codigo'),
                'nombre' => $this->request->getpost('nombre'),
                'precio_venta' => $this->request->getpost('precio_venta'),
                'precio_compra' => $this->request->getpost('precio_compra'),
                'stock_minimo' => $this->request->getpost('stock_minimo'),
                'inventariable' => $this->request->getpost('inventariable'),
                'categorias_id' => $this->request->getpost('categorias_id'),
                'unidades_id' => $this->request->getpost('unidades_id'),
                /**carga de imagenes */
            ]);
            //obtenemos el id del producto del metodo que creamos en el modelo VentasModel ojo verificar
            $id = $this->productos->insertID;
            //codigo para subir multiples imagenes

            if ($imagefile = $this->request->getFiles()) {
                $contador = 1;
                foreach ($imagefile['img_producto'] as $img) {
                    $ruta = "images/productos/" . $id;
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    if ($img->isValid() && ! $img->hasMoved()) {
                        // $newName = $img->getRandomName();
                        // $img->move(WRITEPATH . 'uploads', $newName);
                        $img->move('./images/productos/' . $id, 'foto_' . $contador . '.png');
                        $contador++;
                    }
                }
            }
            //codigo para subir una sola imagen
            // Verifica que se haya cargado el archivo correctamente
            //  $validacion = $this->validate([
            //     'img_producto' => [
            //         'uploaded[img_producto]', // Verifica que se cargue con el campo correctamente
            //         'mime_in[img_producto,image/png]', // Restricción de tipo de archivo ue se recibe en el campo tienda_logo en la vista
            //         'max_size[img_producto,4096]', // Restricción de tamaño
            //     ]
            // ]);

            // if ($validacion) {
            //     $ruta_logo = "images/productos/".$id.".png"; //ruta predefinida del logo
            //     if (file_exists($ruta_logo)) {
            //         unlink($ruta_logo);
            //     }
            //     $img = $this->request->getFile('img_producto');
            //     $img->move('./images/productos', $id.'.png'); // asignamos la ruta donde se va a guardar el archiv y cambiamos el nombre de la imagen a logotipo automaticamente
            // } else {
            //     echo 'Error en la validacion';
            //     exit;
            // }

            return redirect()->to(base_url() . 'productos');
        } else {
            /**imprimimos nuevamente el formulario si no se cumple la condici'on
             * podemos usar el arreglo del formulario inicial de nuevo y le agragamos $this->validator para que nos devuelva
            en el formulario las validaciones que no se cumplieron
             */
            //$producto = $this->productos->where('id', 1)->first();
            $unidades = $this->unidades->where('activo', 1)->findAll();
            $categorias = $this->categorias->where('activo', 1)->findAll();
            $data = [
                'titulo' => 'Agregar producto',
                'unidades' => $unidades,
                'categorias' => $categorias,
                'unidades' => $unidades,
                'validation' => $this->validator
            ];

            echo view('header');
            echo view('productos/nuevo', $data);
            echo view('footer');
        }
    }
    //funcion para pasar al formulario de agregar registros
    public function editar($id, $valid = null)
    {
        //verifico permisos del usuario a este modulo
       $this->verificarAcceso('tienePermisoProductosEditar');
        

        $producto = $this->productos->where('id', $id)->first();
        $unidades = $this->unidades->where('activo', 1)->findAll();
        $categorias = $this->categorias->where('activo', 1)->findAll();
        if ($valid != null) { //si le enviaron la informacion toma data y ademas el valor validation valid         
            //Tambien es necesario enviar el catalogo de unidades y categorias y el dato adicional el producto que estoy consultando
            $data = array_merge($this->permisosMenus,[
                'titulo' => 'Editar producto',
                'unidades' => $unidades,
                'categorias' => $categorias,
                'producto' => $producto,
                'validation' => $valid
            ]);
        } else {
            $data = array_merge($this->permisosMenus,[
                'titulo' => 'Editar producto',
                'unidades' => $unidades,
                'categorias' => $categorias,
                'producto' => $producto
            ]);
        }


        echo view('header',$data);
        echo view('productos/editar', $data);
        echo view('footer');
    }
    //funcion para actualizar registros 
    public function actualizar()
    {
        // Verifica que el método de solicitud sea POST y que la validación de los campos sea exitosa
        if ($this->request->getMethod() == "POST" && $this->validate($this->reglasActualizar)) {
            $id = $this->request->getPost('id');

            // Actualiza la información del producto en la base de datos
            $this->productos->update($id, [
                'codigo' => $this->request->getPost('codigo'),
                'nombre' => $this->request->getPost('nombre'),
                'precio_venta' => $this->request->getPost('precio_venta'),
                'precio_compra' => $this->request->getPost('precio_compra'),
                'stock_minimo' => $this->request->getPost('stock_minimo'),
                'inventariable' => $this->request->getPost('inventariable'),
                'categorias_id' => $this->request->getPost('categorias_id'),
                'unidades_id' => $this->request->getPost('unidades_id'),
            ]);

            // Obtiene los archivos subidos
            if ($imagefile = $this->request->getFiles()) {
                $hasNewImages = false;
                // Verifica si hay nuevas fotos subidas y válidas
                foreach ($imagefile['img_producto'] as $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $hasNewImages = true;
                        break; // Si encuentra una imagen válida, no necesita seguir buscando
                    }
                }

                if ($hasNewImages) {
                    $ruta = "images/productos/" . $id;
                    // Elimina las fotos existentes solo si hay nuevas fotos
                    if (file_exists($ruta)) {
                        $files = glob($ruta . '/*'); // Obtiene todos los archivos en el directorio
                        foreach ($files as $file) {
                            if (is_file($file)) {
                                unlink($file); // Elimina cada archivo
                            }
                        }
                    } else {
                        mkdir($ruta, 0777, true); // Crea el directorio si no existe
                    }

                    // Mueve las nuevas fotos al directorio del producto
                    $contador = 1;
                    foreach ($imagefile['img_producto'] as $img) {
                        if ($img->isValid() && !$img->hasMoved()) {
                            $img->move($ruta, 'foto_' . $contador . '.png');
                            $contador++;
                        }
                    }
                }
            }

            // Redirige a la página de productos
            return redirect()->to(base_url() . 'productos');
        } else {
            // Si la validación falla, muestra el formulario nuevamente con los mensajes de error
            $unidades = $this->unidades->where('activo', 1)->findAll();
            $categorias = $this->categorias->where('activo', 1)->findAll();
            $data = ['titulo' => 'Agregar producto', 'unidades' => $unidades, 'categorias' => $categorias, 'validation' => $this->validator];

            echo view('header');
            echo view('productos/nuevo', $data);
            echo view('footer');
        }
    }


    //codigo para actualizar una sola imagen
    // if ($this->request->getMethod() == "POST" && $this->validate($this->reglasActualizar)) {
    //     $id = $this->request->getPost('id');

    //     // Actualizamos los datos del producto
    //     $this->productos->update($id, [
    //         'codigo' => $this->request->getPost('codigo'),
    //         'nombre' => $this->request->getPost('nombre'),
    //         'precio_venta' => $this->request->getPost('precio_venta'),
    //         'precio_compra' => $this->request->getPost('precio_compra'),
    //         'stock_minimo' => $this->request->getPost('stock_minimo'),
    //         'inventariable' => $this->request->getPost('inventariable'),
    //         'categorias_id' => $this->request->getPost('categorias_id'),
    //         'unidades_id' => $this->request->getPost('unidades_id')
    //     ]);
    //      $img->move('./images/productos/'.$id,'foto_'.$contador.'.png');

    //     // Verificamos si hay una imagen cargada
    //     $img = $this->request->getFile('img_producto');
    //     if ($img && $img->isValid() && !$img->hasMoved()) {
    //         // Validamos la imagen
    //         $validacion = $this->validate([
    //             'img_producto' => [
    //                 'uploaded[img_producto]',
    //                 'mime_in[img_producto,image/png]',
    //                 'max_size[img_producto,4096]'
    //             ]
    //         ]);

    //         if ($validacion) {
    //             $ruta_logo = "images/productos/".$id.".png";
    //             // Eliminamos la imagen existente si hay una
    //             if (file_exists($ruta_logo)) {
    //                 unlink($ruta_logo);
    //             }
    //             // Movemos la nueva imagen a la carpeta correspondiente
    //             $img->move('./images/productos', $id.'.png');
    //         } else {
    //             echo 'Error en la validación de la imagen';
    //             exit;
    //         }
    //     }

    //     return redirect()->to(base_url() . 'productos');
    // } else {
    //     return $this->editar($this->request->getPost('id'), $this->validator);
    // }


    //funcion para eliminar productos no las elimina de la base
    //de datos simplemente actualiza su estado a 0 y retorna a la vista productos
    public function eliminar($id)
    {
        //verifico permisos de usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoProductosEliminar');
        $this->productos->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'productos');
    }

    public function eliminados($activo = 0) //enviamos un valor predefinido con el fin que la funcion no tenga error y poder hacer la consulta de productos con estado 
    {
        $this->verificarAcceso('tienePermisoProductosEliminados');
        //consulta a la base de datos tabla productos trae todos los registros cuando activo sea = 0 la variable
        $productos = $this->productos->where('activo', $activo)->findAll();
        //la informaci'on que le vamos a enviar a la vista
        
        $data = array_merge($this->permisosMenus,['titulo' => 'Productos eliminados', 'datos' => $productos]);

        echo view('header',$data);
        echo view('productos/eliminados', $data);
        echo view('footer');
    }
    public function reingresar($id)
    {
        //verifico permisos de usuario para este modulo o acci'on
        $this->verificarAcceso('tienePermisoProductosReingresar');
        $this->productos->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'productos');
    }
    //Function para consultar productos por codigo y enviarlos a la vista compras
    public function buscarPorCodigo($codigo)
    {
        //Realizamos la consulta de forma diferente a como la estamos trabajando pero usando codeigniter asi:
        $this->productos->select('*'); //definimos que columnas traemos en este caso *para que nos traiga todo
        $this->productos->where('codigo', $codigo);
        $this->productos->where('activo', 1);
        $datos = $this->productos->get()->getRow(); //traiga toda la informacion con get nos trae todas la filas que encontro pero nos trae un solo
        // registro con getRow es decir la fila del producto que encontro es decir nos trae la fila del producto que encontro

        $res['existe'] = false;
        $res['datos'] = '';
        $res['error'] = '';

        if ($datos) { // si $datos encontro la informaci'on solicitada la almacenamos en el arreglo res['datos']
            $res['datos'] = $datos;
            $res['existe'] = true; // para indicar que si encontro la informaci'on

        } else {
            $res['error'] = 'No existe el producto';
            $res['existe'] = false;
        }

        echo json_encode($res); // lo voy a recibir como y json y poder trabajrlo con ajax



    }
    public function autocompleteData() //metodo para autocompletar productos en el formulario de caja y poder realizar ventas
    {
        $returnData = array();
        $valor = $this->request->getGet('term'); //obtenemos la informacion del evento y la asignamos al valor term de termino
        $productos = $this->productos->like('codigo', $valor)->where('activo', 1)->findAll(); //query donde filtramos por activo y ademas por nombre de producto
        //like es para que busque en toda la palabra lo que se asemeje al termino que estamos colocando y nos traiga todos los resultados con findAll

        if (!empty($productos)) { //validamos que si envie algo
            foreach ($productos as $row) {
                $data['id'] = $row['id']; //Se le asigna la fila o la columna que se llama id
                $data['value'] = $row['nombre']; //Es necesatio agregar al arreglo el nobre y asignarlo a la variable value para que lo reconozca el autocomplete
                $data['label'] = $row['codigo'] . '-' . $row['nombre'];
                array_push($returnData, $data); //agregamos al arreglo returnData y los datos que van en ese arreglo



            }
        }
        echo json_encode($returnData); //retornamos el arreglo

    }
    function muestraCodigos()
    {
        $this->verificarAcceso('tienePermisoProductosMuestraCodigos');
        $data = array_merge($this->permisosMenus);

        echo view('header',$data);
        echo view('productos/ver_codigos');
        echo view('footer');
    }
    public function generaBarras()
    {
        //se requiere la libreria barcode descargamos lal ibreria copiamos el archivo barcode.php en el directorio libraries de nuestro proyecto modificamos el archivi
        // se elimina el codigo de prueba y se mete dentro de una clase que llamamos Barcode la cual llamamos en el autoload como se hizo con el fpdf y aqui en el controlador generamos
        //una nueva instancia
        $pdf = new \FPDF('P', 'mm', 'letter'); //creamos un nuevo pdf para almacenar el codigo en dicho formato ya que de manera original se genera es una imagen
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Codigos de barras");
        //consulta a la base de datos tabla productos trae todos los registros cuando activo sea = a la variable
        $productos = $this->productos->where('activo', 1)->findAll();
        foreach ($productos as $producto) {
            $codigo = $producto['codigo'];
            $generaBarcode = new \Barcode_genera();
            $generaBarcode->barcode("images/barcode/" . $codigo . ".png", $codigo, 20, "horizontal", "code39", true); //ruta del archivo.png, texto, tamano, orientaci'on cod39 o code128
            // que con los regulares que se usan en codigos de barras
            $pdf->Image("images/barcode/" . $codigo . ".png");
            //Una vez creados los codigos de barras se deben borrar las imagenes
            unlink("images/barcode/" . $codigo . ".png");
        }
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Codigos.pdf', 'I');
    }
    function mostrarMinimos()
    {
        $this->verificarAcceso('tienePermisoMenuReporteMinimos');
        $data = array_merge($this->permisosMenus);
        echo view('header',$data);
        echo view('productos/ver_minimos');
        echo view('footer');
    }
    public function generaMinimosPdf()
    {
        $nombreTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;


        $pdf = new \FPDF('P', 'mm', 'Letter'); // Orientación, medida, tamaño carta, se coloca diagonal inversa \ para que detecte la libreria
        $pdf->AddPage(); // Agregamos una página
        $pdf->SetMargins(10, 10, 10); // Márgenes del documento: izquierda, arriba, derecha
        $pdf->SetTitle('Productos con stock minimo'); // Título
        $pdf->SetFont('Arial', 'B', 10); // Definimos tipo de letra, estilo negrilla, tamaño 10 puntos
        $pdf->image(base_url() . '/images/logotipo.png', 10, 10, 20, 20, 'png'); // es necesario crear el directorio images en  la raiz del proyecto posicion en x, en y en ancho y alto
        $pdf->Cell(0, 5, mb_convert_encoding($nombreTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Cell(0, 5, mb_convert_encoding($direccionTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Cell(0, 5, mb_convert_encoding("Reporte de productos con stock mínimo", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Ln(20);

        $pdf->Cell(40, 5, mb_convert_encoding("Código", 'ISO-8859-1', 'UTF-8'), 1, 0, "C"); //alto,ancho,--borde 1, 0 salto de linea centrado
        $pdf->Cell(80, 5, mb_convert_encoding("Nombre", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Cell(35, 5, mb_convert_encoding("Existencias", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Cell(35, 5, mb_convert_encoding("Stock Minimo", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Ln(5);
        $productos = $this->productos->getproductosMinimo(); //llamamos el metodo para traer los productos con stock minimo que creamos en el modelo
        foreach ($productos as $producto) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 5, $producto['codigo'], 1, 0, "C"); //alto,ancho,--borde 1, 0 salto de linea centrado
            $pdf->Cell(80, 5, mb_convert_encoding($producto['nombre'], 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
            $pdf->Cell(35, 5, $producto['existencias'], 1, 0, "C");
            $pdf->Cell(35, 5, $producto['stock_minimo'], 1, 0, "C");
            $pdf->Ln(5);
        }
        $pdf->Output('ProductoMinimo.pdf', 'I');
    }
    function mostrarProductos()
    {
        $this->verificarAcceso('tienePermisoMostrarReporteProductos');
        $data =$data = array_merge($this->permisosMenus);
        echo view('header',$data);
        echo view('productos/ver_listadoProductos');
        echo view('footer');
    }
    public function generaProductosPdf()
    {
        $nombreTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;

        $pdf = new \FPDF('l', 'mm', 'Letter'); // Orientación horizontal l, medida, tamaño carta, se coloca diagonal inversa \ para que detecte la libreria
        $pdf->AddPage(); // Agregamos una página
        $pdf->SetMargins(10, 10, 10); // Márgenes del documento: izquierda, arriba, derecha
        $pdf->SetTitle('Listado general de productos'); // Título
        $pdf->SetFont('Arial', 'B', 10); // Definimos tipo de letra, estilo negrilla, tamaño 10 puntos
        $pdf->image(base_url() . '/images/logotipo.png', 10, 10, 20, 20, 'png'); // es necesario crear el directorio images en  la raiz del proyecto posicion en x, en y en ancho y alto
        $pdf->Cell(0, 5, mb_convert_encoding($nombreTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Cell(0, 5, mb_convert_encoding($direccionTienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Cell(0, 5, mb_convert_encoding("Reporte general de productos", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Ln(20);

        $pdf->Cell(30, 5, mb_convert_encoding("Código", 'ISO-8859-1', 'UTF-8'), 1, 0, "C"); //alto,ancho,--borde 1, 0 salto de linea centrado
        $pdf->Cell(80, 5, mb_convert_encoding("Nombre", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Cell(35, 5, mb_convert_encoding("Precio de compra", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Cell(35, 5, mb_convert_encoding("precio de venta", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Cell(35, 5, mb_convert_encoding("Existencias", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Cell(35, 5, mb_convert_encoding("Stock Minimo", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
        $pdf->Ln(5);
        $productos = $this->productos->listadoProductosActivos(); //llamamos el metodo para traer los productos con stock minimo que creamos en el modelo
        foreach ($productos as $producto) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(30, 5, $producto['codigo'], 1, 0, "C"); //alto,ancho,--borde 1, 0 salto de linea centrado
            $pdf->Cell(80, 5, mb_convert_encoding($producto['nombre'], 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
            $pdf->Cell(35, 5, $producto['precio_compra'], 1, 0, "C");
            $pdf->Cell(35, 5, $producto['precio_venta'], 1, 0, "C");
            $pdf->Cell(35, 5, $producto['existencias'], 1, 0, "C");
            $pdf->Cell(35, 5, $producto['stock_minimo'], 1, 0, "C");
            $pdf->Ln(5);
        }
        $pdf->Output('Producto.pdf', 'I');
    }
}
