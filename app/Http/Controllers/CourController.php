<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Cour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourController extends Controller
{
    public function index()
    {
        $cours = Cour::with(['categorie', 'createur'])->orderByDesc('created_at')->paginate(15);

        return view('cours.index', compact('cours'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();

        return view('cours.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->valider($request);

        if ($request->hasFile('image_couverture')) {
            $validated['image_couverture'] = $request->file('image_couverture')->store('cours', 'public');
        }

        $validated['user_id'] = Auth::id();

        Cour::create($validated);

        return redirect()->route('cours.index')->with('success', 'Cours créé.');
    }

    public function edit(Cour $cour)
    {
        $categories = Categorie::orderBy('nom')->get();

        return view('cours.edit', compact('cour', 'categories'));
    }

    public function update(Request $request, Cour $cour)
    {
        $validated = $this->valider($request);

        if ($request->hasFile('image_couverture')) {
            if ($cour->image_couverture) {
                Storage::disk('public')->delete($cour->image_couverture);
            }
            $validated['image_couverture'] = $request->file('image_couverture')->store('cours', 'public');
        }

        $cour->update($validated);

        return redirect()->route('cours.index')->with('success', 'Cours mis à jour.');
    }

    public function destroy(Cour $cour)
    {
        if ($cour->image_couverture) {
            Storage::disk('public')->delete($cour->image_couverture);
        }

        $cour->delete();

        return redirect()->route('cours.index')->with('success', 'Cours supprimé.');
    }

    private function valider(Request $request): array
    {
        return $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'niveau' => ['required', 'in:debutant,intermediaire,avance'],
            'prix' => ['required', 'numeric', 'min:0'],
            'statut' => ['required', 'in:publie,non_publie'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image_couverture' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    public function toggleStatut(Cour $cour)
    {
        $cour->statut = $cour->statut === 'publie' ? 'non_publie' : 'publie';
        $cour->save();

        return back()->with('success', $cour->titre . ' est maintenant ' . ($cour->estPublie() ? 'publié' : 'non publié') . '.');
    }
}
