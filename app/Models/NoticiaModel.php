<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiaModel extends Model
{
    protected $table            = 'noticias';
    protected $primaryKey       = 'idNoticia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['tituloNoticia', 'descripcionNoticia', 'fechaCreacionNoticia', 'fechaPublicacionNoticia', 'urlImagenNoticia', 'estadoNoticia', 'idAutorNoticia'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fechaCreacionNoticia';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    public function actualizarAExpiradas() //noticias mayor a 60 dias pasan a expiradas
    {
        $fechaLimite = date('Y-m-d H:i:s', strtotime('-60 days'));

        return $this->builder()
            ->where('fechaCreacionNoticia <', $fechaLimite)
            ->where('estadoNoticia !=', 6)
            ->set(['estadoNoticia' => 6])
            ->update();
    }

    public function obtenerNoticias($estado = null)
    {
        $this->actualizarAExpiradas();

        $rol = session('idRolUsuario');
        $idUsuario = session('idUsuario');

        $this->select('noticias.*, usuarios.nombreUsuario as nombreAutor');
        $this->join('usuarios', 'usuarios.idUsuario = noticias.idAutorNoticia', 'left');

        if ($rol == 1) {
            $this->where('idAutorNoticia', $idUsuario);
        } elseif ($rol == 2 || $rol == 3) {
            $this->groupStart()
                ->where('idAutorNoticia', $idUsuario)
                ->orWhere('estadoNoticia', 2)
                ->groupEnd();
        }

        if ($estado !== null && $estado > 0) {
            if ($estado == 5) {
                $this->whereIn('estadoNoticia', [5, 6]);
            } else {
                $this->where('estadoNoticia', $estado);
            }
        }

        return $this->orderBy('fechaCreacionNoticia', 'DESC')->findAll();
    }

    public function obtenerNoticiasPublicadas()
    {
        $this->actualizarAExpiradas();

        return $this->select('noticias.*, usuarios.nombreUsuario as nombreAutor')
            ->join('usuarios', 'usuarios.idUsuario = noticias.idAutorNoticia', 'left')
            ->where('estadoNoticia', 4)
            ->orderBy('fechaPublicacionNoticia', 'DESC')
            ->findAll();
    }

    public function buscarNoticiaPorId($id)
    {
        $this->actualizarAExpiradas();

        if ($this->where('idNoticia', $id)->countAllResults() > 0) {
            return $this->where('idNoticia', $id)->first();
        }

        return null;
    }

    public function obtenerAutorPorNoticia($idNoticia)
    {
        return $this->select('usuarios.nombreUsuario')
            ->join('usuarios', 'usuarios.idUsuario = noticias.idAutorNoticia')
            ->where('noticias.idNoticia', $idNoticia)
            ->first();
    }

    public function verificarTitulo($id, $titulo)
    {
        $this->actualizarAExpiradas();

        $query = $this->where('tituloNoticia', $titulo);
        if ($id) {
            $query->where('idNoticia !=', $id);
        }
        return $query->countAllResults() > 0;
    }

    public function cargarNoticias($offset)
    {
        return $this->where('estadoNoticia', 4)
            ->orderBy('fechaPublicacionNoticia', 'DESC')
            ->findAll(6, (int)$offset);
    }

    public function obtenerNoticiasRelacionadas($id)
    {
        return  $this->where('idNoticia !=', $id)
            ->where('estadoNoticia', 4)
            ->limit(3)
            ->orderBy('fechaPublicacionNoticia', 'DESC')
            ->findAll();
    }
}
