<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Romans - Login</title>
</head>
<body>
<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Log in</div>
            <div class="panel-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form role="form" method="post" action="{{ route('login') }}">
                    @csrf
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Enter Email" name="email" type="email">
                            @if($errors->has('email'))
                                <label class="text-danger">{{ $errors->first('email') }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Enter Password" name="password" type="password">
                            @if($errors->has('password'))
                                <label class="text-danger">{{ $errors->first('password') }}</label>
                            @endif
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('get_register')}}" class="text-primary pull-left">Not Registered - Register</a>
                        <a href="{{ route('get_forgot_password')}}" class="text-primary pull-right">Forgot Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->

</body>
</html>
