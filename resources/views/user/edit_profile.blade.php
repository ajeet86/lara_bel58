@extends('layouts.app')
@section('title', 'Edit Profile')
@section('description', 'Edit Profile')
@section('css')
<link href="{{URL::asset('/public/admn/css/plugincss/jasny-bootstrap.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('/public/admn/css/bootstrapValidator.min.css')}}" rel="stylesheet">
@stop
@section('content')
<section class="dashboard-body product-wrapper">
    <div class="container">
        <div class="dash-container row">
            <div class="row">
                    @if(session()->has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    </div>
                    @endif
                    @if(session()->has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    </div>
                    @endif
            </div>
            <div class="abc">
                <div class="personal">
                    <div class="card-block m-t-35">
                        <div class="heading-all">
                            <h4>Datos personales</h4>
                            <hr class="divider-hr">
                        </div>
                        <form class="form-horizontal login_validator  col-md-8 col-md-offset-2" id="tryitForm"
                              enctype="multipart/form-data" action="{{ url('/edit_profile') }}"
                              method="post">
                        {{ csrf_field() }}
                            <div class="form-group row m-t-25">
                                <div class="col-lg-3">
                                    <label class="col-form-label">Avatar</label>
                                </div>
                                <div class="col-lg-6">
                                    <div class="fileinput fileinput-new" 
                                         data-provides="fileinput">
                                        <div class="fileinput-new img-thumbnail text-center">
                                            @if (isset($user->avatar))
                                                <img src="{{url('public/images/users/' . $user->avatar)}}" width="200" height="200">
                                            @else
                                                <img src="" data-src="holder.js/100%x100%" alt="not found">
                                            @endif
                                        </div>
                                        <div class="fileinput-preview fileinput-exists img-thumbnail"></div>
                                        <div class="m-t-20 text-center">
                                            <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new">Select image</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="avatar"></span>
                                            <a href="#" class="btn btn-warning fileinput-exists"
                                               data-dismiss="fileinput">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row m-t-25">
                                <div class="col-lg-3 text-lg-right">
                                    <label for="u-name" class="col-form-label">
                                        {{ __('messages.Name') }} *</label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <input type="text" name="name"
                                               class="form-control" value="{{ isset($user->name) ? $user->name : old('name') }}" required="">
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
                                        {{ __('messages.Surname') }} *</label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <input type="text" name="surname"
                                               class="form-control" 
                                               value="{{ isset($user->surname) ? $user->surname : old('surname') }}"
                                               required>
                                    </div>
                                    @if ($errors->has('surname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-3 text-lg-right">
                                    <label for="email" class="col-form-label">{{ __('E-Mail') }}
                                        *</label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <input type="email" value="{{ isset($user->email) ? $user->email : old('email') }}" name="email"
                                               class="form-control" required>
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
                                    <label for="dob" class="col-form-label">{{ __('messages.Date Of Birth') }}
                                        *</label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <input type="text" value="{{ isset($user->dob) ? \Carbon\Carbon::parse($user->dob)->format('m/d/Y') : old('dob') }}" name="dob" id="datepicker"
                                               class="form-control" required readonly="">
                                    </div>
                                    @if ($errors->has('dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-3 text-lg-right">
                                    <label for="country" class="col-form-label">{{ __('messages.Country') }} *
                                    </label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <select name="country" class="countries" required="">
                                            @foreach($countries as $country)
                                                <option value="{{ $country->iso2_code}}"
                                                    {{ isset($user->id) ? ($user->country) == $country->iso2_code ? 'selected' : '' : '' }}>
                                                    {{ $country->country_name}}</option>
                                            @endforeach
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
                                    <label for="phone" class="col-form-label">{{ __('messages.Telephone') }} *
                                    </label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <input name="phone"
                                               type="number"
                                               minlength="10"
                                               maxlength="20"
                                               value="{{ isset($user->phone) ? $user->phone : old('phone') }}" 
                                               class="form-control number_validation" required="">
                                    </div>
                                    @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row m-t-25">
                                <div class="col-lg-3 text-lg-right">
                                    <label for="level_proof" class="col-form-label">{{ __('messages.Upload Level Proof') }}</label>
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
                            <div class="form-group row">
                                <div class="col-lg-3 text-lg-right">

                                </div>
                                <div class="col-xl-6 col-lg-8 text-lg-left">
                                    <button class="btn btn-primary all-btn-theme" type="submit">
                                        <i class="fa fa-user"></i>
                                        {{ __('Editar') }}
                                    </button>

                                    <a class="btn btn-warning" href="{{ url('change_password') }}">
                                        <i class="fa fa-user"></i>
                                        {{ __("messages.Change Password") }}
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('pagescript')
<script src="{{URL::asset('/public/admn/js/pluginjs/jasny-bootstrap.js')}}"></script>
<script src="{{URL::asset('/public/admn/js/holder.js')}}"></script>
<script>
    $( function() {
        $( "#datepicker" ).datepicker({  maxDate: 0 });
    });
    $(".number_validation").keydown(function(e){
            if(!((e.keyCode > 95 && e.keyCode < 106)
                  || (e.keyCode > 47 && e.keyCode < 58) 
                  || e.keyCode == 8)) {
                return false;
            }
    });
</script>
@stop