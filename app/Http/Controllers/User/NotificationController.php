<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Marquer une notification comme lue
     */
    public function read($id)
    {
        $user = Auth::user();
        $notification = $user()->find($id);

        if ($notification) {
            $notification->markAsRead(); // 🔹 marque la notification comme lue dans la base
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function readAll()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead(); // 🔹 toutes les notifications deviennent lues

        return response()->json(['status' => 'success']);
    }
}
