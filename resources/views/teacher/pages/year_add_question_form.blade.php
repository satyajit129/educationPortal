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
            <form id="questionForm" action="{{ route('saveYearQuestions', ['id' => $id]) }}" method="POST">
                @csrf

                <input type="hidden" name="page" id="pageInput">

                <input type="hidden" name="id" value="{{ $id }}">

                @foreach ($questions as $question)
                    @include('teacher.pages.partials.year_question_list', ['question' => $question, 'selectedQuestions' => $selectedQuestions])
                @endforeach
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div>
                        {{ $questions->appends(['id' => $id])->links() }}
                    </div>
                    <div>

                        <button type="submit" class="btn btn-primary ">সংরক্ষণ করুন</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.pagination a').forEach(function(link) {

                link.addEventListener('click', function(e) {

                    e.preventDefault();

                    let url = new URL(this.href);
                    let page = url.searchParams.get("page");

                    document.getElementById('pageInput').value = page;

                    document.getElementById('questionForm').submit();

                });

            });

        });
    </script>
@endsection
