<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'idUsuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombreUsuario', 'contraUsuario', 'idRolUsuario', 'emailUsuario', 'estaActivo', 'activacionToken', 'resetToken', 'resetTokenExpira'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    //protected $dateFormat    = 'datetime';
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    public function agregarUsuario($data, $token)
    {

        return $this->insert([
            'emailUsuario' => $data['email'],
            'contraUsuario' => password_hash($data['password'], PASSWORD_DEFAULT),
            'nombreUsuario' => $data['name'],
            'idRolUsuario' => $data['rol'],
            'estaActivo' => 0,
            'activacionToken' => $token
        ]);
    }

    public function validarUsuario($email, $password)
    {
        $usuario = $this->where(['emailUsuario' => $email, 'estaActivo' => 1])->first();
        if ($usuario && password_verify($password, $usuario['contraUsuario'])) {
            return $usuario;
        }

        return null;
    }

    public function buscarPorEmail($email)
    {
        return $this->where([
            'emailUsuario' => $email,
            'estaActivo'   => 1
        ])->first();
    }

    public function buscarPorTokenActivacion($token)
    {
        return $this->where([
            'activacionToken' => $token,
            'estaActivo' => 0
        ])->first();
    }

    public function activarCuenta($usuario)
    {
        return $this->update($usuario['idUsuario'], [
            'estaActivo' => 1,
            'activacionToken' => null
        ]);
    }
    public function tokenReset($token, $usuario) //activa el token de reset de contra
    {
        $expiraEn = new \DateTime();
        $expiraEn->modify('+1 hour');

        return $this->update($usuario['idUsuario'], [
            'resetToken' => $token,
            'resetTokenExpira' => $expiraEn->format('Y-m-d H:i:s')
        ]);
    }
    public function buscarPorTokenReset($token)
    {
        return $this->where([
            'resetToken' => $token,
            'estaActivo' => 1
        ])->first();
    }
    public function actualizarPassword($id, $nuevaPassword)
    {
        return $this->update($id, [
            'contraUsuario'    => password_hash($nuevaPassword, PASSWORD_DEFAULT),
            'resetToken'       => null,
            'resetTokenExpira' => null
        ]);
    }
}
