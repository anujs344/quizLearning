@extends('layouts.app')

@section('content')
    <h1 style="margin-bottom:8px;">Available quizzes</h1>
    <p style="color:#6b7280;margin-top:0;margin-bottom:18px;">Browse and start quizzes available to you. Click Start to begin an attempt.</p>

    @if($quizzes->isEmpty())
        <div style="padding:18px;border-radius:10px;background:#fff;border:1px solid #e6e7eb;color:#374151;">No quizzes available right now.</div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;">
            @foreach($quizzes as $quiz)
                <div style="background:white;border-radius:12px;padding:14px;border:1px solid #e6e7eb;display:flex;flex-direction:column;justify-content:space-between;">
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:start;gap:10px;">
                            <div>
                                <div style="font-weight:700;color:#111827;font-size:16px;">{{ $quiz->title }}</div>
                                <div style="color:#6b7280;margin-top:6px;font-size:13px;">{{ Str::limit($quiz->description, 140) }}</div>
                            </div>
                            <div style="text-align:right;min-width:78px;">
                                <div style="font-size:12px;color:#9ca3af;">Questions</div>
                                <div style="font-weight:700;color:#111827">{{ $quiz->total_questions }}</div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center;gap:8px;">
                        <form method="POST" action="{{ route('user.quizzes.start', $quiz) }}" style="margin:0;">
                            @csrf
                            <button type="submit" style="background:linear-gradient(135deg,#4f46e5,#6366f1);color:white;border:none;padding:8px 12px;border-radius:8px;cursor:pointer;font-weight:600;">Start</button>
                        </form>

                        <div style="font-size:12px;color:#9ca3af;">
                            @if($quiz->is_active)
                                <span style="padding:4px 8px;border-radius:999px;border:1px solid #10b981;color:#065f46;background:#ecfdf5;">Active</span>
                            @else
                                <span style="padding:4px 8px;border-radius:999px;border:1px solid #d1d5db;color:#374151;background:#f8fafc;">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
