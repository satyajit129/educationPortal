
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
            <div class="table-reponsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>বছর</th>
                            <th>প্রশ্ন সমূহ </th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($years as $year)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $year->title }}</td>
                                <td> <a href="{{ route('yearAddQuestionForm', $year->id) }}" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-plus-circle-outline"></i></a></td>
                                <td>
                                    <a href="{{ route('yearForm', $year->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <a href="{{ route('yearDelete', $year->id) }}" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('আপনি কি নিশ্চিত?')">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">কোন বছর পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
