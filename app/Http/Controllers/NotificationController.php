<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Marca una notificación como leída y redirige al usuario a la URL correspondiente.
     */
    public function readAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();
        
        $url = $notification->data['url'] ?? route('dashboard');
        
        return redirect($url);
    }

    /**
     * Marca todas las notificaciones no leídas del usuario actual como leídas.
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }
}
