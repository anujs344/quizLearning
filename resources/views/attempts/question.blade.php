@extends('layouts.app')

@section('content')
    <h2>Question {{ $index + 1 }}</h2>
    <div>
        <p>{{ $question->text }}</p>
    </div>

    <div style="margin:8px 0;padding:8px;border:1px solid #ddd;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <strong>Question {{ $index + 1 }} of {{ $attempt->quiz->questions()->count() }}</strong>
        </div>
        <div>
            @if(!empty($timeLimit) && intval($timeLimit) > 0)
                <span id="countdown">Time left: <span id="countdown-seconds">{{ $timeLimit }}</span>s</span>
            @endif
        </div>
        <div>
            <button type="button" onclick="window.print();">Print</button>
        </div>
    </div>

    <form method="POST" action="{{ route('user.attempts.question.submit', ['attempt' => $attempt->id, 'index' => $index]) }}">
        @csrf
        <input type="hidden" name="question_id" value="{{ $question->id }}">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        @foreach($question->options as $opt)
            <div style="padding:8px;border:1px solid #eee;border-radius:4px;">
                <label style="display:block;cursor:pointer;">
                    <input type="radio" name="option_id" value="{{ $opt->id }}"> 
                    <strong>{{ $opt->label }}.</strong>
                    <span style="margin-left:6px;">{{ $opt->text }}</span>
                </label>
                <!-- Points are internal data and should not be shown to users -->
            </div>
        @endforeach
        </div>

        <div style="margin-top:12px;display:flex;gap:12px;align-items:center;">
            <label style="display:block;margin-bottom:6px;">
                <input type="checkbox" name="skipped" value="1"> Skip this question
            </label>
            <button type="submit">Submit</button>
        </div>
    </form>

    <form method="POST" action="{{ route('user.attempts.exit', ['attempt' => $attempt->id]) }}" style="display:inline;margin-top:8px;">
        @csrf
        <button type="submit" onclick="return confirm('Are you sure you want to exit the attempt? Your current answers will be submitted.')">Exit</button>
    </form>

    @if(!empty($timeLimit) && intval($timeLimit) > 0)
        <script>
            (function(){
                var seconds = parseInt(document.getElementById('countdown-seconds').textContent, 10);
                var el = document.getElementById('countdown-seconds');
                var interval = setInterval(function(){
                    seconds -= 1;
                    if (seconds <= 0) {
                        clearInterval(interval);
                        // auto-submit the form as skipped when time is up
                        var f = document.forms[0];
                        if (f) {
                            // ensure skipped checkbox is checked and submit
                            var skipped = f.querySelector('input[name="skipped"]');
                            if (skipped) skipped.checked = true;
                            f.submit();
                        }
                        return;
                    }
                    el.textContent = seconds;
                }, 1000);
            })();
        </script>
    @endif
@endsection
