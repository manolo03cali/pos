<?php
namespace App\Controllers;

use App\Models\UsuariosModel;

class ResetAdminPassword extends BaseController
{
    public function index()
    {
        // Datos del administrador
        $usuario = 'manuel'; // Reemplaza con el nombre de usuario del administrador
        $nuevaPassword = '123456'; // Nueva contraseña

        // Cifrar la nueva contraseña
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $usuariosModel = new UsuariosModel();
        $usuariosModel->where('usuario', $usuario)->set(['password' => $hash])->update();

        echo "Contraseña del administrador restablecida correctamente.";
    }
}