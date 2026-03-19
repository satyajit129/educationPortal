<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>{{ $exam->title }} - ফলাফল</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        p{
            margin: 0;
            line-height: 0;
        }
        body {
            font-family: 'kalpurush';
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .batch_info {
            text-align: center;
        }

        .batch_info .batch_name {
            font-size: 1.6rem;
            text-transform: uppercase;
            text-shadow: 0 0 0 currentColor, 1px 0 0 currentColor, 0 1px 0 currentColor;
            margin: -5px;
        }
        .contact_info{
            font-size: 1.2rem;
            margin: -5px;
        }
    </style>
</head>

<body>

    <div class="batch_info">
        <p class="batch_name">Benzir's Job Aid</p>
        <p class="contact_info">Mobile: 01914-656314</p>
        <p> {{ $exam->title }} </p>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>নাম</th>
                <th>মোবাইল নং</th>
                <th>মোট প্রশ্ন</th>
                <th>প্রশ্নের উত্তর</th>
                <th>সঠিক উত্তর</th>
                <th>ভুল উত্তর</th>
                <th>সঠিক উত্তরের মার্ক</th>
                <th>নেগেটিভ মার্ক</th>
                <th>চূড়ান্ত প্রাপ্ত মার্ক</th>
            </tr>
        </thead>
        <tbody>
            @forelse($userAttempts as $key => $userAttempt)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $userAttempt->name }}</td>
                    <td>{{ $userAttempt->mobile }}</td>
                    <td>{{ $userAttempt->total_questions }}</td>
                    <td>{{ $userAttempt->answered_questions }}</td>
                    <td>{{ $userAttempt->correct_answers }}</td>
                    <td>{{ $userAttempt->wrong_answers }}</td>
                    <td>{{ $userAttempt->right_answer_mark }}</td>
                    <td>{{ $userAttempt->wrong_answer_mark }}</td>
                    <td>{{ $userAttempt->obtained_marks }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">কোনো তথ্য পাওয়া যায়নি</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
