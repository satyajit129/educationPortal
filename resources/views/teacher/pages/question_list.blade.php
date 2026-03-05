@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">প্রশ্নসমূহ</h2>
            <div>
                <a href="{{ route('questionForm') }}" class="btn btn-primary btn-sm">নতুন প্রশ্ন যোগ করুন</a>
                <a href="{{ route('questionImportExcel') }}" class="btn btn-outline-primary btn-sm">Excel আপলোড করুন</a>
                {{-- <a href="{{ route('questionExcel') }}" class="btn btn-outline-secondary btn-sm">Export CSV</a> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>শিরোনাম</th>
                            <th>ক্যাটাগরি</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $q)
                            <tr>
                               <td>{{ $questions->firstItem() + $loop->index }}</td>
                                <td>{!! Str::limit($q->question_text, 80) !!}</td>
                                <td>{{ $q->category->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('questionForm', $q->id) }}"
                                        class="btn btn-sm btn-warning">সম্পাদনা</a>
                                    <a href="{{ route('questionDelete', $q->id) }}" class="btn btn-sm btn-danger"
                                        onclick="return confirm('আপনি কি নিশ্চিত?')">মুছুন</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">কোনো প্রশ্ন পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $questions->links() }}
            </div>
            
        </div>
    </div>
@endsection
