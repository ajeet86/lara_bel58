@extends('layouts.app')
@section('title', 'Login')
@section('description', '')
@section('content')
 <!-- about section -->
<section class="login-wrapper">

<div class="container">
    <div class="row justify-content-center login">
        <div class="col-md-8 col-md-offset-2">
            <div class="card login-form">
                <div class="card-header">{{ __('Login') }}</div>
                <div class="card-body">
                     <br/>
                    <form method="POST" action="{{ route($loginRoute) }}">
                        @csrf
                        <!--<div class="form-group row">
                           <label for="name" class="col-md-4 control-label">Login With</label>
                           <div class="col-md-6">

                               <a href="{{ url('login/facebook') }}" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>

                               <a href="{{ url('login/twitter') }}" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>

                               <a href="{{ url('login/google') }}" class="btn btn-social-icon btn-google-plus"><i class="fa fa-google-plus"></i></a>

                               <a href="{{ url('login/linkedin') }}" class="btn btn-social-icon btn-linkedin"><i class="fa fa-linkedin"></i></a>

                               <a href="{{ url('login/github') }}" class="btn btn-social-icon btn-github"><i class="fa fa-github"></i></a>

                               <a href="{{ url('login/bitbucket') }}" class="btn btn-social-icon btn-bitbucket"><i class="fa fa-bitbucket"></i></a>

                           </div>

                       </div>-->
						
                        <div class="form-group row">
                            <label for="email" class="col-md-6 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-6 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary all-btn-theme">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route($forgotPasswordRoute) }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
    
                    <div class="form-group">
                    <div class="row">
                    <div class="col-md-8 offset-md-4">

                    <!--<div class="login-ft-social">
                        <ul>
                            <li><a href="#" class="facebook-col"><i class="fa fa-facebook" aria-hidden="true"></i> Log In With Facebook</a></li>
                            <li><a href="#" class="google-plus-col"><i class="fa fa-google-plus" aria-hidden="true"></i> Log In With Google+</a></li>
                        </ul>
                    </div>-->
                    </div>
                    </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

 </section>
@endsection
