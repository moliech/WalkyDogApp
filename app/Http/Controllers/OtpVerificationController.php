<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('otp_user_id'));
        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.otp-verify');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->verifyOtp($request->code)) {
            return back()->withErrors(['code' => 'El código ingresado no es válido o ha expirado.']);
        }

        // Limpiar código usado para evitar ataques de repetición
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Autenticar definitivamente al usuario
        $request->session()->forget('otp_user_id');
        Auth::loginUsingId($user->id, $request->session()->get('otp_remember', false));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if ($user) {
            $otp = $user->generateOtp();
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Nuevo código de verificación WalkyDog');
            });
        }

        return back()->with('status', 'Se ha enviado un nuevo código a tu correo.');
    }
}