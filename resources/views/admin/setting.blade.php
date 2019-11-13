@extends('admin.layout.app')
@section('title','Settings')
@section('css')

<!--<link type="text/css" rel="stylesheet" href="{{URL::asset('/public/admn/css/validationEngine.jquery.css')}}" />
<link type="text/css" rel="stylesheet" href="{{URL::asset('/public/admn/css/bootstrapValidator.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{URL::asset('/public/admn/css/pages/form_validations.css')}}" />-->
@stop
@section('content')
<div id="content" class="bg-container">
    <header class="head">
        <div class="main-bar">
            <div class="row no-gutters">
                <div class="col-6">
                    <h4 class="m-t-5">
                        <i class="fa fa-home"></i>
                        Profile
                    </h4>
                </div>
            </div>
        </div>
    </header>
    @if(session()->has('message'))
    <div class="col-md-12">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
    @endif
    <div class="outer">
        <div class="inner bg-container">
            <!--top section widgets-->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <i class="fa fa-file-text-o"></i>
                            Settings
                        </div>
                        <div class="card-block m-t-35">
                            <form class="form-horizontal  login_validator" 
                                  id="form_block_validator" 
                                  action="{{url('/admin/settings')}}"
                                  enctype="multipart/form-data" method="post">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-lg-4  text-lg-right">
                                        <label for="required2" class="col-form-label">Name *</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" id="required2" 
                                               name="name" class="form-control" 
                                               value="{{ ($data->name != '') ? $data->name : ''}}" required>
                                        @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4  text-lg-right">
                                        <label for="required2" class="col-form-label">Username *</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" id="required2" 
                                               name="username" class="form-control" 
                                               value="{{ ($data->username != '') ? $data->username : ''}}" required>
                                        @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4 text-lg-right">
                                        <label for="email2" class="col-form-label">E-mail *</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="email" id="email2" 
                                               name="email" class="form-control"
                                               value="{{ ($data->email != '') ? $data->email : ''}}" required>
                                        @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4 text-lg-right">
                                        <label for="url2" class="col-form-label">Avatar</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="file" id="url2" name="avatar" class="form-control">
                                    </div>
                                    <div class="col-lg-4 text-lg-left">
                                        @if(file_exists(public_path().'/images/users/' . $data->avatar))
                                            <img src="{{url('public/images/users/' . $data->avatar)}}" height="30px" width="30px"
                                             alt="avatar">
                                        @else
                                            <img src="{{url('public/images/users/default.png')}}" height="30px" width="30px"
                                             alt="avatar">
                                        @endif
                                        <input type="hidden" class="form-control"
                                               name="hd_avatar" value="{{ ($data->avatar != '') ? $data->avatar : ''}}"/></div>
                                </div>
                                <div class="form-actions form-group row">
                                    <div class="col-lg-4 push-lg-4">
                                        <input type="submit" value="Save" class="btn btn-primary">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div
        </div>
    </div>


    @endsection
    @section('pagescript')
    <!--<script src="{{URL::asset('/public/admn/js/jquery.validationEngine.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/jquery.validationEngine-en.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/jquery.validate.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/jquery.validate.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/bootstrapValidator.min.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/form.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/pages/form_validation.js')}}"></script>-->
    @stop