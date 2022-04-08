<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{    

    /*
    |--------------------------------------------------------------------------
    | Blog Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for frontend operations, as adding comments, return data by id or url. 
    | Also this controller returns data to layouts
    |

    /**
     * posts
     *
     * @return void
     */
    public function posts(){
        $posts = Post::with('user')
            ->with('category')
            ->where('is_published',1)
            ->orderBy('created_at','desc')
            ->paginate(5);

        return view('home',['posts' => $posts]);
    }
    
    /**
     * Get post By Users
     *
     * @param  mixed $id
     * @return void
     */
    public function postByUsers($id){
        $posts = Post::with('user')
            ->with('category')
            ->where('is_published',1)
            ->where('user_id',$id)
            ->orderBy('created_at','desc')
            ->paginate(5);

        return view('home',['posts' => $posts]);
    }
    
    /**
     * Get post By Category
     *
     * @param  mixed $cat_id
     * @return void
     */
    public function postByCategory($cat_id){
        $posts = Post::with('user')
            ->with('category')
            ->where('is_published',1)
            ->where('category_id',$cat_id)
            ->orderBy('created_at','desc')
            ->paginate(5);

        return view('home',['posts' => $posts]);
    }
    
    /**
     * Get single Blog by url
     *
     * @param  mixed $post_url
     * @return void
     */
    public function singleBlog($post_url){
        $post = Post::with('user')
            ->with('category')
            ->where('is_published',1)
            ->where('post_url',$post_url)
            ->first();

        $comments = Comments::where('post_id',$post->id)
            ->get();
        $post->comments = $comments;

        $related_posts_id = Post::rightJoin('post_tag','posts.id','=','post_tag.post_id')->get(['posts.id'])->toArray();
        $ids = [];
        foreach ($related_posts_id as $id){
            if(in_array($id,$ids)){
                continue;
            }else{
                $ids[] = $id;
            }
        }
        $related_posts = Post::with('category')
            ->whereIn('id',$ids)
            ->where('is_published',1)
            ->get();
        return view('article',['post' => $post,'related_posts'=>$related_posts]);
    }
   
    /**
     * Add new comment
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function comment(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'author' => 'required|max:30',
            'email' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        if(empty($request->input('comment_id'))){
            $post = Post::find($id);
            if (!$post){
                return redirect()->back()->withInput($request->all())->with('error','Post Not Fond');
            }
            $comment = new Comments();
            $comment->author = $request->input('author');;
            $comment->email = $request->input('email');
            $comment->comments = $request->input('comment');

            $post->comments()->save($comment);
        }else{
            $comment = Comments::find($request->input('comment_id'));
            if (!$comment){
                return redirect()->back()->withInput($request->all())->with('error','Comment Not Fond');
            }

        }

        return redirect()->back()->with('success','Comment Add Successfully');
    }

}
