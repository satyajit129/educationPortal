@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">পূর্ববর্তী পরীক্ষার ক্যাটাগরি</h2>
            <a href="{{ route('previousExamCategoryForm') }}" class="btn btn-primary btn-sm">নতুন ক্যাটাগরি যোগ করুন</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>নাম</th>
                        <th>পরীক্ষাসমূহ</th>
                        <th>অবস্থা</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pre_exam_categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td>
                                <table class="table table-bordered table-striped table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>পরীক্ষার নাম</th>
                                            <th class="text-right">
                                                <a href="{{ route('previousExamListForm', ['categoryId' => $cat->id]) }}"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="mdi mdi-plus"></i> পরীক্ষা যুক্ত
                                                </a>
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cat->previousExam as $exam)
                                            <tr>
                                                <td>{{ $exam->name }} - <span
                                                        class="badge badge-outline-primary badge-pill">{{ $exam->year->title }}</span>
                                                </td>
                                                <td class="text-right">
                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('previousExamListForm', ['categoryId' => $cat->id, 'examId' => $exam->id]) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="mdi mdi-pencil-outline"></i>
                                                    </a>
                                                    {{-- Delete Button --}}
                                                    <a href="{{ route('previousExamListDelete', $exam->id) }}"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('আপনি কি নিশ্চিত?')">
                                                        <i class="mdi mdi-delete-outline"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">
                                                    কোনো পরীক্ষা নেই
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                @if ($cat->status == '1')
                                    <span class="badge badge-outline-success badge-square">সক্রিয়</span>
                                @else
                                    <span class="badge badge-outline-danger badge-square">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('previousExamCategoryForm', $cat->id) }}"
                                    class="btn btn-sm btn-primary">সম্পাদনা</a>
                                <a href="{{ route('previousExamCategoryDelete', $cat->id) }}" class="btn btn-sm btn-danger"
                                    onclick="return confirm('আপনি কি নিশ্চিত?')">মুছুন</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">কোনো ক্যাটাগরি পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
