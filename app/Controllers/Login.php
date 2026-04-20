<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Login extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function auth()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
            ->withInput()
            ->with('error_login', 'El correo o la contraseña son incorrectos.');
        }

        $usuarioModel = new UsuarioModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usuario = $usuarioModel->validarUsuario($email, $password);

        if ($usuario) {
            $this->iniciarSesion($usuario);
            return redirect()->to(base_url('panel'));
        }
        echo $usuario;
        return redirect()->back()
            ->withInput()
            ->with('error_login', 'El correo o la contraseña son incorrectos.');
    }

    private function iniciarSesion($usuarioData)
    {
        $data = [
            'logged_in' => true,
            'idUsuario' => $usuarioData['idUsuario'],
            'emailUsuario' => $usuarioData['emailUsuario'],
            'nombreUsuario' => $usuarioData['nombreUsuario'],
            'idRolUsuario' => $usuarioData['idRolUsuario']
        ];

        $this->session->set($data);
    }

    public function logout(){
        if($this->session->get('logged_in')){
            $this->session->destroy();
        }
        return redirect()->to(base_url('login'));
    }
}
