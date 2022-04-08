<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category', 'is_active',
    ];
    
    /**
     * posts
     *
     * @return void
     */
    public function posts(){
        return $this->hasMany(Post::class,'category_id');
    }
    
}
