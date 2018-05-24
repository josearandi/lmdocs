<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Tag;

class Document extends Model
{
    //use SoftDeletes;
    
    /**
     * Table name
     */
    protected $table = 'docs';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'tags', 'content',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at','deleted_at'];

    /**
     * (ManyToOne)
     * User
     * Document's author
     */
    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    /**
     * (ManyToMany)
     * Tag
     * Document's tags
     */
    public function tags() {
        return $this->belongsToMany('App\Tag', 'doc_tags', 'doc_id', 'tag_id');
    }

    /**
     * Helper method to update document's tags
     */
    public function updateTags($tags) {
        // Removing existing tags
        $this->tags()->detach();
        // Adding new tags
        $existingTags = Tag::whereIn('label', $tags)->get();
        $tagsDiff = collect($tags)->diff($existingTags->pluck('label'));
        $newTags = $tagsDiff->map(function($t) {
            return Tag::create(['label' => $t]);
        });
        $tagsToAdd = $existingTags->union($newTags);
        $this->tags()->attach($tagsToAdd->pluck('id')->all());
    }
}
