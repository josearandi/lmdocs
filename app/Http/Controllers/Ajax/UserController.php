<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\User;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling CRUD operations for users
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
     * Lists all available documents as JSON
     */
    public function listJSON(Request $request) {
        $this->validateDocFilters($request);
        $queryData = $request->only(['has_documents']);
        $query = (new User())->newQuery();
        if($queryData['has_documents']) $query->has('docs');

        $users = $query->get();
        return $users;
    }

    /**
     * Creates a new document
     */
    public function create(Request $request) {
        $this->validateDoc($request);
        $data = $request->only(['title', 'tags', 'content']);
        $authUser = Auth::user();
        $newDocument = new Document([
            'title' => $data['title'],
            'content' => $data['content']
        ]);
        $newDocument->author()->associate($authUser);
        $newDocument->save();

        return response()->json([
            'message' => 'Document created successfully',
            'document' => $newDocument
        ],201);
    }

    protected function validateDoc($request) {
        $this->validate($request, [
            'title' => 'required|string',
            'tags' => 'array|max:10',
            'content' => 'required|string|max:2000'
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
