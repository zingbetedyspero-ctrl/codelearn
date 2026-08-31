<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('cours')->orderBy('nom')->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $categorie = Categorie::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['categorie' => $categorie], 201);
        }

        return redirect()->route('categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $categorie->update($validated);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée.');
    }
}
