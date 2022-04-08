@extends('super.master')

@section('page_title', 'Add New Post | Romans Blog')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                        <em class="fa fa-home"></em>
                    </a></li>
                <li class="active">New Post</li>
            </ol>
        </div><!--/.row-->
        <?php
        if (isset($post) && !empty($post)){
            $heading = 'Post Edit';
            $id = $post->id;
            $post_title = $post->post_title;
            $post_category = $post->category_id;
            $post_content = $post->post;
        }else{
            $heading = 'Add New Post';
            $id = 0;
            $post_title = '';
            $post_category = '';
            $post_content = '';
        }
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $heading }}
                    </div>
                    <div class="panel panel-body">
                        <form role="form" method="post" action="{{ route('super.save_post') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$id}}">
                            <div class="form-group">
                                <label>Post Title</label>
                                <input type="text" class="form-control" placeholder="Placeholder" name="post_title" value="{{ old('post_title') == '' ? $post_title : old('post_title')}}">
                                @if($errors->has('post_title'))
                                    <label class="text-danger">{{ $errors->first('post_title') }}</label>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="category">
                                    <option selected disabled>Select Category</option>
                                    @foreach($category as $cat)
                                        <option value="{{$cat->id}}" {{ $post_category == $cat->id ? 'selected' : '' }}>{{$cat->category}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('category'))
                                    <label class="text-danger">{{ $errors->first('category') }}</label>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Post Content</label>
                                <textarea id="post_content" name="post">{{ old('post') == '' ? $post_content : old('post') }}</textarea>
                                @if($errors->has('post'))
                                    <label class="text-danger">{{ $errors->first('post') }}</label>
                                @endif
                            </div>

                            @if (isset($post) && !empty($post))
                                <button type="submit" class="btn btn-success" name="edit" value="true">Submit</button>
                            @else
                                <button type="submit" class="btn btn-success" name="publish" value="true">Publish</button>
                                <button type="submit" class="btn btn-info" name="save" value="true">Save Draft</button>
                            @endif
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><!--/.row-->
    </div>
@endsection