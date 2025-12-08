<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Mostrar formulario de registro
    public function showRegister()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
        ]);

        // Asignar rol de customer
        $user->assignRole('customer');

        // Login automático
        Auth::login($user);

        return redirect()->route('home')->with('success', '¡Registro exitoso! Bienvenido a nuestra tienda.');
    }

    // Mostrar formulario de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redireccionar según rol
            if ($user->role === 'super_admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'tenant_admin') {
                return redirect()->intended('/tenant/dashboard');
            } else {
                return redirect()->intended('/');
            }
        }

        throw ValidationException::withMessages([
            'email' => __('Las credenciales no coinciden con nuestros registros.'),
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesión cerrada correctamente.');
    }
}
