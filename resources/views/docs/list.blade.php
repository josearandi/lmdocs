@extends('layouts.app')

@section('title','Documents')

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@section('content')
<div class="container" style="margin-bottom:35px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div id="doc-list-filters">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#doc-list-filters-collapse">Filters</a>
                        </h4>
                    </div>

                    <div id="doc-list-filters-collapse" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="doc-list-filter-author" class="control-label col-md-3">Author</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="author" id="doc-list-filter-author">
                                            <option value="" selected>All authors</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="doc-list-filters-created-at" class="control-label col-md-3">Created at</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="created_at" id="doc-list-filter-created-at">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="doc-list-filters-updated-at" class="control-label col-md-3">Last updated at</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="updated_at" id="doc-list-filter-updated-at">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="button" class="btn btn-warning" id="doc-list-filters-clear">Clear filters</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="doc-list-results"></div>
            <div class="text-center">
                <img src="/img/preloader-1.gif" width="35" alt="preloader" id="doc-list-preloader">
            </div>
            <div class="text-center">
                <button class="btn btn-primary" type="button" id="doc-list-more-button">Show more</button>
            </div>
            <div class="text-center">
                <p id="doc-list-no-results">No more results found.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        var resultsContainer = $('#doc-list-results');
        var preloader = $('#doc-list-preloader');
        var showMoreButton= $('#doc-list-more-button');
        var noMoreResults = $('#doc-list-no-results');
        // Filters
        var authorFilter = $('#doc-list-filter-author');
        var createdAtFilter = $('#doc-list-filter-created-at');
        var updatedAtFilter = $('#doc-list-filter-updated-at');
        var clearFiltersButton = $('#doc-list-filters-clear');
        
        var page = 1;
        var loadingResults = false;

        function loadResults(append) {
            append = append === undefined ? true : append; // Defaults to true
            if(!loadingResults) {
                var query = getQuery();
                setLoadingState();
                if(!append) resultsContainer.empty();
                $.ajax('/ajax/docs', {
                    type: 'GET',
                    data: query
                }).done(function(resp) {
                    handleResults(resp);
                }).always(function() {
                    clearLoadingState();
                });
            }
        }

        function getQuery() {
            var query = {};
            query.page = page;
            if(authorFilter.val()) query.author_id = authorFilter.val();
            if(createdAtFilter.val()) query.created_at = formatDate(createdAtFilter.datepicker('getDate'));
            if(updatedAtFilter.val()) query.updated_at = formatDate(updatedAtFilter.datepicker('getDate'));
            return query;
        }

        function loadNextPage() {
            page += 1;
            loadResults(); // Appending results
        }

        function triggerQuery() {
            page = 1;
            loadResults(false); // Replacing results
        }

        function setLoadingState() {
            loadingResults = true;
            preloader.show();
            showMoreButton.hide();
            noMoreResults.hide();
        }

        function clearLoadingState() {
            loadingResults = false;
            preloader.hide();
        }

        function handleResults(results) {
            if(results.trim() != '') {
                resultsContainer.append(results);
                showMoreButton.show();
            }else {
                handleNoMoreResults();
            }
        }

        function handleNoMoreResults() {
            console.log('No more results');
            showMoreButton.hide();
            noMoreResults.show();
        }

        function formatDate(date) {
            return date.toISOString().slice(0, 10); // TODO: handle timezones
        }

        // Initial results load
        loadResults(false);

        // Showing more results
        showMoreButton.on('click', function() {
            loadNextPage();
        });

        // Getting authors
        function getAuthors() {
            return $.ajax('/ajax/users.json', {
                type: 'GET',
                data: { has_documents: true }
            });
        }
        getAuthors().done(function(resp) {
            for(idx in resp) {
                var author = resp[idx];
                authorFilter.append('<option value="'+ author.id +'">'+ author.name +'</option>');
            }
        });

        // Datepickers
        createdAtFilter.datepicker();
        updatedAtFilter.datepicker();

        // Filtering
        authorFilter.on('change', function() {
            triggerQuery();
        });
        createdAtFilter.on('change', function() {
            triggerQuery();
        });
        updatedAtFilter.on('change', function() {
            triggerQuery();
        });
        clearFiltersButton.on('click', function() {
            authorFilter.val('');
            createdAtFilter.val('');
            updatedAtFilter.val('');
            triggerQuery();
        });
    });
</script>
@endpush