<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Liste des notifications de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Affiche le détail d'une notification (marquée comme lue),
     * avec un bouton pour accéder à sa destination (cours, chapitre, certificat...).
     */
    public function show(Request $request, string $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }
}