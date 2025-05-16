<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
/**Hay que cambiar el controlador a Usuarios y crear el metodo login con el fin de que el sistema inicie por la pagina de login or lo cual es necesario crear el metodo login */

/**-------------------------ruta unidades-------------------------------**/
$routes->get('/unidades', 'Unidades::index');
$routes->get('/unidades/nuevo', 'Unidades::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/unidades/insertar', 'Unidades::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/unidades/editar/(:num)', 'Unidades::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/unidades/actualizar', 'Unidades::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/unidades/eliminar/(:num)', 'Unidades::eliminar/$1');
$routes->get('/unidades/eliminados', 'Unidades::eliminados');
$routes->get('/unidades/reingresar/(:num)', 'Unidades::reingresar/$1');

/**--------------------------rutas categorias------------------------------------------------------ */
$routes->get('/categorias', 'Categorias::index');
$routes->get('/categorias/nuevo', 'Categorias::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/categorias/insertar', 'Categorias::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/categorias/editar/(:num)', 'Categorias::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/categorias/actualizar', 'Categorias::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/categorias/eliminar/(:num)', 'Categorias::eliminar/$1');
$routes->get('/categorias/eliminados', 'Categorias::eliminados');
$routes->get('/categorias/reingresar/(:num)', 'Categorias::reingresar/$1');

/**--------------------------rutas productos------------------------------------------------------ */
$routes->get('/productos', 'Productos::index');
$routes->get('/productos/nuevo', 'Productos::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/productos/insertar', 'Productos::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/productos/editar/(:num)', 'Productos::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/productos/actualizar', 'Productos::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/productos/eliminar/(:num)', 'Productos::eliminar/$1');
$routes->get('/productos/eliminados', 'Productos::eliminados');
$routes->get('/productos/reingresar/(:num)', 'Productos::reingresar/$1');
/**Agregamos la ruta para la busqueda de productos por codigo en compras que recibe el campo oculto hidden de la vista id y lo envia al controlador */
$routes->get('productos/buscarPorCodigo/(:num)', 'Productos::buscarPorCodigo/$1');
$routes->get('/productos/autocompleteData', 'Productos::autocompleteData');//ruta para permitir el acceso a busqueda de productos en autocompletado en la caja
$routes->get('/productos/muestraCodigos', 'Productos::muestraCodigos');
$routes->get('/productos/generaBarras', 'Productos::generaBarras');
$routes->get('/productos_minimo', 'Productos::productosMinimo');
$routes->get('/productos/mostrarMinimos', 'Productos::mostrarMinimos');
$routes->get('/productos/generaMinimosPdf', 'Productos::generaMinimosPdf');
$routes->get('/productos/mostrarProductos', 'Productos::mostrarProductos');
$routes->get('/productos/generaProductosPdf', 'Productos::generaProductosPdf');


/**--------------------------rutas clientes------------------------------------------------------ */
$routes->get('/clientes', 'Clientes::index');
$routes->get('/clientes/nuevo', 'Clientes::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/clientes/insertar', 'Clientes::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/clientes/editar/(:num)', 'Clientes::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/clientes/actualizar', 'Clientes::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/clientes/eliminar/(:num)', 'Clientes::eliminar/$1');
$routes->get('/clientes/eliminados', 'Clientes::eliminados');
$routes->get('/clientes/reingresar/(:num)', 'Clientes::reingresar/$1');
$routes->get('/clientes/autocompleteData', 'Clientes::autocompleteData');//ruta para permitir el acceso a busqueda de clientes en autocompletado en la caja
/**--------------------------rutas configuraci'on------------------------------------------------------ */
$routes->get('/configuracion', 'Configuracion::index');
$routes->post('/configuracion/actualizar', 'Configuracion::actualizar');

