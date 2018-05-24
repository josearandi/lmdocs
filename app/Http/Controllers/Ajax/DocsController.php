<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Document;
use DB;

class DocsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Docs Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling CRUD operations for documents
    | via AJAX.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lists all available documents
     */
    public function list(Request $request) {
        $this->validateDocFilters($request);
        $queryData = $request->only(['author_id','created_at','updated_at']);
        $query = (new Document())->newQuery();
        $query->with('author','tags');
        if($queryData['author_id']) $query->where('author_id', $queryData['author_id']);
        if($queryData['created_at']) $query->whereDate('created_at', $queryData['created_at']);
        if($queryData['updated_at']) $query->whereDate('updated_at', $queryData['updated_at']);
        
        $docs = $query->simplePaginate(10);
        return view('ajax.docs.doc-results', ['docs' => $docs]);
    }

    /**
     * Creates a new document
     */
    public function create(Request $request) {
        $this->validateDoc($request);
        $data = $request->only(['title', 'tags', 'content']);
        $authUser = Auth::user();

        $newDocument = DB::transaction(function() use($authUser, $data) {
            $newDocument = new Document([
                'title' => $data['title'],
                'content' => $data['content']
            ]);
            $newDocument->author()->associate($authUser);
            $newDocument->save();

            // Saving tags
            if($data['tags']) {
                $newDocument->updateTags($data['tags']);
            }

            return $newDocument;
        });

        return response()->json([
            'message' => 'Document created successfully',
            'document' => $newDocument
        ],201);
    }

    /**
     * Return a document as JSON
     */
    public function showJSON($id) {
        $doc = Document::with('tags')->findOrFail($id);
        return $doc;
    }

    /**
     * Update a document
     */
    public function update($id, Request $request) {
        $doc = Document::findOrFail($id);
        $authUser = Auth::user();
        if($authUser->can('update',$doc)) {
            $this->validateDoc($request);
            $data = $request->only(['title', 'tags', 'content']);

            $doc->title = $data['title'];
            $doc->content = $data['content'];
            $doc->save();

            // Saving tags
            if($data['tags']) {
                $doc->updateTags($data['tags']);
            }

            return response()->json([
                'message' => 'Document updated successfully',
                'doc' => $doc
            ]);
        }else {
            return response()->json([
                'message' => 'You are not allowed to edit this document.'
            ],403); // 403 Forbidden
        }

    }

    /**
     * Soft deletes a document
     */
    public function delete($id) {
        $doc = Document::findOrFail($id);
        $authUser = Auth::user();
        if($authUser->can('delete',$doc)) {
            $doc->delete(); // Soft delete
            return response()->json([
                'message' => 'Document deleted successfully'
            ]);
        }else {
            return response()->json([
                'message' => 'You are not allowed to edit this document.'
            ],403); // 403 Forbidden
        }
    }

    protected function validateDoc($request) {
        $this->validate($request, [
            'title' => 'required|string',
            'tags' => 'array|max:10',
            'content' => 'required|string|max:5000'
        ]);
    }

    protected function validateDocFilters($request) {
        $this->validate($request, [
            'author_id' => 'numeric',
            'created_at' => 'date',
            'updated_at' => 'date'
        ]);
    }
}
