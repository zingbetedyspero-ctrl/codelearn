<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Evaluation $evaluation)
    {
        $questions = $evaluation->questions()->with('optionsReponse')->get();

        return view('questions.index', compact('evaluation', 'questions'));
    }

    public function create(Evaluation $evaluation)
    {
        return view('questions.create', compact('evaluation'));
    }

    public function store(Request $request, Evaluation $evaluation)
    {
        $validated = $this->valider($request);

        $question = $evaluation->questions()->create($validated);

        $this->sauverOptions($request, $question);

        return redirect()->route('evaluations.questions.index', $evaluation)->with('success', 'Question ajoutée.');
    }

    public function edit(Question $question)
    {
        $evaluation = $question->evaluation;
        $question->load('optionsReponse');

        return view('questions.edit', compact('evaluation', 'question'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $this->valider($request);

        $question->update($validated);

        $question->optionsReponse()->delete();
        $this->sauverOptions($request, $question);

        return redirect()->route('evaluations.questions.index', $question->evaluation)->with('success', 'Question mise à jour.');
    }

    public function destroy(Question $question)
    {
        $evaluation = $question->evaluation;
        $question->delete();

        return redirect()->route('evaluations.questions.index', $evaluation)->with('success', 'Question supprimée.');
    }

    public function storeMultiple(Request $request, Evaluation $evaluation)
    {
        $questions = $request->input('questions', []);

        $request->validate([
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.enonce' => ['required', 'string'],
            'questions.*.type_question' => ['required', 'in:qcm,question'],
            'questions.*.temps_reponse' => ['required', 'integer', 'min:5'],
            'questions.*.bareme' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($questions as $donnee) {
            $question = $evaluation->questions()->create([
                'enonce' => $donnee['enonce'],
                'type_question' => $donnee['type_question'],
                'temps_reponse' => $donnee['temps_reponse'],
                'bareme' => $donnee['bareme'],
            ]);

            if ($question->type_question === 'qcm') {
                foreach ($donnee['options'] ?? [] as $option) {
                    $texte = trim($option['texte'] ?? '');

                    if ($texte === '') {
                        continue;
                    }

                    $question->optionsReponse()->create([
                        'option_texte' => $texte,
                        'is_correct' => ! empty($option['correct']),
                    ]);
                }
            }
        }

        $nb = count($questions);

        return redirect()->route('evaluations.questions.index', $evaluation)
            ->with('success', $nb . ' question(s) ajoutée(s).');
    }

    private function valider(Request $request): array
    {
        return $request->validate([
            'enonce' => ['required', 'string'],
            'type_question' => ['required', 'in:qcm,question'],
            'temps_reponse' => ['required', 'integer', 'min:5'],
            'bareme' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function sauverOptions(Request $request, Question $question): void
    {
        if ($question->type_question !== 'qcm') {
            return;
        }

        $options = $request->input('options', []);

        foreach ($options as $donnee) {
            $texte = trim($donnee['texte'] ?? '');

            if ($texte === '') {
                continue;
            }

            $question->optionsReponse()->create([
                'option_texte' => $texte,
                'is_correct' => ! empty($donnee['correct']),
            ]);
        }
    }
}
