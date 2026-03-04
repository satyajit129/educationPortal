@extends('teacher.global.master')


@section('teacher_custom_style')
@endsection


@section('teacher_content')
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 text-bold">শিক্ষক ড্যাশবোর্ড</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card card-default mb-4">
                <div class="card-header">
                     <h2 class="card-title">মোট ব্যাচসমূহ</h2>
                     <h4>12</h4>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-sm btn-pill btn-outline-primary">এক্সপ্লোর করুন</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card card-default mb-4">
                <div class="card-header">
                     <h2 class="card-title">মোট শিক্ষার্থী</h2>
                     <h4>350</h4>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-sm btn-pill btn-outline-primary">এক্সপ্লোর করুন</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('general_custom_js')
@endsection