/**--------------------------rutas roles------------------------------------------------------ */
$routes->get('/roles', 'Roles::index');
$routes->get('/roles/nuevo', 'Roles::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/roles/insertar', 'Roles::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/roles/editar/(:num)', 'Roles::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/roles/actualizar', 'Roles::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/roles/eliminar/(:num)', 'Roles::eliminar/$1');
$routes->get('/roles/eliminados', 'Roles::eliminados');
$routes->get('/roles/reingresar/(:num)', 'Roles::reingresar/$1');
$routes->get('/roles/detalles/(:alphanum)', 'Roles::detalles/$1');
$routes->post('roles/guardaPermisos', 'Roles::guardaPermisos');
/**--------------------------rutas cajas------------------------------------------------------ */
$routes->get('/cajas', 'Cajas::index');
$routes->get('/cajas/nuevo', 'Cajas::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/cajas/insertar', 'Cajas::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/cajas/editar/(:num)', 'Cajas::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/cajas/actualizar', 'Cajas::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/cajas/eliminar/(:num)', 'Cajas::eliminar/$1');
$routes->get('/cajas/eliminados', 'Cajas::eliminados');
$routes->get('/cajas/reingresar/(:num)', 'Cajas::reingresar/$1');
$routes->get('cajas/arqueo/(:num)', 'Cajas::arqueo/$1'); 
$routes->get('cajas/nuevo_arqueo', 'Cajas::nuevo_arqueo');
$routes->post('cajas/nuevo_arqueo', 'Cajas::nuevo_arqueo');
$routes->get('cajas/cerrar_caja/(:num)', 'Cajas::cerrarCaja/$1'); 
$routes->post('cajas/cerrarCaja', 'Cajas::cerrarCaja'); 

/**--------------------------rutas usuarios------------------------------------------------------ */
$routes->get('/usuarios', 'Usuarios::index');
$routes->get('/usuarios/nuevo', 'Usuarios::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/usuarios/insertar', 'Usuarios::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/usuarios/editar/(:num)', 'Usuarios::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/usuarios/actualizar', 'Usuarios::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/usuarios/eliminar/(:num)', 'Usuarios::eliminar/$1');
$routes->get('/usuarios/eliminados', 'Usuarios::eliminados');
$routes->get('/usuarios/reingresar/(:num)', 'Usuarios::reingresar/$1');
/**-------------------------Ruta usuario login y logout----------------------------- */
$routes->get('/', 'Usuarios::login');
$routes->post('/usuarios/valida', 'Usuarios::valida');
//ruta para acceder al dashboard o inicio de la pagina  que en este caso llamamos inicio
$routes->get('inicio', 'Inicio');
$routes->get('/usuarios/logout', 'Usuarios::logout');
$routes->get('/usuarios/cambia_password', 'Usuarios::cambia_password');
$routes->post('/usuarios/actualizar_password', 'Usuarios::actualizar_password');
/**--------------------------rutas compras------------------------------------------------------ */
$routes->get('/compras', 'Compras::index');
$routes->get('/compras/nuevo', 'Compras::nuevo');
//$routes->get('/compras/guarda', 'Compras::guarda');
//Aquí, (:num) indica que el parámetro debe ser un número, y (:alphanum) permite tanto letras como números el cual
// se agrega porque estamos creando un codigo en php alfanumerico en la vista.
$routes->get('/temporalCompra/insertarCompra/(:num)/(:num)/(:alphanum)', 'TemporalCompra::insertarCompra/$1/$2/$3');
$routes->get('/temporalCompra/eliminar/(:num)/(:alphanum)', 'TemporalCompra::eliminaProducto/$1/$2');
$routes->post('compras/guarda', 'Compras::guarda');
//ruta generar reportes compras pdf tener en cuenta el llamado de los metodos para gnerar las rutas necesarias
$routes->get('/compras/generaCompraPdf/(:num)', 'Compras::generaCompraPdf/$1');
$routes->get('/compras/muestraCompraPdf/(:num)', 'Compras::muestraCompraPdf/$1');
$routes->get('compras/eliminados', 'Compras::eliminados');
$routes->get('/compras/eliminar/(:num)', 'Compras::eliminar/$1');
/**--------------------------rutas ventas------------------------------------------------------ */
$routes->get('/ventas/venta', 'Ventas::venta');
$routes->post('/ventas/guarda', 'Ventas::guarda');
$routes->get('/temporalCompra/insertarVenta/(:num)/(:num)/(:alphanum)', 'TemporalCompra::insertarVenta/$1/$2/$3');
$routes->get('/ventas/muestraTicket/(:num)', 'Ventas::muestraTicket/$1');
$routes->get('/ventas/generaTicket/(:num)', 'Ventas::generaTicket/$1');
$routes->get('/ventas', 'Ventas::index');
$routes->get('/ventas/eliminar/(:num)', 'Ventas::eliminar/$1');
$routes->get('/ventas/eliminados', 'Ventas::eliminados');
$routes->get('/ventas/reingresar/(:num)', 'Ventas::reingresar/$1');
$routes->get('/ventas/ventas_caja', 'Ventas::ventasDia');
$routes->get('/ventas/mostrarVentasDia', 'Ventas::mostrarVentasDia');
$routes->get('/ventas/generaVentasDiaPdf', 'Ventas::generaVentasDiaPdf');
/*----------------------------------reportes ventas---------------------------------------------------------------*/
// Ruta para mostrar la selección de parámetros (fechas y caja)
$routes->get('ventas/seleccionarReporteVentas', 'Ventas::seleccionarReporteVentas');
//Ruta para enviar los datos seleccionados a la funci'on mostrar reporte
$routes->post('ventas/mostrarReporteVentas', 'Ventas::mostrarReporteVentas');
// Ruta para generar el reporte y mostrarlo en el iframe
$routes->get('ventas/generaReporteVentas/(:any)/(:any)/(:num)', 'Ventas::generaReporteVentas/$1/$2/$3');











