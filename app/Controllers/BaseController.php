<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
/*------------------------------------*/
use App\Models\DetalleRolesPermisosModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

    protected $request;
    protected $detalleRoles;
    protected $permisosMenus;
    protected $helpers = [];
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */


    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $this->detalleRoles = new DetalleRolesPermisosModel(); // Inicializa el modelo correctamente
        $this->permisosMenus = $this->cargarPermisosMenus();
    }
    // Método para cargar permisos de menús y submenús

    protected function cargarPermisosMenus()
    {
        $idRol = session()->get('roles_id'); // Obtén el rol del usuario desde la sesión

        // Llama directamente al método verificaPermisos del modelo para cada permiso
        return [
            /*---------------------------Productos--------------------------------------------------------*/
            'tienePermisoMenuProductos' => $this->detalleRoles->verificaPermisos($idRol, 'MenuProductos'),
            'tienePermisoProductosCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'ProductosCatalogo'),
            'tienePermisoProductosAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'ProductosAgregar'),
            'tienePermisoProductosEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'ProductosEliminados'),
            'tienePermisoProductosMuestraCodigos' => $this->detalleRoles->verificaPermisos($idRol, 'ProductosMuestraCodigos'),
            'tienePermisoProductosEditar' => $this->detalleRoles->verificaPermisos($idRol, 'ProductosEditar'),
            'tienePermisoProductosEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'ProductosEliminar'),
            'tienePermisoProductosReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'ProductosReingresar'),
            /*---------------------------Unidades---------------------------------------------------------*/
            'tienePermisoUnidadesCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'UnidadesCatalogo'),
            'tienePermisoUnidadesAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'UnidadesAgregar'),
            'tienePermisoUnidadesEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'UnidadesEliminados'),
            'tienePermisoUnidadesEditar' => $this->detalleRoles->verificaPermisos($idRol, 'UnidadesEditar'),
            'tienePermisoUnidadesEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'UnidadesEliminar'),
            'tienePermisoUnidadesReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'UnidadesReingresar'),
            /*---------------------------Categorias--------------------------------------------------------*/
            'tienePermisoCategoriasCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriasCatalogo'),
            'tienePermisoCategoriasAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriasAgregar'),
            'tienePermisoCategoriasEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriasEliminados'),
            'tienePermisoCategoriasEditar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriasEditar'),
            'tienePermisoCategoriasEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriasEliminar'),
            'tienePermisoCategoriasReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriasReingresar'),
            /*---------------------------Clientes----------------------------------------------------------*/
            'tienePermisoMenuClientes' => $this->detalleRoles->verificaPermisos($idRol, 'MenuClientes'),
            'tienePermisoClientesCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'ClientesCatalogo'),
            'tienePermisoClientesAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'ClientesAgregar'),
            'tienePermisoClientesEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'ClientesEliminados'),
            'tienePermisoClientesEditar' => $this->detalleRoles->verificaPermisos($idRol, 'ClientesEditar'),
            'tienePermisoClientesEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'ClientesEliminar'),
            'tienePermisoClientesReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'ClientesReingresar'),
            /*---------------------------Compras-----------------------------------------------------------*/
            'tienePermisoMenuCompras' => $this->detalleRoles->verificaPermisos($idRol, 'MenuCompras'),
            'tienePermisoComprasCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'ComprasCatalogo'),
            'tienePermisoComprasAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'ComprasAgregar'),
            'tienePermisoComprasEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'ComprasEliminados'),
            'tienePermisoComprasEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'ComprasEliminar'),
            /*---------------------------CajaVentas--------------------------------------------------------*/
            'tienePermisoMenuCaja' => $this->detalleRoles->verificaPermisos($idRol, 'MenuCaja'),
            'tienePermisoMenuVentas' => $this->detalleRoles->verificaPermisos($idRol, 'VentasCatalogo'),
            'tienePermisoVentasEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'VentasEliminados'),
            'tienePermisoVentasEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'VentasEliminar'),
            /*---------------------------Reportes-----------------------------------------------------------*/
            'tienePermisoMenuReportes' => $this->detalleRoles->verificaPermisos($idRol, 'MenuReportes'),
            'tienePermisoMenuReporteMinimos' => $this->detalleRoles->verificaPermisos($idRol, 'ReporteMinimos'),
            'tienePermisoMenuFormularioReporteVentas' => $this->detalleRoles->verificaPermisos($idRol, 'FormularioVentasPorFecha'),
            'tienePermisoMenuReporteVentas' => $this->detalleRoles->verificaPermisos($idRol, 'ReporteVentas'),
            'tienePermisoMenuReporteProductos' => $this->detalleRoles->verificaPermisos($idRol, 'ReporteProductos'),
            'tienePermisoMostrarReporteProductos' => $this->detalleRoles->verificaPermisos($idRol, 'MostrarReporteProductos'),
            'tienePermisoMenuReporteProductosCategoria' => $this->detalleRoles->verificaPermisos($idRol, 'ReporteProductosCategoria'),
            /*---------------------------Administraci'on-----------------------------------------------------*/
            'tienePermisoMenuAdministracion' => $this->detalleRoles->verificaPermisos($idRol, 'MenuAdministracion'),
            'tienePermisoConfiguracion' => $this->detalleRoles->verificaPermisos($idRol, 'Configuracion'),
            /*---------------------------Usuarios------------------------------------------------------------*/
            'tienePermisoUsuariosCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'UsuariosCatalogo'),
            'tienePermisoUsuariosAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'UsuariosAgregar'),
            'tienePermisoUsuariosEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'UsuariosEliminados'),
            'tienePermisoUsuariosEditar' => $this->detalleRoles->verificaPermisos($idRol, 'UsuariosEditar'),
            'tienePermisoUsuariosEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'UsuariosEliminar'),
            'tienePermisoUsuariosReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'UsuariosReingresar'),
            /*---------------------------Roles---------------------------------------------------------------*/
            'tienePermisoRolesCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'RolesCatalogo'),
            'tienePermisoRolesAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'RolesAgregar'),
            'tienePermisoRolesEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'RolesEliminados'),
            'tienePermisoRolesEditar' => $this->detalleRoles->verificaPermisos($idRol, 'RolesEditar'),
            'tienePermisoRolesEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'RolesEliminar'),
            'tienePermisoRolesReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'RolesReingresar'),
            'tienePermisoRolesDetalle' => $this->detalleRoles->verificaPermisos($idRol, 'RolesDetalle'),
            /*---------------------------Permisos------------------------------------------------------------*/
            'tienePermisoPermisosCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'PermisosCatalogo'),
            'tienePermisoPermisosAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'PermisosAgregar'),
            'tienePermisoPermisosEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'PermisosEliminados'),
            'tienePermisoPermisosEditar' => $this->detalleRoles->verificaPermisos($idRol, 'PermisosEditar'),
            'tienePermisoPermisosEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'PermisosEliminar'),
            'tienePermisoPermisosReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'PermisosReingresar'),
            /*---------------------------Cajas---------------------------------------------------------------*/
            'tienePermisoCajasCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'CajasCatalogo'),
            'tienePermisoCajasAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'CajasAgregar'),
            'tienePermisoCajasEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'CajasEliminados'),
            'tienePermisoCajasEditar' => $this->detalleRoles->verificaPermisos($idRol, 'CajasEditar'),
            'tienePermisoCajasEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'CajasEliminar'),
            'tienePermisoCajasReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'CajasReingresar'),
            'tienePermisoCajasArqueo' => $this->detalleRoles->verificaPermisos($idRol, 'CajasArqueo'),
             /*---------------------------CategoriaPermisos--------------------------------------------------------*/
             'tienePermisoMenuCategoriaPermisos' => $this->detalleRoles->verificaPermisos($idRol, 'MenuCategoriaPermisos'),
             'tienePermisoCategoriaPermisosCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriaPermisosCatalogo'),
             'tienePermisoCategoriaPermisosAgregar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriaPermisosAgregar'),
             'tienePermisoCategoriaPermisosEliminados' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriaPermisosEliminados'),
             'tienePermisoCategoriaPermisosEditar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriaPermisosEditar'),
             'tienePermisoCategoriaPermisosEliminar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriaPermisosEliminar'),
             'tienePermisoCategoriaPermisosReingresar' => $this->detalleRoles->verificaPermisos($idRol, 'CategoriaPermisosReingresar'),
            /*---------------------------Logs----------------------------------------------------------------*/
            'tienePermisoLogsCatalogo' => $this->detalleRoles->verificaPermisos($idRol, 'LogsCatalogo'),


        ];
    }
    // Método para verificar permisos para una vista específica
    protected function verificarAcceso($permisoClave)
    {
        // Verifica si el permiso existe y si es falso
        if (!isset($this->permisosMenus[$permisoClave]) || !$this->permisosMenus[$permisoClave]) {
            $data = array_merge($this->permisosMenus,['titulo' => 'Error de permisos', 'mensaje' => 'No cuentas con los permisos requeridos para completar esta acción o visualizar esta sección.']);
            echo view('header',$data);
            echo view('errors/html/error_401', $data);
            echo view('footer');
            exit; // Detiene la ejecuci'on de este codigo
        }
    }
}
