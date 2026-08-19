<?php

namespace App\Http\Controllers;

use App\Models\Chapitre;
use App\Models\Cour;
use Illuminate\Http\Request;

class ChapitreController extends Controller
{
    public function index(Cour $cour)
    {
        $chapitres = $cour->chapitres()->get();

        return view('chapitres.index', compact('cour', 'chapitres'));
    }

    public function create(Cour $cour)
    {
        return view('chapitres.create', compact('cour'));
    }

    public function store(Request $request, Cour $cour)
    {
        $validated = $this->valider($request);

        $dernierOrdre = $cour->chapitres()->max('ordre_affichage') ?? 0;
        $validated['ordre_affichage'] = $dernierOrdre + 1;

        $cour->chapitres()->create($validated);

        return redirect()->route('cours.chapitres.index', $cour)->with('success', 'Chapitre ajouté.');
    }

    public function edit(Chapitre $chapitre)
    {
        return view('chapitres.edit', ['cour' => $chapitre->cour, 'chapitre' => $chapitre]);
    }

    public function update(Request $request, Chapitre $chapitre)
    {
        $validated = $this->valider($request);

        $chapitre->update($validated);

        return redirect()->route('cours.chapitres.index', $chapitre->cour)->with('success', 'Chapitre mis à jour.');
    }

    public function destroy(Chapitre $chapitre)
    {
        $cour = $chapitre->cour;
        $chapitre->delete();

        return redirect()->route('cours.chapitres.index', $cour)->with('success', 'Chapitre supprimé.');
    }

    public function monter(Chapitre $chapitre)
    {
        $precedent = Chapitre::where('cour_id', $chapitre->cour_id)
            ->where('ordre_affichage', '<', $chapitre->ordre_affichage)
            ->orderByDesc('ordre_affichage')->first();

        if ($precedent) {
            $this->permuter($chapitre, $precedent);
        }

        return back();
    }

    public function descendre(Chapitre $chapitre)
    {
        $suivant = Chapitre::where('cour_id', $chapitre->cour_id)
            ->where('ordre_affichage', '>', $chapitre->ordre_affichage)
            ->orderBy('ordre_affichage')->first();

        if ($suivant) {
            $this->permuter($chapitre, $suivant);
        }

        return back();
    }

    private function permuter(Chapitre $a, Chapitre $b): void
    {
        $ordreA = $a->ordre_affichage;
        $ordreB = $b->ordre_affichage;
        $a->update(['ordre_affichage' => $ordreB]);
        $b->update(['ordre_affichage' => $ordreA]);
    }

    private function valider(Request $request): array
    {
        return $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'contenu' => ['nullable', 'string'],
        ]);
    }
}
