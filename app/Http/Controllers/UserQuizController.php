<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Answer;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserQuizController extends Controller
{
    // Show available quizzes to users
    public function index()
    {
        $quizzes = Quiz::where('is_active', 1)->latest()->get();

        // include current user's attempts so they can see past scores
        $userAttempts = [];
        if (Auth::check()) {
            $userAttempts = \App\Models\QuizAttempt::where('user_id', Auth::id())
                ->with('quiz')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        if (view()->exists('quizzes.available')) {
            return view('quizzes.available', compact('quizzes', 'userAttempts'));
        }

        return response()->json(['quizzes' => $quizzes, 'userAttempts' => $userAttempts]);
    }

    // Start an attempt for a quiz
    public function start(Request $request, Quiz $quiz)
    {
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'total_score' => 0,
            'max_score' => 0,
            'started_at' => now(),
        ]);

        return redirect()->route('user.attempts.question', ['attempt' => $attempt->id, 'index' => 0]);
    }

    // Show a question for an attempt at given index
    public function showQuestion(QuizAttempt $attempt, $index)
    {
        $questions = $attempt->quiz->questions()->with('options')->get();
        $index = intval($index);
        if ($index < 0 || $index >= $questions->count()) {
            return redirect()->route('user.attempts.summary', $attempt);
        }

        $question = $questions[$index];

        // try to use per-quiz time limit if present (column may not exist yet)
        $timeLimit = $attempt->quiz->time_limit_seconds ?? 0; // seconds
        if (view()->exists('attempts.question')) {
            return view('attempts.question', compact('attempt', 'question', 'index', 'timeLimit'));
        }

        return response()->json(['attempt' => $attempt, 'question' => $question, 'index' => $index]);
    }

    // Handle answer submission
    public function submitAnswer(Request $request, QuizAttempt $attempt, $index)
    {
        $data = $request->validate([
            'option_id' => 'nullable|integer|exists:options,id',
            'skipped' => 'sometimes|boolean',
        ]);

        $skipped = $request->boolean('skipped');
        $option = null;
        $awarded = 0;

        // ensure attempt belongs to current user
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $skipped && ! empty($data['option_id'])) {
            $option = Option::with('question.scoringRules')->find($data['option_id']);
            if ($option) {
                $question = $option->question;
                // If the question has scoring rules, treat option.points as the value to match ranges
                $rules = $question->scoringRules()->get();
                if ($rules->count() > 0) {
                    $val = floatval($option->points ?? 0);
                    $matched = null;
                    foreach ($rules as $r) {
                        if ($val >= floatval($r->min_value) && $val <= floatval($r->max_value)) {
                            $matched = $r;
                            break;
                        }
                    }
                    if ($matched) {
                        $awarded = intval($matched->points);
                    } else {
                        // fallback to option.points
                        $awarded = intval($option->points ?? 0);
                    }
                } else {
                    // No rules: use option.points directly
                    $awarded = intval($option->points ?? 0);
                }
            }
        }

        // determine question id: prefer explicit field, else by index
        $questionId = $request->input('question_id');
        if (! $questionId) {
            $q = $attempt->quiz->questions()->skip($index)->first();
            $questionId = $q ? $q->id : null;
        }

        Answer::create([
            'quiz_attempt_id' => $attempt->id,
            'question_id' => $questionId,
            'option_id' => $option ? $option->id : null,
            'awarded_points' => $awarded,
            'skipped' => $skipped,
        ]);

        // update attempt totals (simple sum)
        $attempt->total_score = ($attempt->total_score ?? 0) + $awarded;
        $attempt->save();

        // next index
        $next = intval($index) + 1;
        $questionsCount = $attempt->quiz->questions()->count();
        if ($next >= $questionsCount) {
            return redirect()->route('user.attempts.summary', $attempt);
        }

        return redirect()->route('user.attempts.question', ['attempt' => $attempt->id, 'index' => $next]);
    }

    // Summary of an attempt
    public function summary(QuizAttempt $attempt)
    {
        // load answers with option and question, and quiz questions to present answers in question order
        $attempt->load('answers.option', 'answers.question', 'quiz.questions');

        if (view()->exists('attempts.summary')) {
            return view('attempts.summary', compact('attempt'));
        }

        return response()->json($attempt);
    }

    // Exit attempt early and show summary
    public function exitAttempt(Request $request, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->finished_at = now();
        $attempt->save();

        return redirect()->route('user.attempts.summary', $attempt);
    }
}
