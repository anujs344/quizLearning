<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\ScoringRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function create(Quiz $quiz)
    {
        return view('questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'time_limit_seconds' => 'required|integer|min:5',
            'allow_skip' => 'sometimes|boolean',
            'show_skip_button' => 'sometimes|boolean',
            'allow_back' => 'sometimes|boolean',
            'base_points' => 'required|integer|min:0',
            'options' => 'required|array|size:4',
            'options.*.label' => 'required|string|max:5',
            'options.*.text' => 'required|string',
            'options.*.points' => 'nullable|integer|min:0',
            'options.*.is_correct' => 'sometimes|boolean',
            'rules' => 'nullable|array',
            'rules.*.min_value' => 'nullable|numeric',
            'rules.*.max_value' => 'nullable|numeric',
            // points may be empty when user leaves rule rows blank; validate as nullable integer
            'rules.*.points' => 'nullable|integer|min:0',
        ]);

        // Enforce exactly 4 options (the UI now shows four option rows) and normalize points
        if (count($data['options']) !== 4) {
            return redirect()->back()->withErrors(['options' => 'Exactly 4 options are required.'])->withInput();
        }

        // Validate rules: only consider rules the user actually filled with numeric min/max.
        // This avoids accidental creation of default/empty ranges when the form posts empty strings.
        $rawRules = $data['rules'] ?? [];
        $rules = [];
        foreach ($rawRules as $r) {
            // skip if both values are empty or not set
            if (!isset($r['min_value']) || !isset($r['max_value'])) {
                continue;
            }
            if ($r['min_value'] === '' && $r['max_value'] === '') {
                continue;
            }
            // require numeric values for a rule to be considered
            if (!is_numeric($r['min_value']) || !is_numeric($r['max_value'])) {
                continue;
            }
            $rules[] = ['min_value' => floatval($r['min_value']), 'max_value' => floatval($r['max_value']), 'points' => intval($r['points'] ?? 0)];
        }

        if (!empty($rules)) {
            if (count($rules) !== 4) {
                return redirect()->back()->withErrors(['rules' => 'Range-based scoring requires 4 ranges (one for each option).'])->withInput();
            }

            $ranges = [];
            foreach ($rules as $r) {
                $min = $r['min_value'];
                $max = $r['max_value'];
                if (floatval($min) > floatval($max)) {
                    return redirect()->back()->withErrors(['rules' => 'Invalid rule ranges.'])->withInput();
                }
                $ranges[] = [floatval($min), floatval($max)];
            }

            usort($ranges, function($a, $b) { return $a[0] <=> $b[0]; });
            for ($i = 1; $i < count($ranges); $i++) {
                $prev = $ranges[$i-1];
                $curr = $ranges[$i];
                if ($prev[1] >= $curr[0]) {
                    return redirect()->back()->withErrors(['rules' => 'Scoring ranges must not overlap.'])->withInput();
                }
            }
        }

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'text' => $data['text'],
            'time_limit_seconds' => $data['time_limit_seconds'],
            'allow_skip' => $request->boolean('allow_skip'),
            'show_skip_button' => $request->boolean('show_skip_button'),
            'allow_back' => $request->boolean('allow_back'),
            'base_points' => $data['base_points'],
        ]);

        foreach ($data['options'] as $opt) {
            Option::create([
                'question_id' => $question->id,
                'label' => $opt['label'],
                'text' => $opt['text'],
                'points' => isset($opt['points']) ? intval($opt['points']) : 0,
                'is_correct' => !empty($opt['is_correct']),
            ]);
        }

        if (!empty($rules)) {
            foreach ($rules as $rule) {
                ScoringRule::create([
                    'question_id' => $question->id,
                    'min_value' => $rule['min_value'],
                    'max_value' => $rule['max_value'],
                    'points' => $rule['points'],
                ]);
            }
        }

        return redirect()
            ->route('quizzes.edit', $quiz)
            ->with('status', 'Question created.');
    }

    public function edit(Quiz $quiz, Question $question)
    {
        $question->load('options', 'scoringRules');

        return view('questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'time_limit_seconds' => 'required|integer|min:5',
            'allow_skip' => 'sometimes|boolean',
            'show_skip_button' => 'sometimes|boolean',
            'allow_back' => 'sometimes|boolean',
            'base_points' => 'required|integer|min:0',
            'options' => 'required|array|size:4',
            'options.*.id' => 'nullable|integer',
            'options.*.label' => 'required|string|max:5',
            'options.*.text' => 'required|string',
            'options.*.points' => 'nullable|integer|min:0',
            'options.*.is_correct' => 'sometimes|boolean',
            'rules' => 'nullable|array',
            'rules.*.id' => 'nullable|integer',
            'rules.*.min_value' => 'nullable|numeric',
            'rules.*.max_value' => 'nullable|numeric',
            // points may be empty when owner clears rules; allow nullable integer
            'rules.*.points' => 'nullable|integer|min:0',
        ]);

        $question->update([
            'text' => $data['text'],
            'time_limit_seconds' => $data['time_limit_seconds'],
            'allow_skip' => $request->boolean('allow_skip'),
            'show_skip_button' => $request->boolean('show_skip_button'),
            'allow_back' => $request->boolean('allow_back'),
            'base_points' => $data['base_points'],
        ]);

        // sync options (simple approach: delete & recreate)
        $question->options()->delete();
        foreach ($data['options'] as $opt) {
            Option::create([
                'question_id' => $question->id,
                'label' => $opt['label'],
                'text' => $opt['text'],
                'points' => isset($opt['points']) ? intval($opt['points']) : 0,
                'is_correct' => !empty($opt['is_correct']),
            ]);
        }

        // sync scoring rules: only persist rules that have numeric min/max values
        $question->scoringRules()->delete();
        $rawRules = $data['rules'] ?? [];
        $rules = [];
        foreach ($rawRules as $r) {
            if (!isset($r['min_value']) || !isset($r['max_value'])) {
                continue;
            }
            if ($r['min_value'] === '' && $r['max_value'] === '') {
                continue;
            }
            if (!is_numeric($r['min_value']) || !is_numeric($r['max_value'])) {
                continue;
            }
            $rules[] = ['min_value' => floatval($r['min_value']), 'max_value' => floatval($r['max_value']), 'points' => intval($r['points'] ?? 0)];
        }

        if (!empty($rules)) {
            foreach ($rules as $rule) {
                ScoringRule::create([
                    'question_id' => $question->id,
                    'min_value' => $rule['min_value'],
                    'max_value' => $rule['max_value'],
                    'points' => $rule['points'],
                ]);
            }
        }

        return redirect()
            ->route('quizzes.edit', $quiz)
            ->with('status', 'Question updated.');
    }
}


