<?php

namespace App\Controllers;

use App\Models\NoticiaModel;
use App\Models\HistorialModel;
use App\Models\UsuarioModel;

class Panel extends BaseController
{
    public function panel()
    {
        $noticiaModel = new NoticiaModel();
        $estado = $this->request->getGet('estado');

        $data = [
            'noticias'      => $noticiaModel->obtenerNoticias($estado),
            'estado_actual' => $estado
        ];

        return view('panel', $data);
    }
    public function crearNoticia()
    {
        return view('crearNoticia');
    }
    public function guardarNoticiaForm()
    {
        $rules = [
            'titulo' => 'required|max_length[100]',
            'contenido' => 'required',
            'fotoPortada' => 'permit_empty|mime_in[fotoPortada,image/jpg,image/jpeg,image/png]|is_image[fotoPortada]|max_size[fotoPortada,2048]'
        ];
        $mensajes = [
            'titulo' => [
                'required' => 'El título es obligatorio.',
                'max_length' => 'El título no puede superar los {param} caracteres.'
            ],
            'contenido' => [
                'required' => 'El contenido es obligatorio.'
            ],
            'fotoPortada' => [
                'mime_in' => 'La foto de portada debe ser un archivo JPG o PNG.',
                'is_image' => 'El archivo debe ser una imagen válida.',
                'max_size' => 'La foto de portada no puede superar los 2MB.'
            ]
        ];

        if (!$this->validate($rules, $mensajes)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $accion = $this->request->getPost('accion');
        $titulo = $this->request->getPost('titulo');
        $contenido = $this->request->getPost('contenido');
        $id = $this->request->getPost('idNoticia');

        $fotoNueva = $this->request->getFile('fotoPortada');
        $fotoVieja = $this->request->getPost('urlImagenActual');
        $imagenBorrada = $this->request->getPost('imagenBorrada');

        $nombreArchivo = $fotoVieja;

        if ($imagenBorrada == "1") {
            if (!empty($fotoVieja) && file_exists(ROOTPATH . 'public/assets/uploads/noticias/' . $fotoVieja)) {
                unlink(ROOTPATH . 'public/assets/uploads/noticias/' . $fotoVieja);
            }
            $nombreArchivo = null;
        }

        if ($fotoNueva && $fotoNueva->isValid() && !$fotoNueva->hasMoved()) {
            $ext = $fotoNueva->getExtension();
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $nombreArchivo = $fotoNueva->getRandomName();
                $fotoNueva->move(ROOTPATH . 'public/assets/uploads/noticias', $nombreArchivo);
            }
        }


        $estado = 1;
        if ($accion === 'publicar') {
            $estado = 4;
        }
        if ($accion === 'validacion') {
            $estado = 2;
        }

        $noticiaModel = new NoticiaModel();

        $data = [
            'tituloNoticia' => $titulo,
            'descripcionNoticia' => $contenido,
            'fechaCreacionNoticia' => $id ? $noticiaModel->buscarNoticiaPorId($id)['fechaCreacionNoticia'] : date('Y-m-d H:i:s'),
            'fechaPublicacionNoticia' => ($estado == 4) ? date('Y-m-d H:i:s') : null,
            'urlImagenNoticia' => $nombreArchivo ? $nombreArchivo : null,
            'estadoNoticia' => $estado,
            'idAutorNoticia' => session('idUsuario')
        ];

        $historialModel = new HistorialModel();

        if ($estado == 4) {
            if ($noticiaModel->verificarTitulo($id, $titulo)) {
                return redirect()->back()->withInput()->with('error', 'Ya existe una noticia con ese título.');
            }
            if (strlen($titulo) < 10) {
                return redirect()->back()->withInput()->with('error', 'El título debe tener al menos 10 caracteres.');
            }
            if (strlen($titulo) > 100) {
                return redirect()->back()->withInput()->with('error', 'El título no debe exceder los 100 caracteres.');
            }
            if (strlen($contenido) < 50) {
                return redirect()->back()->withInput()->with('error', 'El contenido debe tener al menos 50 caracteres.');
            }
        }

        if ($id) {
            if ($noticiaModel->update($id, $data)) {
                $historialModel->registrarHistorial($id, $estado);
                return redirect()->to('panel')->with('mensaje_exito', '¡La noticia se ha actualizado con éxito!');
            } else {
                return redirect()->back()->with('error', 'Error al actualizar la noticia.');
            }
        } else {
            if ($noticiaModel->insert($data)) {
                $historialModel->registrarHistorial($noticiaModel->getInsertID(), 7);
                
                usleep(1000000);
                $historialModel->registrarHistorial($noticiaModel->getInsertID(), $estado);

                return redirect()->to('panel')->with('mensaje_exito', '¡La noticia se ha creado con éxito!');
            } else {
                return redirect()->back()->with('error', 'Error al guardar la noticia.');
            }
        }
    }
    public function editar($id)
    {
        $noticiaModel = new NoticiaModel();
        $historialModel = new HistorialModel();
        $usuarioModel = new UsuarioModel();

        $data['noticia'] = $noticiaModel->buscarNoticiaPorId($id);

        if (!$data['noticia']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $autor = $usuarioModel->find($data['noticia']['idAutorNoticia']);
        $data['noticia']['nombreAutor'] = $autor['nombreUsuario'] ?? 'Redacción';

        $data['historial'] = $historialModel->obtenerHistorialConNombres($id);

        $data['noticia']['ultimoComentario'] = !empty($data['historial']) ? $data['historial'][0]['comentarioHistorial'] : '';

        return view('crearNoticia', $data);
    }
    public function anular($id)
    {
        $noticiaModel = new NoticiaModel();
        $historialModel = new HistorialModel();

        $noticiaModel->update($id, ['estadoNoticia' => 6]);
        $historialModel->registrarHistorial($id, 6);

        return redirect()->to('/panel')->with('mensaje_exito', 'La noticia ha sido anulada correctamente.');
    }
    public function ver($id)
    {
        $noticiaModel = new NoticiaModel();
        $data['noticia'] = $noticiaModel->buscarNoticiaPorId($id);
        $data['noticia']['nombreAutor'] = $noticiaModel->obtenerAutorPorNoticia($data['noticia']['idNoticia'])['nombreUsuario'];
        $historialModel = new HistorialModel();
        $data['historial'] = $historialModel->obtenerHistorialConNombres($id);

        if (!$data['noticia']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('verNoticia', $data);
    }
    public function corregir($id)
    {
        $comentario = $this->request->getGet('comentario');
        $noticiaModel = new NoticiaModel();
        $noticiaModel->update($id, [
            'estadoNoticia' => 3
        ]);
        $historialModel = new HistorialModel();
        $historialModel->registrarHistorial($id, 3, $comentario);
        return redirect()->to('/panel')->with('mensaje_exito', 'Noticia enviada a corrección.');
    }
    public function publicar($id)
    {
        $noticiaModel = new NoticiaModel();
        $historialModel = new HistorialModel();

        $noticiaModel->update($id, ['estadoNoticia' => 4, 'fechaPublicacionNoticia' => date('Y-m-d H:i:s')]);
        $historialModel->registrarHistorial($id, 4);

        return redirect()->to('/panel')->with('mensaje_exito', '¡Noticia publicada con éxito!');
    }
}
