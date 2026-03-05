
@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">বছর সমূহ</h2>
            <div>
                <a href="{{ route('yearForm') }}" class="btn btn-primary btn-sm">নতুন বছর যোগ করুন</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('yearSave') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $year->id ?? '' }}">
                <div class="form-group">
                    <label for="title">বছর</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $year->title ?? old('title') }}"
                        required>
                </div>
                <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
            </form>
        </div>
    </div>
@endsection
