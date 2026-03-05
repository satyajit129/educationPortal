@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">{{ isset($category) ? 'ক্যাটাগরি সম্পাদনা' : 'নতুন ক্যাটাগরি' }}</h2>
            <a href="{{ route('questionCategoryList') }}" class="btn btn-secondary btn-sm">ফিরে যান</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('questionCategorySave') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ old('id', $category->id ?? '') }}">

                <div class="form-group">
                    <label>নাম</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $category->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>প্যারেন্ট ক্যাটাগরি (ঐচ্ছিক)</label>

                    @php
                        $selectedParent = old(
                            'parent_category_id',
                            isset($category) 
                                ? $category->parent_category_id 
                                : session('last_parent_category_id')
                        );
                    @endphp

                    <select name="parent_category_id" class="form-control select2">
                        <option value="">-- পছন্দ করুন --</option>

                        @foreach ($parents as $p)
                            <option value="{{ $p->id }}"
                                {{ (string) $selectedParent === (string) $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label>অবস্থা</label>
                    <select name="status" class="form-control" required>
                        <option value="1" {{ old('status', $category->status ?? '1') === '1' ? 'selected' : '' }}>
                            সক্রিয়</option>
                        <option value="0" {{ old('status', $category->status ?? '0') === '0' ? 'selected' : '' }}>
                            নিষ্ক্রিয়</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary">{{ isset($category) ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <script>
        $('.select2').select2({
            placeholder: "নির্বাচন করুন",
            width: '100%'
        });
    </script>
@endsection
