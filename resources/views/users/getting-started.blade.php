@extends('layouts.app')

@section('title','Getting started')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Getting started</div>

                <div class="panel-body">
                    <h1 class="text-center">Welcome to LMDocs!</h1>
                    <p class="text-center">
                        Now you can create and edit documents with ease using Markdown syntax.
                    </p>
                    <div class="text-center">
                        <a class="btn btn-primary" href="/docs/create">Create your first document</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
