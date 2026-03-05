<div class="mb-3 p-2 border rounded">
    <div class="d-flex align-items-start">

        <!-- ✅ Question Checkbox -->
        <div class=" mr-2">
            <input type="checkbox" name="questions[]" value="{{ $question->id }}"
                {{ in_array($question->id, $selectedQuestions) ? 'checked' : '' }}>
        </div>

        <!-- Question Text -->
        <h5 class="mb-0 d-flex align-items-center">
            <span
                class="badge badge-primary badge-pill">Q{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}</span>
            {!! $question->question_text !!}
        </h5>
    </div>

    <hr>

    <!-- Options -->
    <div class="row mt-2">
        @foreach ($question->options as $option)
            <div class="col-lg-6 col-md-12">
                <label class="d-flex align-items-center" style="gap: 10px;">
    <input 
        type="radio" 
        name="q{{ $question->id }}" 
        value="{{ $option->id }}" 
        disabled
        {{ $option->is_correct == 1 ? 'checked' : '' }}
    >
    <span style="
        font-weight: bold; 
        color: {{ $option->is_correct == 1 ? 'green' : '#333' }};
        font-size: 16px;
    ">
        {!! $option->option_text !!}
    </span>
</label>
            </div>
        @endforeach
    </div>
</div>
