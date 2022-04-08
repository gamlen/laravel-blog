<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength('191');

        view()->composer('layouts.sidebar',function ($view){
            $post = new Post();

            //get category list
            $category = Category::where('is_active',1)->get();

            //get Latest comments
            $comments = $post->latestComments();

            $view->with('category',$category)
                ->with('comments',$comments);
        });

        view()->composer('layouts.header',function ($view){
            $category = Category::where('is_active',1)->get();
            $view->with('category',$category);
        });
    }
}
