@extends('layouts.app')

@section('title','Edit Document - '.$doc->title)

@section('content')
<div id="doc-form-page-preloader">
    <img src="/img/preloader-1.gif" width="50" alt="preloader">
</div>
<div class="container-fluid">
    <div class="row">
        <form class="col-md-5" id="doc-form">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Edit Document - {{ $doc->title }}</div>

                        <div class="panel-body">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="doc-title" class="control-label col-md-3">Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title" id="doc-title" placeholder="Untitled" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="doc-tags" class="control-label col-md-3">Tags</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="tags" id="doc-tags" placeholder="e.g. report, important, to do">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Contents</div>

                        <div class="panel-body">
                            <textarea name="content" id="doc-markdown" placeholder="Write your markdown content here..." required></textarea>
                        </div>

                        <div class="panel-footer text-right">
                            <img src="/img/preloader-1.gif" width="30" alt="preloader" id="doc-form-preloader">
                            <button type="submit" class="btn btn-primary" id="submit-doc-button">Save</button>
                            <button type="button" class="btn" id="discard-doc-button">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">Preview</div>

                <div class="panel-body">
                    <div id="doc-preview-html"></div>
                    <div id="doc-preview-html-placeholder">
                        No content yet...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.8.6/showdown.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        var form = $('#doc-form');
        // Edit document
        var docId = {{ $doc->id }};
        loadDocument(docId);
        var pagePreloader = $('#doc-form-page-preloader');
        
        var docTitle = $('#doc-title');
        var docTags = $('#doc-tags');
        var docMarkdown = $('#doc-markdown');
        var docPreviewHtml = $('#doc-preview-html');
        var docPreviewHtmlPlaceholder = $('#doc-preview-html-placeholder');

        docMarkdown.on('input', function() {
            var contents = docMarkdown.val();
            var isContentEmpty = contents.trim() == '';
            docPreviewHtmlPlaceholder.toggle(isContentEmpty);
            docPreviewHtml.html(markdownToHtml(contents));
        });

        var markdownConverter = new showdown.Converter();
        function markdownToHtml(text) {
            return markdownConverter.makeHtml(text);
        }

        var discardButton = $('#discard-doc-button');
        discardButton.on('click', function() {
            if(!processingSubmit) {
                window.location.href = '/docs/'+ docId;
            }
        });
        var submitButton = $('#submit-doc-button');
        var preloader = $('#doc-form-preloader');
        preloader.hide();

        form.validate({
            messages:{
                title: 'Please enter a title for your document',
                content: {
                    required: 'Document\'s content can\'t be empty.',
                    maxlength: 'Content is too large, please do not exceed 2000 characters'
                }
            }
        });
        var processingSubmit = false;
        form.on('submit', function(ev) {
            ev.preventDefault();
            if(!processingSubmit && isFormValid()) {
                blockForm();
                // Submitting form
                var data = processData();
                submitData(data).done(function(resp) {
                    console.log(resp);
                    window.location.href = '/docs/'+ docId;
                }).always(function() {
                    unblockForm();
                });
            }
        });

        function isFormValid() {
            return form.valid();
        }

        function blockForm() {
            processingSubmit = true;
            submitButton.attr('disabled','disabled');
            discardButton.attr('disabled','disabled');
            preloader.show();
        }

        function unblockForm() {
            processingSubmit = false;
            submitButton.removeAttr('disabled');
            discardButton.removeAttr('disabled');
            preloader.hide();
        }

        function processData() {
            var title = docTitle.val();
            var tags = docTags.val().split(/\s*,\s*/);
            var content = docMarkdown.val();

            return {
                title: title,
                tags: tags,
                content: content
            };
        }

        function submitData(data) {
            return $.ajax('/ajax/docs/'+ docId, {
                type: 'PUT',
                data: data
            });
        }

        function loadDocument(id) {
            $.ajax('/ajax/docs/'+ id +'.json', {
                type: 'GET'
            }).done(function(resp) {
                docTitle.val(resp.title);
                var tags = _.map(resp.tags,function(t) {
                    return t.label;
                }).join(', ');
                docTags.val(tags);
                docMarkdown.val(resp.content);
                docMarkdown.trigger('input'); // Updating preview
                pagePreloader.hide();
            });
        }
    });
</script>
@endpush