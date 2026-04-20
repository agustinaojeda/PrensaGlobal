<?php

namespace App\Controllers;

use App\Models\NoticiaModel;
use App\Helpers\FechaHelper;

class Home extends BaseController
{

    public function index(): string
    {
        $noticiaModel = new NoticiaModel();
        $todasLasNoticias = $noticiaModel->obtenerNoticiasPublicadas();

        $data = ['destacada' => null, 'listaRapida' => [], 'restoNoticias' => []];

        if (!empty($todasLasNoticias)) {
            $data['totalNoticias'] = count($todasLasNoticias);

            $data['destacada'] = array_shift($todasLasNoticias);

            $data['listaRapida'] = array_splice($todasLasNoticias, 0, 2);

            $data['restoNoticias'] = $todasLasNoticias;
        }
        return view('index', $data);
    }
    public function cargarMas($offset = 0)
    {
        $noticiaModel = new NoticiaModel();
        $noticias = $noticiaModel->cargarNoticias($offset);

        foreach ($noticias as &$n) {
        $n['fechaPublicacionNoticia'] = FechaHelper::fechaString($n['fechaPublicacionNoticia']);
    }

        return $this->response->setJSON($noticias);
    }
    public function ver($id)
    {
        $noticiaModel = new NoticiaModel();
        $noticia = $noticiaModel->buscarNoticiaPorId($id);

        if (!$noticia || $noticia['estadoNoticia'] != 4) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("La noticia con ID $id no existe o no está publicada.");
        }

        $autor = $noticiaModel->obtenerAutorPorNoticia($id);

        $data = [
            'noticia' => $noticia,
            'autor' => $autor ? $autor['nombreUsuario'] : 'Desconocido'
        ];
        $data['relacionadas'] = $noticiaModel->obtenerNoticiasRelacionadas($id);

        return view('noticiaDetalles', $data);
    }
}
