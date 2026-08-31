<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EvaluationController extends Controller
{
    public function index(Cour $cour)
    {
        $evaluations = $cour->evaluations()->with('chapitre')->get();

        return view('evaluations.index', compact('cour', 'evaluations'));
    }

    public function create(Cour $cour)
    {
        $chapitres = $cour->chapitres;

        return view('evaluations.create', compact('cour', 'chapitres'));
    }

    public function store(Request $request, Cour $cour)
    {
        $validated = $this->valider($request);

        $cour->evaluations()->create($validated);

        return redirect()->route('cours.evaluations.index', $cour)->with('success', 'Évaluation créée.');
    }

    public function edit(Evaluation $evaluation)
    {
        $cour = $evaluation->cour;
        $chapitres = $cour->chapitres;

        return view('evaluations.edit', compact('cour', 'evaluation', 'chapitres'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $this->valider($request);

        $evaluation->update($validated);

        return redirect()->route('cours.evaluations.index', $evaluation->cour)->with('success', 'Évaluation mise à jour.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $cour = $evaluation->cour;
        $evaluation->delete();

        return redirect()->route('cours.evaluations.index', $cour)->with('success', 'Évaluation supprimée.');
    }

    private function valider(Request $request): array
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'type_evaluation' => ['required', 'in:test_chapitre,examen_final'],
            'chapitre_id' => ['nullable', 'exists:chapitres,id'],
            'seuil_reussite' => ['required', 'numeric', 'min:0', 'max:100'],
            'duree_max' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['type_evaluation'] === 'examen_final') {
            $validated['chapitre_id'] = null;
        } elseif (empty($validated['chapitre_id'])) {
            throw ValidationException::withMessages([
                'chapitre_id' => "Un test de chapitre doit être rattaché à un chapitre.",
            ]);
        }

        return $validated;
    }
}
