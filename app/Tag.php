<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Table name
     */
    protected $table = 'tags';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label'
    ];

    /**
     * (ManyToMany)
     * Document
     * Documents with this tag
     */
    public function docs() {
        return $this->belongsToMany('App\Document', 'doc_tags', 'tag_id', 'doc_id');
    }
}
