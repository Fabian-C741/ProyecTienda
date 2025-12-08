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
        try {
            // Verificar que sea super admin
            if (!Auth::check() || Auth::user()->role !== 'admin') {
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
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar contraseña: ' . $e->getMessage());
        }
    }

    public function updateEmail(Request $request)
    {
        try {
            // Verificar que sea super admin o admin
            if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) {
                return back()->with('error', 'No tienes permisos para cambiar el email');
            }

            $request->validate([
                'current_password' => 'required',
                'new_email' => 'required|email|unique:users,email,' . Auth::id(),
            ], [
                'current_password.required' => 'La contraseña es requerida para confirmar el cambio',
                'new_email.required' => 'El nuevo email es requerido',
                'new_email.email' => 'El formato del email no es válido',
                'new_email.unique' => 'Este email ya está en uso',
            ]);

            // Verificar contraseña actual
            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return back()->with('error', 'La contraseña es incorrecta');
            }

            // Actualizar email
            Auth::user()->update([
                'email' => $request->new_email
            ]);

            return back()->with('success', 'Email actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar email: ' . $e->getMessage());
        }
    }
}
