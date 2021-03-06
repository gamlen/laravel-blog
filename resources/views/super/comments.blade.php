@extends('super.master')

@section('page_title', 'Comments - Romans Blog')

@section('style')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ route('super.dashboard') }}">
                        <em class="fa fa-home"></em>
                    </a></li>
                <li><a href="{{ route('super.get_posts') }}">
                        Posts
                    </a></li>
                <li class="active">Comments</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Comments
                        <a class="btn btn-primary pull-right" href="{{ route('super.get_add_comment',$post_id) }}">Add Comments</a>
                    </div>
                    <div class="panel-body">

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <table id="comments" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>Author Name</th>
                                <th>Email</th>
                                <th>Comments</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Author Name</th>
                                <th>Email</th>
                                <th>Comments</th>
                                <th>Option</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div><!--/.row-->
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#comments').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ route('super.comments',$post_id) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "author" },
                    { "data": "email" },
                    { "data": "comments" },
                    { "data": "options"},
                ]
            });
        });
    </script>
@endsection