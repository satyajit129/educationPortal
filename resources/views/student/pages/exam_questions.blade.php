<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('source/css/style.css') }}">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">

    <style>
        * {
            font-family: "Kalpurush", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-variation-settings:
                "wdth" 100;
        }

        .question_header {
            padding: 20px 0;
        }

        .batch_info {
            text-align: center;
        }

        .batch_info .batch_name {
            font-family: "Kalpurush", sans-serif;
            font-size: 1.6rem;
            text-transform: uppercase;
            text-shadow: 0 0 0 currentColor, 1px 0 0 currentColor, 0 1px 0 currentColor;
        }

        .batch_info .contact_info {
            font-weight: 500;
        }

        .time_marks {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid black;
        }

        .questions_wrapper {
            display: flex;
            gap: 20px;
            /* space between columns */
            border-left: 1px solid transparent;
            /* placeholder to avoid layout shift */
        }

        /* Each column takes 50% of available width */
        .questions_column {
            flex: 1;
            border-left: 1px solid #ccc;
            /* separator on the left side of right column */
            padding-left: 20px;
        }

        /* Remove separator on the first column */
        .questions_column:first-child {
            border-left: none;
            padding-left: 0;
        }

        /* Responsive: single column on small screens */
        @media (max-width: 991px) {
            .questions_wrapper {
                flex-direction: column;
            }

            .questions_column {
                border-left: none;
                padding-left: 0;
            }
        }

        .question_item {
            margin-bottom: 10px;
        }
    </style>
</head>



<body>
    <div class="container">
        <div class="question_header">
            <div class="batch_info">
                <h5 class="batch_name">Benzir's Job Aid</h5>
                <h6 class="contact_info">Mobile: 01914-656314</h6>
                <p> {{ $exam->title }} </p>
            </div>
            <div class="time_marks">
                <p>সময় : {{ $exam->duration }} মিনিট</p>
                <p>পূর্ণমান : {{ round($exam->total_mark) }}</p>
            </div>
        </div>

        <form action="" method="POST">
            @csrf
            @php
    $bengali_letters = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ']; // extend if needed
@endphp

<div class="question_body">
    @php
        $total = $questions->count();
        $half = ceil($total / 2);
    @endphp

    <div class="questions_wrapper">
        <div class="questions_column">
            @foreach($questions->slice(0, $half) as $question)
                <div class="question_item">
                    <p><strong>{{ $loop->iteration }}.</strong> {{ $question->question_text }}</p>

                    @foreach($question->options as $index => $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $option->id }}" 
                                   id="option{{ $option->id }}">
                            <label class="form-check-label" for="option{{ $option->id }}">
                                {{ $bengali_letters[$index] ?? chr(65+$index) }}. {{ $option->option_text }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="questions_column">
            @foreach($questions->slice($half) as $question)
                <div class="question_item">
                    <p><strong>{{ $loop->iteration + $half }}.</strong> {{ $question->question_text }}</p>

                    @foreach($question->options as $index => $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $option->id }}" 
                                   id="option{{ $option->id }}">
                            <label class="form-check-label" for="option{{ $option->id }}">
                                {{ $bengali_letters[$index] ?? chr(65+$index) }}. {{ $option->option_text }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

            <button type="submit" class="btn btn-primary">Submit Exam</button>
        </form>
    </div>
</body>

</html>
