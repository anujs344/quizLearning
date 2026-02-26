@extends('layouts.app')

@section('content')
    <h1>{{ $quiz->title }}</h1>
    <p>{{ $quiz->description }}</p>

    <h3>Quiz details</h3>
    <ul>
        <li>Total questions: {{ $quiz->total_questions }}</li>
        <li>Status: {{ $quiz->is_active ? 'Active' : 'Inactive' }}</li>
        <li>Time limit (seconds): {{ $quiz->time_limit_seconds ?? 'Not set' }}</li>
    </ul>

    <h3>Questions</h3>
    @forelse($quiz->questions as $idx => $q)
        <div style="border:1px solid #e5e7eb;padding:12px;margin-bottom:10px;border-radius:6px;background:#fff;">
            <div style="display:flex;justify-content:space-between;align-items:start;">
                <div style="flex:1;">
                    <strong>Q#{{ $idx + 1 }}:</strong> {!! nl2br(e($q->text)) !!}
                    <div style="margin-top:8px;color:#374151;font-size:13px;">
                        <strong>Options:</strong>
                        <ul>
                            @foreach($q->options as $opt)
                                <li>
                                    <strong>{{ $opt->label }}.</strong> {{ $opt->text }} — Points: <strong>{{ $opt->points ?? 0 }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div style="width:260px;margin-left:12px;">
                    <strong>Scoring rules</strong>
                    @if($q->scoringRules && $q->scoringRules->count() > 0)
                        <ul>
                            @foreach($q->scoringRules as $r)
                                <li>{{ $r->min_value }} - {{ $r->max_value }} => {{ $r->points }} pts</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="color:#6b7280">No range rules for this question.</div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p>No questions added yet.</p>
    @endforelse

    <a href="{{ route('quizzes.index') }}" class="link">← Back to quiz list</a>
@endsection
