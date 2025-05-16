<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            // Aquí deberías manejar la lógica de autenticación
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Verifica las credenciales (esto es solo un ejemplo básico)
            if ($username === 'admin' && $password === '1234') {
                // Establece la sesión si las credenciales son correctas
                session()->set('isLoggedIn', true);

                // Redirige al dashboard o alguna página protegida
                return redirect()->to('/dashboard');
            } else {
                // Maneja el error de autenticación
                return redirect()->back()->with('error', 'Credenciales incorrectas');
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
        // Destruye la sesión para cerrar sesión
        session()->destroy();
        return redirect()->to('/login');
    }
}
