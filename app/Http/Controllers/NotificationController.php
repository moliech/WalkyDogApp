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

    /**
     * Obtiene las notificaciones no leídas en formato JSON para el polling en tiempo real.
     */
    public function getUnread()
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }

        $notifications = auth()->user()->unreadNotifications;

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'mensaje' => $n->data['mensaje'],
                    'tipo' => $n->data['tipo'],
                    'url' => route('notificaciones.ir', $n->id),
                    'time' => $n->created_at->diffForHumans()
                ];
            })
        ]);
    }
}
