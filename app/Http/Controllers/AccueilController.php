<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Cour;
use App\Models\Payement;
use App\Models\User;

class AccueilController extends Controller
{
    public function index()
    {
        /* if (auth()->check()) {
            return redirect()->route('dashboard');
        } */

        $stats = [
            'apprenants' => User::where('role', 'apprenant')->count(),
            'formations' => Cour::where('statut', 'publie')->count(),
            'certificats' => \App\Models\InscriptionCours::whereHas('payement')->count() > 0
                ? \Illuminate\Support\Facades\DB::table('certificats')->count()
                : 0,
        ];

        $categories = Categorie::withCount(['cours' => function ($q) {
            $q->where('statut', 'publie');
        }])->orderByDesc('cours_count')->take(6)->get();

        $coursVedette = Cour::where('statut', 'publie')->with('categorie')->latest()->take(3)->get();

        return view('accueil', compact('stats', 'categories', 'coursVedette'));
    }
}