/**--------------------------rutas permisos------------------------------------------------------ */
$routes->get('/permisos', 'Permisos::index');
$routes->get('/permisos/nuevo', 'Permisos::nuevo');
/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */
$routes->post('/permisos/insertar', 'Permisos::insertar');
/** Usamos el comodin :num para capturar el numero que llegue en la url y en la ruta del controlador $1 para
 *  indicar que tome el valor$1 se refiere al primer segmento capturado por el comodín (:num). En este caso, 
 * será el número que aparezca en la URI en la posición del comodín. Este número será pasado como argumento al 
 * método editar del controlador Unidades. */
$routes->get('/permisos/editar/(:num)', 'Permisos::editar/$1');
/**
 * Como el formulario editar envia tres parametros al controlador id y nombre a editar y nombre corto a editar no se requiereel comodin usado en la ruta anterior
 * porque ya esos datos se habian obtenido desde la vista editar
 */
$routes->post('/permisos/actualizar', 'Permisos::actualizar');
/**Ruta para eliminar usamos nuevamente el comodin :num para enviar el id del registro a modificar el estado activo a 0 */
$routes->get('/permisos/eliminar/(:num)', 'Permisos::eliminar/$1');
$routes->get('/permisos/eliminados', 'Permisos::eliminados');
$routes->get('/permisos/reingresar/(:num)', 'Permisos::reingresar/$1');
/**-------------------------ruta categoriaPermisos-------------------------------**/
$routes->get('/categoriaPermisos', 'CategoriaPermisos::index');
$routes->get('/categoriaPermisos/nuevo', 'CategoriaPermisos::nuevo');
$routes->post('/categoriaPermisos/insertar', 'CategoriaPermisos::insertar');
$routes->get('/categoriaPermisos/editar/(:num)', 'CategoriaPermisos::editar/$1');
$routes->post('/categoriaPermisos/actualizar', 'CategoriaPermisos::actualizar');
$routes->get('/categoriaPermisos/eliminar/(:num)', 'CategoriaPermisos::eliminar/$1');
$routes->get('/categoriaPermisos/eliminados', 'CategoriaPermisos::eliminados');
$routes->get('/categoriaPermisos/reingresar/(:num)', 'CategoriaPermisos::reingresar/$1');
$routes->get('reset-admin-password', 'ResetAdminPassword::index');


/**--------------------------rutas Logs------------------------------------------------------ */
$routes->get('/logs', 'Logs::index');
/**--------------------------ruta para generar excel------------------------------------------------------ */
$routes->get('/inicio/excel', 'Inicio::excel');


































/**Ojo como en la vista se crea un formulario que envia datos post en la ruta tambien se debe cambiar a post
 */



