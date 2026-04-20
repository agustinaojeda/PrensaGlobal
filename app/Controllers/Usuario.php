<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Usuario extends BaseController
{
    public function registro()
    {
        return view('registro');
    }

    public function crear()
    {
        $rules = [
            'email' => 'required|valid_email|is_unique[usuarios.emailUsuario]|max_length[80]',
            'password' => 'required|min_length[8]|max_length[50]',
            'repassword' => 'matches[password]',
            'name' => 'required|min_length[10]|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'rol' => 'required|in_list[1,2,3]'
        ];

        $messages = [

            'email' => [
                'required' => 'El correo electrónico es obligatorio.',
                'valid_email' => 'Ingresá un correo electrónico válido.',
                'is_unique' => 'Este correo ya está registrado.',
                'max_length' => 'El correo no puede superar los {param} caracteres.'
            ],

            'password' => [
                'required' => 'La contraseña es obligatoria.',
                'min_length' => 'La contraseña debe tener al menos {param} caracteres.',
                'max_length' => 'La contraseña no puede superar los {param} caracteres.'
            ],

            'repassword' => [
                'required' => 'Debes confirmar la contraseña.',
                'matches' => 'Las contraseñas no coinciden.'
            ],

            'name' => [
                'required' => 'El nombre es obligatorio.',
                'min_length' => 'El nombre debe tener al menos {param} caracteres.',
                'max_length' => 'El nombre no puede superar los {param} caracteres.',
                'regex_match' => 'El nombre solo debe contener letras y espacios.'
            ],

            'rol' => [
                'required' => 'Debes seleccionar un rol.',
                'in_list' => 'El rol seleccionado no es válido.'
            ]

        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $usuarioModel = new UsuarioModel();
        $post = $this->request->getPost(['name', 'email', 'password', 'rol']);
        $token = bin2hex(random_bytes(20));

        $usuarioModel->agregarUsuario($post,$token);

        $email = \Config\Services::email();

        $email->setTo($post['email']);
        $email->setSubject('Activa tu cuenta en Prensa Global');

        $url = base_url('activar-cuenta/' . $token);

        $body = '<p>¡Hola ' . $post['name'] . '!,</p>';
        $body .= '<p>Gracias por registrarte en Prensa Global. Para activar tu cuenta, haz clic en el siguiente enlace:</p>';
        $body .= '<p><a href="' . $url . '">Activar mi cuenta</a></p>';
        $body .= '<p>Si no te registraste, puedes ignorar este correo.</p>';
        $body .= '<p>Saludos,<br>El equipo de Prensa Global</p>';

        $email->setMessage($body);
        $email->send();

        $title = 'Registro exitoso';
        $message = 'Revisa tu correo electrónico para activar tu cuenta.';

        return $this->mostrarMensaje($title, $message);
    }

    public function activarCuenta($token)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->buscarPorTokenActivacion($token);

        if (!$usuario) {
            $title = 'Ocurrió un error';
            $message = 'El enlace de activación no es válido. Por favor, verifica tu correo o regístrate nuevamente.';
            return $this->mostrarMensaje($title, $message);
        }

        $usuarioModel->activarCuenta($usuario);

        $title = 'Cuenta activada';
        $message = 'Tu cuenta ha sido activada exitosamente. Ya puedes iniciar sesión.';

        return $this->mostrarMensaje($title, $message);
    }

    public function linkRequestForm()
    {
        return view('requestForm');
    }

    public function enviarEmailReset()
    {
        $rules = [
            'email' => 'required|valid_email|max_length[80]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error_request', 'El correo no es válido.');
        }

        $usuarioModel = new UsuarioModel();
        $email = $this->request->getPost('email');
        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario) {
            $token = bin2hex(random_bytes(20));

            $usuarioModel->tokenReset($token, $usuario);

            $email = \Config\Services::email();

            $email->setTo($usuario['emailUsuario']);
            $email->setSubject('Recuperar contraseña');

            $url = base_url('reset-password/' . $token);

            $body = '<p>Hola ' . $usuario['nombreUsuario'] . ', </p>';
            $body .= '<p>Se ha solicitado un restablecimiento de contraseña.<br>Para hacerlo, haz clic en el siguiente enlace:</p>';
            $body .= '<p><a href="' . $url . '">Restablecer contraseña</a></p>';
            $body .= '<p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este correo.</p>';
            $body .= '<p>Saludos,<br>El equipo de Prensa Global</p>';

            $email->setMessage($body);
            $email->send();
        }

        $title = 'Solicitud de restablecimiento de contraseña';
        $message = 'Se ha enviado un correo electrónico con instrucciones para restablecer tu contraseña.';

        return $this->mostrarMensaje($title, $message);
    }

    public function resetForm($token)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->buscarPorTokenReset($token);

        if ($usuario) {
            $horaLocal = new \DateTime();
            $horaLocalSt = $horaLocal->format('Y-m-d H:i:s');

            if ($horaLocalSt <= $usuario['resetTokenExpira']) {
                return view('resetForm', ['token' => $token]);
            } else {
                return $this->mostrarMensaje('Enlace expirado', 'El enlace para restablecer tu contraseña ha expirado. Por favor, solicita un nuevo restablecimiento de contraseña.');
            }
        }

        return $this->mostrarMensaje('Ocurrió un error', 'Por favor, intenta nuevamente más tarde.');
    }

    public function resetPasswordForm(){
        $rules = [
            'password' => 'required|min_length[8]|max_length[50]',
            'repassword' => 'matches[password]'
        ];

        $messages = [
            'password' => [
                'required' => 'La contraseña es obligatoria.',
                'min_length' => 'La contraseña debe tener al menos {param} caracteres.',
                'max_length' => 'La contraseña no puede superar los {param} caracteres.'
            ],

            'repassword' => [
                'required' => 'Debes confirmar la contraseña.',
                'matches' => 'Las contraseñas no coinciden.'
            ]

        ];
        
       if(!$this->validate($rules, $messages)){
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
       }

       $usuarioModel = new UsuarioModel();
       $post = $this->request->getPost(['token', 'password']);

       $usuario = $usuarioModel->buscarPorTokenReset($post['token']);

       if($usuario){
            $usuarioModel->actualizarPassword($usuario['idUsuario'], $post['password']);

            return $this->mostrarMensaje('Contraseña restablecida', 'Tu contraseña ha sido restablecida correctamente. Puedes iniciar sesión con tu nueva contraseña.');
       }

       return $this->mostrarMensaje('Ocurrió un error', 'Por favor, intenta nuevamente más tarde.');

    }

    private function mostrarMensaje($titulo, $mensaje)
    {
        $data = [
            'title' => $titulo,
            'message' => $mensaje
        ];

        return view('mjeRegistro', $data);
    }
}
