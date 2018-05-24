@extends('layouts.app')

@section('title',$doc->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="{{ route('docs.list') }}">All Documents</a></li>
                <li class="active">{{ $doc->title }}</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading">{{ $doc->title }}</div>

                <div class="panel-body">
                    {!! $docHtml !!}
                </div>

                <div class="panel-footer text-right">
                    @if(Auth::user()->id == $doc->author->id)
                    <a class="btn btn-primary" href="{{route('docs.edit', ['id' => $doc->id])}}">Edit</a>
                    <button class="btn btn-danger" id="doc-show-delete-button">Delete</button>
                    @endif
                    <a class="btn btn-default" href="{{route('docs.pdf', ['id' => $doc->id])}}">Download PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var docId = {{ $doc->id }};
        var deleteButton = $('#doc-show-delete-button');
        deleteButton.on('click', function() {
            var confirmDelete = window.confirm('Do you want to delete this document?');
            if(confirmDelete) deleteDocument(docId);
        });

        function deleteDocument(id) {
            $.ajax('/ajax/docs/'+ id, {
                type: 'DELETE'
            }).done(function(resp) {
                window.location.href = '/docs';
            });
        }
    });
</script>
@endpush