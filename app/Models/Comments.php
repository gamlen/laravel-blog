<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'author', 'email', 'comments',
    ];

    
    /**
     * post
     *
     * @return void
     */
    public function post(){
        return $this->belongsTo(Post::class,'post_id');
    }
}
