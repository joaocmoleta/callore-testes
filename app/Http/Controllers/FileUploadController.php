<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('dashboard.media.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:900',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()]);
        }

        $width = $request->width ? $request->width : 800;
        $height = $request->height ? $request->height : 800;

        $filename = Carbon::now()->format('ymdHis') . "-" . Str::slug($request->name_img) . "." . $request->file->extension();
        $filenameThumb = Carbon::now()->format('ymdHis') . '-' . Str::slug($request->name_img) . '-' . $width . 'x' . $height . '.' . $request->file->extension();
        $target_path = public_path('uploads/' . $filenameThumb);

        if($request->file('file')->getClientMimeType() == 'image/gif') {
            $filenameThumb = $filename;
        } else {
            $this->resize($request->file('file')->path(), $target_path, $width, $height, $request->cut_type);
        }

        $request->file->move(public_path('file'), $filename);

        return response()->json([
            'status' => 'Arquivo armazenado com sucesso.',
            'filename' => $filenameThumb,
            'original_filename' => $filename,
            'public_path' => '/file',
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
}
