<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Liste des notifications (les plus récentes en haut)
        $notifications = $user->notifications()->latest()->paginate(15);

        // Marquer toutes comme lues dès l’ouverture (simple)
        $user->unreadNotifications->markAsRead();

        return view('membre.notifications.index', compact('notifications'));
    }
}
