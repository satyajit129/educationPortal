<div class="mb-3 p-2 border rounded">
    <div class="d-flex align-items-start">

        <!-- ✅ Question Checkbox -->
        <div class="form-check me-2">
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
                <label class="d-flex align-items-center " style="gap: 5px;">
                    <input type="radio" name="q{{ $question->id }}" value="{{ $option->id }}">
                    {!! $option->option_text !!}
                </label>
            </div>
        @endforeach
    </div>
</div>
