<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalActiviteController extends Controller
{
    public function enregistrer(Request $request)
    {
        $validated = $request->validate([
            'type_action' => ['required', 'string', 'in:changement_onglet,copier_coller'],
        ]);

        JournalActivite::create([
            'user_id' => Auth::id(),
            'type_action' => $validated['type_action'],
        ]);

        return response()->json(['ok' => true]);
    }

    // Vue admin : consultation des journaux anti-fraude (RG08).
    public function index(Request $request)
    {
        $journaux = JournalActivite::with('user')
            ->when($request->user_id, fn ($q, $id) => $q->where('user_id', $id))
            ->latest()->paginate(30);

        return view('admin.journal.index', compact('journaux'));
    }
}
