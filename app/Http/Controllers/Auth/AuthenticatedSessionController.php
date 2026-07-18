<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();
        $otp= $user->generateOtp(); 
         
        Mail::send('emails.otp', ['otp'=>$otp, 'user'=>$user], function ($message) use ($user){
            $message -> to ($user->email)
                -> subject('Código de verificación para WalkyDog');
        });

        Auth::logout(); // Cerrar sesión del usuario después de enviar el OTP

        $request->session()->put('otp_user_id', $user->id); // Guardar el ID del usuario en la sesión para la verificación del OTP
        $request->session()->put('otp_remember',$request->boolean('remember')); // Guardar la opción "remember me" en la sesión

        return redirect()->route('otp.verify'); // Redirigir al usuario a la página de verificación del OTP

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
