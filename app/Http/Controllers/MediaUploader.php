<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;


class MediaUploader extends Controller
{
    public function index()
    {
        // File and new size
        $filename = public_path('uploads/vibora-serpente-venenosa-2.jpg');
        $dest = public_path('uploads/callore-aquecedores-de-toalhas-20230421125738-3.jpg');
        $percent = 0.5;

        // Content type
        header('Content-Type: image/jpeg');

        // Get new sizes
        list($width, $height) = getimagesize($filename);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;

        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($filename);

        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Output
        imagejpeg($thumb);
        // return view('dashboard.media-upload.index');
    }

    public function byAjax()
    {
        return view('dashboard.media-upload.ajax');
    }

    public function upByAjax(Request $request)
    {
        $mimes = Str::of(env('FILES_ACCEPT'))->replace('.', '');

        $validator = Validator::make(
            $request->all(),
            [
                'file_name' => 'nullable|string',
                'file' => 'required|max:900|mimes:' . $mimes,
            ],
            [
                'file.max' => 'Selecione um arquivo até 900k.',
                'file.required' => 'Selecione um arquivo.',
                'file.mimes' => 'Selecione um arquivo nos formatos suportados.',
                'file' => 'Erro no arquivo.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([$validator->errors()]);
        }

        // $file = $request->file('file');

        $width = $request->crop_width ?? 800;
        $height = $request->crop_height ?? 800;

        $post_filename = $request->file_name ? Str::slug($request->file_name, '-') : 'callore-aquecedores';

        $pathh = '/uploads/';

        $filename = $post_filename . '.' . $request->file->extension();
        $filenameThumb = $post_filename . '-' . $width . 'x' . $height . '.' . $request->file->extension();

        $pp = $pathh . $filename;
        $ppt = $pathh . $filenameThumb;

        $i = 1;
        while (file_exists(public_path($pp))) {
            $i++;
            $pp = $pathh . $post_filename . '-' . $i . '.' . $request->file->extension();
        }
        
        $j = 1;
        while(file_exists(public_path($ppt))) {
            $j++;
            $ppt = $pathh . $post_filename . '-' . $width . 'x' . $height . '-' . $j . '.' . $request->file->extension();
        }
        
        $this->resize($request->file('file')->path(), public_path($ppt), $width, $height, $request->crop_type);

        $request->file->move(public_path($pathh), public_path($pp));

        return response()->json([
            [
                'file' => [
                    'Arquivo armazenado com sucesso.',
                    'path' => $ppt,
                    'original_filename' => $pp,
                ]
            ]
        ]);
    }

    private function resize($path, $targetPath, $width, $height, $cut_type)
    {
        $img = Image::make($path);

        if ($cut_type) {
            // Resized image
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            // Canvas image
            $canvas = Image::canvas($width, $height);
            $canvas->insert($img, 'center');
            $canvas->save($targetPath);
        } else {
            $img->fit($width, $height);
            $img->save($targetPath);
        }
    }

    public function upload(Request $request)
    {
        $mimes = Str::of(env('FILES_ACCEPT'))->replace('.', '');
        $request->validate(
            [
                'file_name' => 'string',
                'file' => 'required|mimes:' . $mimes,
            ],
            [
                'file_name' => 'Coloque um nome para o arquivo.',
                'file' => 'Selecione um arquivo válido.',
            ]
        );

        $file = $request->file('file');

        // Move Uploaded File
        $slug_name = Str::slug($request->file_name);
        $ext = $file->getClientOriginalExtension();

        $pp = public_path('uploads/' . $slug_name . '.' . $ext);

        $i = 0;
        while (file_exists($pp)) {
            $i++;
            $pp = public_path('uploads/' . $slug_name . '-' . $i . '.' . $ext);
        }

        $path = $file->move('uploads', $slug_name . '-' . $i . '.' . $ext);

        return redirect()->back()->with('success', 'Arquivo adicionado: ' . $path);
    }

    public function uploadPdf(Request $request)
    {
        $request->validate(
            [
                'file_name' => 'string',
                'file' => 'required|mimes:pdf',
            ],
            [
                'file_name' => 'Coloque um nome para o arquivo.',
                'file' => 'Selecione um arquivo em PDF.',
            ]
        );

        $file = $request->file('file');

        // Move Uploaded File
        $slug_name = Str::slug($request->file_name);
        $ext = $file->getClientOriginalExtension();

        $pp = public_path('uploads/' . $slug_name . '.' . $ext);

        $i = 0;
        while (file_exists($pp)) {
            $i++;
            $pp = public_path('uploads/' . $slug_name . '-' . $i . '.' . $ext);
        }

        $path = $file->move('uploads', $slug_name . '-' . $i . '.' . $ext);

        return redirect()->back()->with('success', 'Arquivo adicionado: ' . $path);
    }

    public function uploadImage(Request $request)
    {
        $mimes = Str::of(env('FILES_ACCEPT_IMG'))->replace('.', '');
        $request->validate(
            [
                'file_name' => 'string',
                'file' => 'required|mimes:' . $mimes,
            ],
            [
                'file_name' => 'Coloque um nome para a imagem.',
                'file' => 'Selecione uma imagem válida.',
            ]
        );

        $file = $request->file('file');

        // Move Uploaded File
        $slug_name = Str::slug($request->file_name);
        $ext = $file->getClientOriginalExtension();

        $pp = public_path('uploads/' . $slug_name . '.' . $ext);

        $i = 0;
        while (file_exists($pp)) {
            $i++;
            $pp = public_path('uploads/' . $slug_name . '-' . $i . '.' . $ext);
        }

        $path = $file->move('uploads', $slug_name . '-' . $i . '.' . $ext);

        return redirect()->back()->with('success', 'Imagem adicionada: ' . $path);
    }

    public function cropped(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'path' => 'required',
                'file_name' => 'required',
                'ext' => 'required',
                'width' => 'required',
                'height' => 'required',
                'rotate' => 'required',
                'pos_x' => 'required',
                'pos_y' => 'required',
                'sca_x' => 'required',
                'sca_y' => 'required',
            ],
            []
        );

