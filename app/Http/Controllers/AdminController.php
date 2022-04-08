<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for all CRUD operations in admin side. 
    | Also this controller returns data to layouts
    | All is in one controller, cause I had 2 days for that.
    |
    */
    
    /**
     * Join admin dashboard getDashboard
     *
     * @return void
     */
    public function getDashboard(){
        $posts = Post::where('is_published',1)->count();
        $users = User::count();
        $category = Category::where('is_active',1)->count();
        $comments = Comments::count();
        $last_post = Post::whereDate('created_at', '>', \Carbon\Carbon::now()->subDay())
            ->count();
        $last_user = User::whereDate('created_at', '>', \Carbon\Carbon::now()->subDay())
            ->count();
        $last_comments = Comments::whereDate('created_at', '>', \Carbon\Carbon::now()->subDay())
            ->count();
        $data = [
            'posts' => $posts,
            'users' =>$users,
            'comments' => $comments,
            'category' => $category,
            'last_comments' => $last_comments,
            'last_post' => $last_post,
            'last_user' => $last_user
        ];
        return view('super.dashboard',['data' => $data]);
    }

    
    /**
     * Get all users getUsers
     *
     * @return void
     */
    public function getUsers(){
        return view('super.users');
    }

    
    /**
     * Send users data to admin panel, to layout
     *
     * @param  mixed $request
     * @return void
     */
    public function users(Request $request){
        $columns  = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'status',
            4 => 'id',
        );

        $totalData = User::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $users = User::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }else{
            $search = $request->input('search.value');
            $users = User::where('name','LIKE','%'.$search.'%')
                ->orWhere('email','LIKE','%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = User::where('name','LIKE','%'.$search.'%')
                ->orWhere('email','LIKE','%'.$search.'%')->count();
        }

        $data = array();

        if(!empty($users)) {
            foreach ($users as $user) {
                $delete = route('super.delete_user',$user->id);
                $approve = route('super.approve_user',$user->id);

                $nestedData['id'] = $user->id;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['status'] = $user->is_active == 0 ? "<a href='{$approve}' class='btn btn-primary'>Active</a>" : "<span class='text-primary'><b>Activate</b></span>";
                $nestedData['options'] = $user->is_delete == 0 ? "<a href='{$delete}' class='btn btn-danger'>Delete</a>" : "<span class='text-danger'><b>Deleted</b></span>";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    
    /**
     * approveUser if they has not active status
     *
     * @param  mixed $id
     * @return void
     */
    public function approveUser($id){
        $user = User::where('is_active',0)->find($id);
        if (!$user){
            return redirect()->back()->with('error','User Not Find');
        }

        $user->is_active = 1;
        $result = $user->save();
        if (!$result){
            return redirect()->back()->with('error', 'Problem to Active User');
        }

        return redirect()->back()->with('success','User Active Successfully');
    }
    
    /**
     * deleteUser
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteUser($id){
        $user = User::where('is_active',1)->where('is_delete',0)->find($id);
        if (!$user){
            return redirect()->back()->with('error','User Not Find');
        }

        $user->is_delete = 1;
        $result = $user->save();
        if (!$result){
            return redirect()->back()->with('error', 'Problem to Delete User');
        }

        return redirect()->back()->with('success','User Delete Successfully');
    }

    
    /**
     * Return view to add user
     *
     * @return void
     */
    public function getAddUser(){
        return view('super.add_user');
    }

    
    /**
     * saveUser
     *
     * @param  mixed $request
     * @return void
     */
    public function saveUser(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:20',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|max:20',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->is_active = 1;
        $result = $user->save();

        if (!$result){
            return redirect()->back()->with('error','Problem to Create User')->withInput($request->all());
        }

        return redirect()->route('super.get_users')->with('success','User Created Successfully');
    }

    
    /**
     * getCategory
     *
     * @return void
     */
    public function getCategory(){
        $category = Category::get();
        return view('super.category',['category' => $category]);
    }
    
    /**
     * Send category data to layout
     *
     * @param  mixed $request
     * @return void
     */
    public function category(Request $request){
        $columns  = array(
            0 => 'id',
            1 => 'category',
            2 => 'status',
            3 => 'id',
        );

        $totalData = Category::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $category = Category::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }else{
            $search = $request->input('search.value');
            $category = Category::where('category','LIKE','%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Category::where('category','LIKE','%'.$search.'%')->count();
        }

        $data = array();

        if(!empty($category)) {
            foreach ($category as $cat) {
                $edit = route('super.edit_category',$cat->id);
                $delete = route('super.delete_category',$cat->id);
                $approve = route('super.approve_category',$cat->id);

                $nestedData['id'] = $cat->id;
                $nestedData['category'] = $cat->category;
                $nestedData['status'] = $cat->is_active == 0 ? "<a href='{$approve}' class='btn btn-primary'>Active</a>" : "<span class='text-primary'><b>Activate</b></span>";
                $nestedData['options'] = "<a href='{$edit}' title='Edit' ><span class='btn btn-warning'>Edit</span></a> &nbsp; <a href='{$delete}' title='Delete' ><span class='btn btn-danger'>Delete</span></a>";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }
    
    /**
     * View to add or get category
     *
     * @return void
     */
    public function getAddCategory(){
        $category = [];
        return view('super.add_category',['category' => $category]);
    }

    
    /**
     * editCategory
     *
     * @param  mixed $id
     * @return void
     */
    public function editCategory($id){
        $category = Category::find($id);
        return view('super.add_category',['category' => $category]);
    }

    
    /**
     * saveCategory
     *
     * @param  mixed $request
     * @return void
     */
    public function saveCategory(Request $request){
        $validator = Validator::make($request->all(),[
            'category' => 'required|unique:category,category|max:20'
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }
        $id = $request->input('id');
        $category_name = $request->input('category');

        if ($id == 0){
            $category = new Category();
            $category->category = $category_name;
            $category->is_active = 1;
            $result = $category->save();
        }else{
            $category = Category::find($id);
            $category->category = $category_name;
            $result = $category->save();
        }

        if (!$request){
            return redirect()->back()->with('error','Problem to Save Category')->withInput($request->all());
        }
        return redirect()->route('super.get_category')->with('success','Category Add Successfully');
    }

    
    /**
     * approveCategory if it has not active status
     *
     * @param  mixed $id
     * @return void
     */
    public function approveCategory($id){
        $category = Category::find($id);
        if (!$category){
            return redirect()->back()->with('error','Category Not Found');
        }
        $category->is_active = 1;
        $result = $category->save();
        if (!$result){
            return redirect()->back()->with('error','Problem to Active Category');
        }

        return redirect()->back()->with('success','Category Active Successfully');
    }

    
    /**
     * deleteCategory by id
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteCategory($id){
        $category = Category::find($id);
        if (!$category){
            return redirect()->back()->with('error','Category Not Found');
        }
        $result = $category->delete();
        if (!$result){
            return redirect()->back()->with('error','Problem to Delete Category');
        }

        return redirect()->back()->with('success','Category Delete Successfully');
    }

    
    /**
     * getPosts
     *
     * @return void
     */
    public function getPosts(){
        return view('super.posts');
    }

    
    /**
     * getAddPost
     *
     * @return void
     */
    public function getAddPost(){
        $category = Category::where('is_active',1)->get();
        return view('super.add_post',['category' => $category]);
    }

    
    /**
     * posts
     *
     * @param  mixed $request
     * @return void
     */
    public function posts(Request $request){
        $columns  = array(
            0 => 'id',
            1 => 'post_title',
            2 => 'category_id',
            3 => 'id',
            4 => 'status',
            5 => 'id',
            6 => 'id',
        );

        $totalData = Post::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $posts = Post::with('category')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }else{
            $search = $request->input('search.value');
            $posts = Post::with('category')
                ->where('post_title','LIKE','%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Post::where('post_title','LIKE','%'.$search.'%')->count();
        }
        $data = array();

        if(!empty($posts)) {
            foreach ($posts as $post) {
                $edit = route('super.edit_post',$post->id);
                $delete = route('super.delete_post',$post->id);
                $approve = route('super.publish_post',$post->id);
                $comment = route('super.get_comments',$post->id);

                $nestedData['id'] = $post->id;
                $nestedData['post_title'] = $post->post_title;
                $nestedData['post_category'] = $post->category->category;
                $nestedData['status'] = $post->is_published == 0 ? "<a href='{$approve}' class='btn btn-primary'>Publish</a>" : "<span class='text-primary'><b>Published</b></span>";
                $nestedData['options'] = "<a href='{$edit}' title='Edit' ><span class='btn btn-warning'>Edit</span></a> &nbsp; <a href='{$delete}' title='Delete' ><span class='btn btn-danger'>Delete</span></a>";
                $nestedData['comments'] = "<a href='{$comment}' class='btn btn-info'>Comments</a>";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    
    /**
     * editPost
     *
     * @param  mixed $id
     * @return void
     */
    public function editPost($id){
        $post = Post::find($id);
        $category = Category::where('is_active',1)->get();
        return view('super.add_post',['post' => $post,'category' => $category]);
    }

    
    /**
     * deletePost
     *
     * @param  mixed $id
     * @return void
     */
    public function deletePost($id){
        $post = Post::find($id);
        if (!$post){
            return redirect()->back()->with('error', 'Post Not Found');
        }

        $post->delete();
        return redirect()->back()->with('success','Post Successfully Deleted');
    }

    
    /**
     * publishPost
     *
     * @param  mixed $id
     * @return void
     */
    public function publishPost($id){
        $post = Post::find($id);
        if (!$post){
            return redirect()->back()->with('error', 'Post Not Found');
        }
        $post->is_published = 1;
        $post->save();

        return redirect()->back()->with('success','Post Successfully Published');
    }
    
    /**
     * savePost
     *
     * @param  mixed $request
     * @return void
     */
    public function savePost(Request $request){
        $user_id = Auth::id();
        $user = User::find($user_id);
        $validator = Validator::make($request->all(),[
            'post_title' => 'required|min:6|max:191',
            'category' => 'required',
            'post' => 'required',
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->with($request->all());
        }
        $post_model = new Post();
        $post_title = $request->input('post_title');
        $post_url = $post_model->urlEncode($post_title);
        $post_category = $request->input('category');
        $post_content = $request->input('post');

        if ($request->input('id') == 0){
            $post = new Post();
            $post->post_title = $post_title;
            $post->post_url = $post_url;
            $post->category_id = $post_category;
            $post->post = $post_content;

            if ($request->input('publish')){
                $post->is_published = 1;
            }else{
                $post->is_published = 0;
            }
            $user->posts()->save($post);

        }else{
            $post = Post::find($request->input('id'));
            $post->post_title = $post_title;
            $post->post_url = $post_url;
            $post->category_id = $post_category;
            $post->post = $post_content;
            $user->posts()->save($post);

        }

        return redirect()->route('super.get_posts')->with('success','Post Successfully Created');

    }
    
    /**
     * getComments by id
     *
     * @param  mixed $id
     * @return void
     */
    public function getComments($id){
        return view('super.comments',['post_id' => $id]);
    }

    
    /**
     * Send comments data to layout admin
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function comments(Request $request, $id){
        $columns  = array(
            0 => 'author',
            1 => 'email',
            2 => 'comments',
            3 => 'id',
            4 => 'id'
        );

        $totalData = Comments::where('post_id',$id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $comments = Comments::where('post_id',$id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }else{
            $search = $request->input('search.value');
            $comments = Comments::where('post_id',$id)
                ->where('comments','LIKE','%'.$search.'%')
                ->orWhere('name','LIKE','%'.$search.'%')
                ->orWhere('email','LIKE','%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Comments::where('post_id',$id)
                ->where('comments','LIKE','%'.$search.'%')
                ->orWhere('name','LIKE','%'.$search.'%')
                ->orWhere('email','LIKE','%'.$search.'%')
                ->count();
        }

        $data = array();

        if(!empty($comments)) {
            foreach ($comments as $comment) {
                $edit = route('super.edit_comment',$comment->id);
                $delete = route('super.delete_comment',$comment->id);

                $nestedData['author'] = $comment->author;
                $nestedData['email'] = $comment->email;
                $nestedData['comments'] = $comment->comments;
                $nestedData['options'] = "<a href='{$edit}' title='Edit' ><span class='btn btn-warning'>Edit</span></a> &nbsp; <a href='{$delete}' title='Delete' ><span class='btn btn-danger'>Delete</span></a>";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    
    /**
     * editComments
     *
     * @param  mixed $id
     * @return void
     */
    public function editComments($id){
        $comment = Comments::find($id);
        return view('super.add_comments',['comment' => $comment]);
    }
    
    /**
     * deleteComments
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteComments($id){
        $comment = Comments::find($id);
        if (!$comment){
            return redirect()->back()->with('error','comment Not Found');
        }
        $result = $comment->delete();
        if (!$result) {
            return redirect()->back()->with('error', 'Problem to comment');
        }

        return redirect()->back()->with('success','comment Delete Successfully');
    }
    
    /**
     * getAddComments
     *
     * @param  mixed $id
     * @return void
     */
    public function getAddComments($id){
        return view('super.add_comments',['post_id'=>$id]);
    }
    
    /**
     * saveComments
     *
     * @param  mixed $request
     * @return void
     */
    public function saveComments(Request $request){
        $validator = Validator::make($request->all(),[
            'comment' => 'required',
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        if ($request->input('id') == 0){
            $post = Post::find($request->input('post_id'));
            $user = Auth::user();
            $comment = new Comments();
            $comment->author = $user->name;
            $comment->email = $user->email;
            $comment->comments = $request->input('comment');
            $post->comments()->save($comment);
            return redirect()->route('super.comments',$request->input('post_id'))->with('success','Comments Successfully Created');

        }else{
            $comment = Comments::find($request->input('id'));
            $comment->comments = $request->input('comment');
            $comment->save();
            return redirect()->route('super.comments',$request->input('post_id'))->with('success','Comments Edit Successfully');
        }

    }
}
