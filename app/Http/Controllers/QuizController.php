<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('created_by', Auth::id())
            ->latest()
            ->get();

        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_questions' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['created_by'] = Auth::id();
        $data['is_active'] = $request->boolean('is_active');

        $quiz = Quiz::create($data);

        return redirect()
            ->route('quizzes.index')
            ->with('status', 'Quiz created. Next, add questions and options.');
    }

    public function edit(Quiz $quiz)
    {
        $this->authorizeOwner($quiz);

        return view('quizzes.edit', compact('quiz'));
    }

    public function show(Quiz $quiz)
    {
        $this->authorizeOwner($quiz);

        // load questions, options and scoring rules for display
        $quiz->load('questions.options', 'questions.scoringRules');

        return view('quizzes.show', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorizeOwner($quiz);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_questions' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $quiz->update($data);

        return redirect()
            ->route('quizzes.index')
            ->with('status', 'Quiz updated.');
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorizeOwner($quiz);

        $quiz->delete();

        return redirect()
            ->route('quizzes.index')
            ->with('status', 'Quiz deleted.');
    }

    protected function authorizeOwner(Quiz $quiz)
    {
        if ($quiz->created_by !== Auth::id()) {
            abort(403);
        }
    }
}


