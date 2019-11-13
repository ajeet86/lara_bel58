@extends('admin.layout.app')
@section('title', 'Add Users')
@section('css')
<link href="{{URL::asset('/public/admn/css/plugincss/jasny-bootstrap.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('/public/admn/css/bootstrapValidator.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop
@section('content')

<div id="content" class="bg-container">
    <header class="head">
        <div class="main-bar">
            <div class="row no-gutters">
                <div class="col-6">
                    <h4 class="m-t-5">
                        <i class="fa fa-user"></i>
                        {{ isset($user->id) ? 'Edit User' : 'Add Users' }}
                    </h4>
                </div>
            </div>
        </div>
    </header>
    @if(session()->has('error'))
    <div class="col-md-12">
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    </div>
    @endif
    @if(session()->has('success'))
    <div class="col-md-12">
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    </div>
    @endif
    <div class="outer">
        <div class="inner bg-container">
            <!--top section widgets-->
            <div class="card">

                <div class="card-block m-t-35">
                    <div>
                        <h4>Personal Information</h4>
                    </div>
                    <form class="form-horizontal login_validator" id="tryitForm"
                          enctype="multipart/form-data" action="{{ isset($user->id) ? url('/admin/edit_user/' . $user->id) : url('/admin/add_user') }}"
                          method="post">
                        <div class="row">
                            <div class="col-12">
                                {{ csrf_field() }}
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-center text-lg-right">
                                        <label class="col-form-label">Avatar</label>
                                    </div>
                                    <div class="col-lg-6 text-center text-lg-left">
                                        <div class="fileinput fileinput-new" 
                                             data-provides="fileinput">
                                            <div class="fileinput-new img-thumbnail text-center">
                                                @if (isset($user->avatar))
                                                <img src="{{url('public/images/users/' . $user->avatar)}}"></div>
                                                @else
                                                <img src="" 
                                                     data-src="holder.js/100%x100%"  
                                                     alt="not found"></div>
                                                @endif
                                            <div class="fileinput-preview fileinput-exists img-thumbnail"></div>
                                            <div class="m-t-20 text-center">
                                                <span class="btn btn-primary btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" name="avatar"></span>
                                                <a href="#" class="btn btn-warning fileinput-exists"
                                                   data-dismiss="fileinput">Remove</a>
                                            </div>
                                            @if ($errors->has('avatar'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('avatar') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="u-name" class="col-form-label">
                                            First Name *</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user text-primary"></i>
                                            </span>
                                            <input type="text" name="name"
                                                   class="form-control" value="{{ isset($user->name) ? $user->name : old('name') }}">
                                        </div>
                                        @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="u-name" class="col-form-label">
                                            Surname *</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user text-primary"></i>
                                            </span>
                                            <input type="text" name="surname"
                                                   class="form-control" value="{{ isset($user->surname) ? $user->surname : old('surname') }}">
                                        </div>
                                        @if ($errors->has('surname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('surname') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="u-name" class="col-form-label">
                                            Username *</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user-circle-o text-primary"></i>
                                            </span>
                                            <input type="text" name="username"
                                                   value="{{ isset($user->username) ? $user->username : old('userName') }}"
                                                   autocomplete="user-name"
                                                   class="form-control">
                                        </div>
                                        @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="email" class="col-form-label">Email
                                            *</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope text-primary"></i>
                                            </span>
                                            <input type="text" value="{{ isset($user->email) ? $user->email : old('email') }}" name="email"
                                                   class="form-control">
                                        </div>
                                        @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="pwd" class="col-form-label">Password
                                            {{ isset($user->id) ? '' : '*' }}</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-lock text-primary"></i>
                                            </span>
                                            <input type="password" name="password" autocomplete="new-password"
                                                   class="form-control pwd">
                                        </div>
                                        @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="cpwd" class="col-form-label">Confirm
                                            Password {{ isset($user->id) ? '' : '*' }}</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-lock text-primary"></i>
                                            </span>
                                            <input type="password" name="confirmpassword" placeholder=" "
                                                   class="form-control pwd">
                                        </div>
                                        @if ($errors->has('confirmpassword'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('confirmpassword') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="phone" class="col-form-label">Phone
                                            </label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-phone text-primary"></i>
                                            </span>
                                            <input name="phone"
                                                   type="number"
                                                   minlength="10"
                                                   maxlength="20"
                                                   value="{{ isset($user->phone) ? $user->phone : old('phone') }}" 
                                                   class="form-control">
                                        </div>
                                        @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="country" class="col-form-label">Country *
                                        </label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-flag text-primary"></i>
                                            </span>
                                            <select class="form-control chzn-select" tabindex="2" name="country">
                                                @if($countries)
                                                    @foreach($countries as $country)
                                                    <option value="{{ $country->iso2_code}}">
                                                            {{ $country->country_name}}
                                                    </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="phone" class="col-form-label">Date Of Birth
                                            </label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar text-primary"></i>
                                            </span>
                                            <input name="dob" id="datepicker" readonly
                                                   value="{{ isset($user->dob) ? ($user->dob == '0000-00-00 00:00:00' ? '' : date('m/d/Y', strtotime($user->dob))) : old('dob') }}" 
                                                   class="form-control">
                                        </div>
                                        @if ($errors->has('dob'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
								<div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="level_proof" class="col-form-label">Upload Level Proof</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <input id="input-22" name="level_proof" type="file" class="file-loading">
                                            @if(isset($user->level_proof) && Storage::exists('uploads/files/level_proof/' . $user->level_proof))
                                                <a href="{{ asset('storage/app/uploads/files/level_proof/' . $user->level_proof) }}" target="_blank">Download Level Proof</a>
                                            @endif
                                        </div>
                                        @if ($errors->has('level_proof'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('level_proof') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group gender_message row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label class="col-form-label">Select Access Level</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" name="access_level"
                                                       class="custom-control-input" value="1"
                                                       {{ (isset($user->access_level) ? $user->access_level : old('access_level')) == 1 ? 'checked' : ''}}>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Level 1</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" name="access_level"
                                                       class="custom-control-input" value="2"
                                                       {{ (isset($user->access_level) ? $user->access_level : old('access_level')) == 2 ? 'checked' : ''}}>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Level 2</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" name="access_level"
                                                       class="custom-control-input" value="3"
                                                       {{ (isset($user->access_level) ? $user->access_level : old('access_level')) == 3 ? 'checked' : ''}}>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Level 3</span>
                                            </label>
                                        </div>
                                        @if ($errors->has('access_level'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('access_level') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-9 push-lg-3">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-user"></i>
                                            {{ isset($user->id) ? 'Edit User' : 'Add User' }}
                                        </button>
                                        @if(!isset($user->id))
                                        <button class="btn btn-warning" type="reset" id="clear">
                                            <i class="fa fa-refresh"></i>
                                            Reset
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('pagescript')
    <script src="{{URL::asset('/public/admn/js/pluginjs/jasny-bootstrap.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/holder.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/bootstrapValidator.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!--<script src="{{URL::asset('/public/admn/js/pages/validation.js')}}"></script>-->
	<script>
	$( function() {
            $( "#datepicker" ).datepicker({  maxDate: 0 });
        });
        $(document).on('keydown', '.pwd', function (e) {
            if (e.keyCode == 32)
                return false;
        });

    </script>
    @stop