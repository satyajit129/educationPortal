@extends('teacher.global.master')

@section('teacher_content')
<div class="card card-default">
    <div class="card-header">
        <h2 class="card-title mb-0">নির্বাচিত প্রশ্নসমূহ</h2>
    </div>
    <div class="card-body">
        <ul class="list-group">
            @foreach($questions as $q)
                <li class="list-group-item">
                    <strong>#{{ $q->id }}</strong> — {{ Str::limit($q->title, 120) }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
