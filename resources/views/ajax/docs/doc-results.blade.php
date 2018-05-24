@foreach($docs as $doc)
<div class="panel panel-default">
    <div class="panel-heading">
        <a href="{{ route('docs.show', $doc->id) }}">{{ $doc->title }}</a>
    </div>

    <div class="panel-body">
        <div>Author: {{ $doc->author->name }}</div>
        @if($doc->tags->count() > 0)
        <div>Tags: {{ implode(', ',$doc->tags->pluck('label')->all()) }} </div>
        @endif
        <div>Created at: {{ $doc->created_at }}</div>
    </div>

    <div class="panel-footer text-right">
        <a class="btn" href="{{ route('docs.show', $doc->id) }}">View document</a>
    </div>
</div>
@endforeach