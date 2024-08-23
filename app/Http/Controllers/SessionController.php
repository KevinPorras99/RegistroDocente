<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\resetPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class SessionController extends Controller {
    
    
    public function create() {
        return view('auth.login'); // Vista sencilla para el login
    }

    public function store(Request $request) {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials) == false) {
            return back()->withErrors([
                'message' => 'El correo electrónico o la contraseña son incorrectos, inténtalo de nuevo.',
            ]);
        }
    
        // Redirigir a la página principal después de un login exitoso
        return redirect()->route('home');
    }

    // Nuevo método para cargar la vista de home con el usuario autenticado
    public function home() {
        $user = Auth::user(); // Obtén el usuario autenticado
        return view('home', ['user' => $user]); // Pasa el usuario a la vista
    }

    public function ForgotPasswordView() {
        $token = ""; 
    
        return view('auth.forgotPassword', compact('token'));
    }

    public function forgotPassword(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => "El campo correo electrónico es obligatorio.",
            'email.email' => "Correo electrónico no válido.",
            'email.exists' => "Este correo no corresponde a ningún usuario registrado.",
        ]);
    
        $token = Str::random(64);
    
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );
    
        $user = User::where('email', $request->email)->first();
    
        Mail::to($user)->send(new resetPasswordMail($token));
    
        return back()->with('status', 'Revisa tu correo electrónico. 
        Se han enviado instrucciones para restablecer tu contraseña.');
    }    

    public function showResetForm($token) {
        return view('auth.changePassword', compact('token'));
    }

    public function reset(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => [
                'required',
                'min:8',
                'max:32',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@,$!%*?&])[A-Za-z\d@,$!%*?&]+$/',
            ],
            'password_confirmation' => 'required'
        ]);
    
        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])->first();
    
        if (!$updatePassword) {
            return back()->withErrors(['email' => 'El enlace de cambio de contraseña es inválido o ya ha sido utilizado.']);
        }
    
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
    
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
    
        return redirect()->to(route('login.index'))->with('success', 'Se ha cambiado la contraseña de manera correcta');
    } 
    
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta el tamaño máximo de archivo según sea necesario
        ]);

        $user = Auth::user();

        // Eliminar la imagen anterior si existe
        if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
            Storage::delete('public/' . $user->profile_image);
        }

        // Almacenar la nueva imagen
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');

        // Actualizar la imagen de perfil del usuario
        $user->profile_image = $imagePath;
        $user->save();

        return redirect()->route('home')->with('success', 'Foto de perfil actualizada correctamente.');
    }

    public function deleteProfileImage()
    {
        $user = Auth::user();

        // Eliminar la imagen de perfil si existe
        if ($user->profile_image) {
            Storage::delete('public/' . $user->profile_image);
            $user->profile_image = null;
            $user->save();
        }

        return redirect()->route('home')->with('success', 'Foto de perfil eliminada correctamente.');
    }

    
    
    public function destroy() {
        Auth::logout();
        return redirect()->to('/');
    }
}