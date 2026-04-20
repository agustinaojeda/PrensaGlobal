<?php

namespace App\Models;

use CodeIgniter\Model;

class HistorialModel extends Model
{
    protected $table            = 'historial';
    protected $primaryKey       = 'idHistorial';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idAutorHistorial', 'idNoticiaHistorial', 'estadoInicialHistorial', 'estadoFinalHistorial', 'fechaHistorial', 'comentarioHistorial'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fechaHistorial';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    public function obtenerHistorialConNombres($idNoticia)
    {
        return $this->db->table('historial')
            ->select('historial.*, usuarios.nombreUsuario as nombreEditor')
            ->join('usuarios', 'usuarios.idUsuario = historial.idAutorHistorial')
            ->where('idNoticiaHistorial', $idNoticia)
            ->orderBy('fechaHistorial', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function registrarHistorial($idNoticia, $accion, $comentario = '')
    {
        $historialModel = new HistorialModel();
        
        $historialModel->insert([
            'idNoticiaHistorial' => $idNoticia,
            'idAutorHistorial'   => session('idUsuario'),
            'estadoFinalHistorial' => $accion,
            'fechaHistorial'     => date('Y-m-d H:i:s'),
            'comentarioHistorial' => $comentario
        ]);
    }
}
