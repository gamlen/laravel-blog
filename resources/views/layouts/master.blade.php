<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie ie8" lang="en">
<![endif]-->
<!--[if (gte IE 9)|!(IE)]>
<html lang="en" class="no-js">
<![endif]-->
<html lang="en">
<head>
    <title>@yield('page_title')</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <!-- Page Description and Author -->
    <meta content="Romans - Blog" name="description">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    @yield('stylesheet')
</head>
<body>
<!-- Header Section Start -->
@include('layouts.header')
<!-- Header Section End -->
<!-- title Area Start -->
@yield('title')
<!-- title Area End -->
<!-- Content Start -->
<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                {{-- Content Here--}}
                @yield('content')
            </div>
            <div class="col-md-4">
                {{-- Sidebar Start--}}
                @include('layouts.sidebar')
                {{--Sidebar End--}}
            </div>
        </div>
    </div>
</div><!-- Content End -->
<!-- Footer Start -->
@include('layouts.footer')

</body>
</html>