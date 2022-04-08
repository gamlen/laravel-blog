<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $table = 'posts';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'post_title', 'post_url', 'post', 'is_published',
    ];

    
    /**
     * user
     *
     * @return void
     */
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    
    /**
     * category
     *
     * @return void
     */
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    
    /**
     * comments
     *
     * @return void
     */
    public function comments(){
        return $this->hasMany(Comments::class,'post_id');
    }

    
    /**
     * latestPosts
     *
     * @return void
     */
    public function latestPosts(){
        return $this->with('category')
            ->where('is_published',1)
            ->limit(5)
            ->orderBy('created_at','desc')
            ->get();
    }
    
    /**
     * latestComments
     *
     * @return void
     */
    public function latestComments(){
        return Comments::with('post')
            ->limit(5)
            ->orderBy('created_at','desc')
            ->get();
    }
    
    /**
     * urlEncode
     *
     * @param  mixed $string
     * @return void
     */
    public function urlEncode($string){
        $url = preg_replace("/[#$%^&*()+=\-\_\[\]\`\‘\’\';,.\/{}|\":<>?@!~\\\\]/",'',$string);
        $url = str_replace(' ','-',strtolower($url));
        return $url;
    }
}
