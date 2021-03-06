@extends('layouts.master')

@section('page_title','Romans Blog - Home')

@section('title')

    <section class="text-center" id="title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="intro-area">
                        <h3>Welcome To</h3>
                        <h2 class="page-title">Romans Blog</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('content')
    @foreach($posts as $post)
        <article>
            <!-- Blog item Start -->
            <div class="blog-item-wrap">
                <h2 class="blog-title"><a href="{{ route('blog.single_blog',$post->post_url) }}">{{$post->post_title}}</a></h2><!-- Entry Meta Start-->
                <div class="entry-meta">
                    <span class="meta-part"><i class="ico-user"></i> <a href="{{ route('blog.post_by_users',[$post->user_id,str_replace(' ','-',strtolower($post->user->name))]) }}">{{ $post->user->name }}</a></span>
                    <span class="meta-part"><i class="ico-calendar-alt-fill"></i> <a href="#">{{ date('F j, Y ',strtotime($post->created_at)) }}</a></span>
                    <span class="meta-part"><i class="ico-comments"></i><a href="#">20</a></span>
                    <span class="meta-part"><i class="ico-tag"></i> <a href="{{ route('blog.post_by_category',[$post->category->id,str_replace(' ','-',strtolower($post->category->category))]) }}">{{ $post->category->category }}</a></span>
                    <span class="meta-part"><i class="ico-star"></i> <a href="#">7.5</a></span>
                </div><!-- Entry Meta End-->
                <!-- Post Content Start -->
                <div class="post-content">
                    <?php
                        $post_cantent = str_limit($post->post,1000);
                        echo $post_cantent;
                    ?>

                </div><!-- Post Content End -->
                <div class="entry-more">
                    <div class="pull-left">
                        <a class="btn btn-common" href="{{ route('blog.single_blog',$post->post_url) }}">Read More <i class="ico-arrow-right"></i></a>
                    </div>
                    <div class="share-icon pull-right">
                        <span class="socialShare"></span>
                    </div>
                </div>
            </div><!-- Blog item End -->
        </article>
    @endforeach

    @if($posts->lastPage() > 1)
        <article>
            <!-- Pagination Start -->
            <ul class="pager">
                @if($posts->previousPageUrl())
                    <li class="previous">
                        <a href="{{ $posts->previousPageUrl() }}"><i class="ico-arrow-left"></i>Previous</a>
                    </li>
                @endif
                @if($posts->nextPageUrl())
                    <li class="next">
                        <a href="{{ $posts->nextPageUrl() }}">Next <i class="ico-arrow-right"></i></a>
                    </li>
                @endif

            </ul><!-- Pagination End -->
        </article><!-- Blog Article End-->
    @endif




@endsection

