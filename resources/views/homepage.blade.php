@extends('layouts.app')

@section('title','Edit your documents with ease')

@push('meta')
<meta property="og:url"                content="{{url('/')}}" />
<meta property="og:type"               content="website" />
<meta property="og:title"              content="LMDocs - Edit your documents with ease" />
<meta property="og:description"        content="LMDocs is a markdown editor online." />
<meta property="og:image"              content="{{url('/img/lmdocs-ogimage.png')}}" />
<meta property="og:image:width"              content="1200" />
<meta property="og:image:height"              content="630" />
@endpush

@section('content')
<div class="jumbotron">
    <div class="container">
        <h1>LMDocs!</h1>
        <p>Edit your documents with ease, from your desktop or mobile phone.</p>
        <p>
            @if(Auth::guest())
            <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Get started</a>
            @else
            <a class="btn btn-default btn-lg" href="{{ route('docs.list') }}">View documents</a>
            <a class="btn btn-primary btn-lg" href="{{ route('docs.create') }}">Create new</a>
            @endif
        </p>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h2>Edit on the fly!</h2>
            <p>No more lost documents in an old flash drive, you can have all your documents available everywhere from every device. (as long as you have Internet...)</p>
        </div>
        <div class="col-md-4">
            <h2>Use Markdown</h2>
            <p>Use markdown language to write expressive documents with ease. It's almost magic.</p>
        </div>
        <div class="col-md-4">
            <h2>Even more features</h2>
            <p>Checkout all available features by signing up today.</p>
        </div>
    </div>
</div>
@endsection