@extends('teacher.global.master')

@section('teacher_content')
<div class="card card-default">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="card-title mb-0">প্রশ্নপত্রের ক্যাটাগরি</h2>
        <a href="{{ route('questionCategoryForm') }}" class="btn btn-primary btn-sm">নতুন ক্যাটাগরি যোগ করুন</a>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>নাম</th>
                    <th>প্যারেন্ট ক্যাটাগরি</th>
                    <th>অবস্থা</th>
                    <th>অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->parent->name ?? '-' }}</td>
                        <td>
                            @if($cat->status=='1')
                                <span class="badge badge-outline-success badge-square">সক্রিয়</span>
                            @else
                                <span class="badge badge-outline-danger badge-square">নিষ্ক্রিয়</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('questionCategoryForm',$cat->id) }}" class="btn btn-sm btn-primary">সম্পাদনা</a>
                            <a href="{{ route('questionCategoryDelete',$cat->id) }}" class="btn btn-sm btn-danger"
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
        {{ $categories->links() }}
    </div>
</div>
@endsection
