<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('source/css/style.css') }}">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    {{-- <link href="{{ asset('fonts/font.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('source/plugins/toaster/toastr.min.css') }}" rel="stylesheet" />

    <style>
        * {
            font-family: "Kalpurush", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-variation-settings:
                "wdth" 100;
        }

        #examTimer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #000;
            color: #fff;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            z-index: 9999;
            font-weight: bold;
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
            border-left: 1px solid transparent;
        }

        .questions_column {
            flex: 1;
            border-left: 1px solid #ccc;
            padding-left: 20px;
        }

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

            #examTimer {
                padding: 1px 1px;
            }
        }

        .question_item {
            margin-bottom: 10px;
        }
    </style>
</head>



<body>
    <div class="container">
        <div id="examTimer">
            ⏱️ সময় বাকি: <span id="timeLeft"></span>
        </div>
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

        <form id="examForm" action="{{ route('studentExamSubmit') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_id" id="examId" value="{{ $exam->id }}">
            @php
                $bengali_letters = ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ'];
            @endphp
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>মোবাইল নম্বর <span class="text-danger">**</span></label>
                        <input type="text" name="mobile" class="form-control" placeholder="আপনার মোবাইল নম্বর লিখুন"
                            required>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>আপনার নাম লিখুন <span class="text-danger">**</span></label>
                        <input type="text" name="name" class="form-control" placeholder="আপনার নাম লিখুন"
                            required>
                    </div>
                </div>

            </div>


            <div class="question_body">
                @php
                    $total = $questions->count();
                    $half = ceil($total / 2);
                @endphp

                <div class="questions_wrapper">
                    <div class="questions_column">
                        @foreach ($questions->slice(0, $half) as $question)
                            <div class="question_item">
                                <p><strong>{{ $loop->iteration }}.</strong> {{ $question->question_text }}</p>

                                @foreach ($question->options as $index => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                            id="option{{ $option->id }}">
                                        <label class="form-check-label" for="option{{ $option->id }}">
                                            {{ $bengali_letters[$index] ?? chr(65 + $index) }}.
                                            {{ $option->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="questions_column">
                        @foreach ($questions->slice($half) as $question)
                            <div class="question_item">
                                <p><strong>{{ $loop->iteration + $half }}.</strong> {{ $question->question_text }}</p>

                                @foreach ($question->options as $index => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                            id="option{{ $option->id }}">
                                        <label class="form-check-label" for="option{{ $option->id }}">
                                            {{ $bengali_letters[$index] ?? chr(65 + $index) }}.
                                            {{ $option->option_text }}
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

    <script src="{{ asset('source/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('source/plugins/toaster/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            // ✅ CSRF Setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ✅ SINGLE FUNCTION FOR SUBMIT (manual + auto)
            function submitExam() {

                let form = $('#examForm');
                let formData = form.serialize();
                let submitBtn = form.find('button[type="submit"]');

                submitBtn.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: form.attr('action'),
                    method: "POST",
                    data: formData,
                    success: function(response) {

                        if (response.status === 'success') {
                            toastr.success(response.message);

                            console.log('Correct:', response.data?.correct);
                            console.log('Wrong:', response.data?.wrong);
                            console.log('Obtained Marks:', response.data?.obtained_marks);

                            if (response.route) {
                                setTimeout(function() {
                                    window.location.href = response.route;
                                }, 1500);
                            }

                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong!');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Submit Exam');
                    }
                });
            }

            // ✅ MANUAL SUBMIT
            $('#examForm').on('submit', function(e) {
                e.preventDefault(); // 🔥 IMPORTANT

                let name = $('input[name="name"]').val().trim();
                let mobile = $('input[name="mobile"]').val().trim();
                let checkedAnswers = $('input[type="radio"]:checked').length;

                // ✅ Validations
                if (name === '') {
                    toastr.error('আপনার নাম লিখুন');
                    return;
                }

                if (name.length < 3) {
                    toastr.warning('নাম কমপক্ষে ৩ অক্ষরের হতে হবে');
                    return;
                }

                if (mobile === '') {
                    toastr.error('মোবাইল নম্বর লিখুন');
                    return;
                }

                if (!/^01[3-9]\d{8}$/.test(mobile)) {
                    toastr.error('সঠিক মোবাইল নম্বর দিন (১১ সংখ্যা)');
                    return;
                }

                if (checkedAnswers < 1) {
                    toastr.warning('কমপক্ষে একটি প্রশ্নের উত্তর দিন');
                    return;
                }

                // ✅ CALL FUNCTION
                submitExam();
            });

        });
    </script>


    <script>
        $(document).ready(function() {

            let totalMinutes = {{ $exam->duration }};
            let totalSeconds = totalMinutes * 60;

            function formatTime(seconds) {
                let mins = Math.floor(seconds / 60);
                let secs = seconds % 60;
                return mins.toString().padStart(2, '0') + ':' + secs.toString().padStart(2, '0');
            }

            function updateTimer() {
                $('#timeLeft').text(formatTime(totalSeconds));

                if (totalSeconds <= 0) {
                    clearInterval(timerInterval);

                    toastr.warning('সময় শেষ! Exam auto submit হচ্ছে...');

                    // ✅ AUTO SUBMIT USING SAME FUNCTION
                    $('#examForm').off('submit'); // prevent validation block
                    $('#examForm button[type="submit"]').prop('disabled', true);

                    // trigger submit manually
                    $('#examForm').submit();
                }

                totalSeconds--;
            }

            updateTimer();

            let timerInterval = setInterval(updateTimer, 1000);
        });
    </script>

    <script>
        $(document).ready(function() {
            @if (session('success'))
                showToast('success', "{{ session('success') }}");
            @endif

            @if (session('error'))
                showToast('error', "{{ session('error') }}");
            @endif

            @if (session('warning'))
                showToast('warning', "{{ session('warning') }}");
            @endif
        });

        function showToast(type, message) {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };

            toastr[type](message);
        }
    </script>
</body>

</html>
