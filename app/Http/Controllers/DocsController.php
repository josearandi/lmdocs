<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Document;
use Markdown;
use PDF;

class DocsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Docs Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling CRUD operations for documents.
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
    public function list() {
        return view('docs.list');
    }

    /**
     * Shows a single document
     */
    public function show($id) {
        $doc = Document::with('author','tags')->findOrFail($id);
        $docHtml = Markdown::convertToHtml($doc->content);
        return view('docs.show', ['doc' => $doc, 'docHtml' => $docHtml]);
    }

    /**
     * Creates a new document
     */
    public function create() {
        return view('docs.create');
    }

    /**
     * Edit an existing document
     */
    public function edit($id) {
        $doc = Document::findOrFail($id);
        return view('docs.edit', ['doc' => $doc]);
    }

    /**
     * Download document as PDF
     */
    public function downloadPDF($id) {
        $doc = Document::findOrFail($id);
        $docHtml = Markdown::convertToHtml($doc->content);
        $pdf = PDF::loadHtml($docHtml);
        $filename = str_slug($doc->title).'.pdf';
        return $pdf->download($filename);
    }
}
