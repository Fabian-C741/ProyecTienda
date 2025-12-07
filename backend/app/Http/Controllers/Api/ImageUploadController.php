<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadController extends Controller
{
    /**
     * Upload single image
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'folder' => 'nullable|string|in:products,categories,users,banners',
            'resize' => 'nullable|boolean',
            'width' => 'nullable|integer|min:100|max:2000',
            'height' => 'nullable|integer|min:100|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $folder = $request->folder ?? 'products';
            $image = $request->file('image');
            
            // Generar nombre único
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $folder . '/' . date('Y/m');

            // Optimizar imagen si se solicita
            if ($request->resize) {
                $width = $request->width ?? 800;
                $height = $request->height ?? 800;
                
                $img = Image::make($image);
                
                // Redimensionar manteniendo proporción
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // Optimizar calidad
                $img->encode($image->getClientOriginalExtension(), 85);
                
                // Guardar
                Storage::disk('public')->put($path . '/' . $filename, $img->stream());
            } else {
                // Guardar original
                $image->storeAs($path, $filename, 'public');
            }

            $url = Storage::url($path . '/' . $filename);

            return response()->json([
                'message' => 'Imagen subida exitosamente',
                'url' => $url,
                'path' => $path . '/' . $filename,
                'filename' => $filename,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al subir la imagen',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload multiple images
     */
    public function uploadMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'folder' => 'nullable|string|in:products,categories,users,banners',
            'resize' => 'nullable|boolean',
            'width' => 'nullable|integer|min:100|max:2000',
            'height' => 'nullable|integer|min:100|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $uploadedImages = [];
        $errors = [];

        foreach ($request->file('images') as $index => $image) {
            try {
                $folder = $request->folder ?? 'products';
                $filename = time() . '_' . $index . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $folder . '/' . date('Y/m');

                if ($request->resize) {
                    $width = $request->width ?? 800;
                    $height = $request->height ?? 800;
                    
                    $img = Image::make($image);
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->encode($image->getClientOriginalExtension(), 85);
                    
                    Storage::disk('public')->put($path . '/' . $filename, $img->stream());
                } else {
                    $image->storeAs($path, $filename, 'public');
                }

                $uploadedImages[] = [
                    'url' => Storage::url($path . '/' . $filename),
                    'path' => $path . '/' . $filename,
                    'filename' => $filename,
                ];

            } catch (\Exception $e) {
                $errors[] = "Error en imagen {$index}: " . $e->getMessage();
            }
        }

        return response()->json([
            'message' => count($uploadedImages) . ' imágenes subidas exitosamente',
            'images' => $uploadedImages,
            'errors' => $errors,
        ], 201);
    }

    /**
     * Delete image
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            if (Storage::disk('public')->exists($request->path)) {
                Storage::disk('public')->delete($request->path);
                
                return response()->json([
                    'message' => 'Imagen eliminada exitosamente',
                ]);
            }

            return response()->json([
                'message' => 'Imagen no encontrada',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la imagen',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
