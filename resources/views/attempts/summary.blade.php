@extends('layouts.app')

@section('content')
    <h1>Attempt summary</h1>

    <p>Total score: {{ $attempt->total_score }}</p>

    <h3>Answers</h3>
    <ul>
        @php
            // Build a map of answers by question id for quick lookup
            $answersMap = [];
            foreach($attempt->answers as $a) {
                $answersMap[$a->question_id] = $a;
            }
            $questions = $attempt->quiz->questions ?? collect();
        @endphp

        @foreach($questions as $idx => $q)
            @php $ans = $answersMap[$q->id] ?? null; @endphp
            <li>
                Q#{{ $idx + 1 }} — {{ Str::limit($q->text, 200) }} — option: {{ optional($ans->option)->label ?? '—' }} — awarded: {{ optional($ans)->awarded_points ?? 0 }}
            </li>
        @endforeach
    </ul>
@endsection
