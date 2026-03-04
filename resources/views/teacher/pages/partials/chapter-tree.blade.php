@php $level = $level ?? 0; @endphp
@if($chapters->count())
<ul>
    @foreach($chapters as $chapter)
        <li id="chapter_{{ $chapter->id }}">
            {{ $chapter->name }}
            @if($chapter->children && $chapter->children->count())
                @include('teacher.pages.partials.chapter-tree', [
                    'chapters' => $chapter->children
                ])
            @endif
        </li>
    @endforeach
</ul>
@endif