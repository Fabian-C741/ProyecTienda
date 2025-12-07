<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Subir una imagen
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'type' => 'required|in:product,category,avatar,general',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $file = $request->file('image');
            $type = $request->type;
            
            // Generar nombre único
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Guardar en storage/app/public/{type}
            $path = $file->storeAs("public/{$type}s", $filename);
            
            // URL pública
            $url = Storage::url($path);
            
            // URL completa con dominio
            $fullUrl = config('app.url') . $url;

            return response()->json([
                'message' => 'Imagen subida exitosamente',
                'url' => $fullUrl,
                'path' => $path,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al subir la imagen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir múltiples imágenes
     */
    public function uploadMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'required|in:product,category,avatar,general',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $uploadedImages = [];

        try {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs("public/{$request->type}s", $filename);
                $url = config('app.url') . Storage::url($path);

                $uploadedImages[] = [
                    'url' => $url,
                    'path' => $path,
                    'filename' => $filename,
                    'size' => $file->getSize(),
                ];
            }

            return response()->json([
                'message' => count($uploadedImages) . ' imágenes subidas exitosamente',
                'images' => $uploadedImages,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al subir las imágenes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una imagen
     */
    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            if (Storage::exists($request->path)) {
                Storage::delete($request->path);

                return response()->json([
                    'message' => 'Imagen eliminada exitosamente'
                ]);
            }

            return response()->json([
                'message' => 'Imagen no encontrada'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la imagen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