        if ($validator->fails()) {
            return response()->json([$validator->errors()]);
        }

        $source_path = public_path($request->path);
        $path_parts = pathinfo($source_path);

        $new_w = $request->width;
        $new_h = $request->height;
        $new_x = $request->pos_x;
        $new_y = $request->pos_y;

        // Create image instances
        switch ($path_parts['extension']) {
            case 'webp':
                $src = imagecreatefromwebp($source_path);
                break;
            case 'jpg':
                $src = imagecreatefromjpeg($source_path);
                break;
            case 'jpeg':
                $src = imagecreatefromjpeg($source_path);
                break;
            case 'png':
                $src = imagecreatefrompng($source_path);
                break;
            case 'gif':
                $src = imagecreatefromgif($source_path);
                break;
            case 'bmp':
                $src = imagecreatefrombmp($source_path);
                break;
            case 'avif':
                $src = imagecreatefromavif($source_path);
                break;
            case 'wbmp':
                $src = imagecreatefromwbmp($source_path);
                break;
            default:
                return response()->json([
                    [
                        'file' => [
                            'Formato não suportado.',
                        ]
                    ]
                ]);
        }

        $largura_original = imagesx($src);
        $altura_original = imagesy($src);

        // imagesx($src), imagesy($src)
        $dest = imagecreatetruecolor($new_w, $new_h);

        // set background to white
        $white = imagecolorallocate($dest, 255, 255, 255);
        imagefill($dest, 0, 0, $white);

        // Image copy from source to destination
        imagecopy($dest, $src, 0, 0, $new_x, $new_y, $new_w, $new_h);

        // Topo preechimento quando for negativo deslocamento de x e y
        for ($x = 0; $x <= $new_w; $x++) {
            for ($y = 0; $y <= $new_y * -1; $y++) {
                imagesetpixel($dest, $x, $y, $white);
            }
        }

        // Esquerda preechimento quando for negativo deslocamento de x e y
        for ($x = 0; $x <= $new_x * -1; $x++) {
            for ($y = 0; $y <= $new_h; $y++) {
                imagesetpixel($dest, $x, $y, $white);
            }
        }

        // Direita preechimento quando for negativo deslocamento de x e y
        for ($x = ($new_x * -1 + $largura_original); $x <= $new_w; $x++) {
            for ($y = 0; $y <= $new_h; $y++) {
                imagesetpixel($dest, $x, $y, $white);
            }
        }

        // Rodapé preechimento quando for negativo deslocamento de x e y
        for ($x = 0; $x <= $new_w; $x++) {
            for ($y = $new_y * -1 + $altura_original; $y <= $new_h; $y++) {
                imagesetpixel($dest, $x, $y, $white);
            }
        }

        // Output and free from memory
        // header('Content-Type: image/gif');
        // imagejpeg($dest, public_path($request->path . $request->file_name . '-edited.' . $request->ext));

        $dest_path = $path_parts['dirname'] . '/' . $path_parts['filename'] . '-edited.' . $path_parts['extension'];

        switch ($path_parts['extension']) {
            case 'webp':
                imagewebp($dest, $dest_path);
                break;
            case 'jpg':
                imagejpeg($dest, $dest_path);
                break;
            case 'jpeg':
                imagejpeg($dest, $dest_path);
                break;
            case 'png':
                imagepng($dest, $dest_path);
                break;
            case 'gif':
                imagegif($dest, $dest_path);
                break;
            case 'bmp':
                imagebmp($dest, $dest_path);
                break;
            case 'avif':
                imageavif($dest, $dest_path);
                break;
            case 'wbmp':
                imagewbmp($dest, $dest_path);
                break;
            default:
                return response()->json([
                    [
                        'file' => [
                            'Formato não suportado.',
                        ]
                    ]
                ]);
        }

        imagedestroy($dest);
        imagedestroy($src);

        return response()->json([
            [
                'file' => [
                    'Adicionada com sucesso.',
                    'path' => 'uploads/' . $path_parts['filename'] . '-edited.' . $path_parts['extension'],
                ]
            ]
        ]);
    }
}
