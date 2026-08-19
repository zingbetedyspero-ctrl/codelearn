<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApprenantController extends Controller
{
    public function index(Request $request)
    {
        $apprenants = User::where('role', 'apprenant')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('nom')
            ->paginate(15);

        return view('admin.apprenants.index', compact('apprenants'));
    }

    public function toggleStatut(User $apprenant)
    {
        if ($apprenant->role !== 'apprenant') {
            abort(403);
        }

        $apprenant->statut_compte = $apprenant->statut_compte === 'actif' ? 'inactif' : 'actif';
        $apprenant->save();

        return back()->with('success', "Le compte de {$apprenant->nomComplet()} est maintenant {$apprenant->statut_compte}.");
    }
}
