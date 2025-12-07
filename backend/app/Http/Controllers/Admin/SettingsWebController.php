<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsWebController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function clearCache()
    {
        Cache::flush();
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Caché limpiada exitosamente');
    }

    public function updatePassword(Request $request)
    {
        // Verificar que sea super admin
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'No tienes permisos para cambiar la contraseña');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es requerida',
            'new_password.required' => 'La nueva contraseña es requerida',
            'new_password.min' => 'La nueva contraseña debe tener al menos 6 caracteres',
            'new_password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta');
        }

        // Actualizar contraseña
        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente');
    }
}
