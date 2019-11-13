@extends('layouts.app')

@section('content')
<section class="login-wrapper">
<div class="container">
    <div class="row justify-content-center login">
        <div class="col-md-8 col-md-offset-2">
            <div class="card login-form register-form">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <br/>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-6 col-form-label text-md-right">{{ __('messages.Name') }} *</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}"  autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
                        <div class="form-group row">
                            <label for="surname" class="col-md-6 col-form-label text-md-right">{{ __('messages.Surname') }} *</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}"  autofocus>

                                @if ($errors->has('surname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-6 col-form-label text-md-right">{{ __('E-Mail') }} *</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" >

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>	
						
                        <div class="form-group row">
                            <label for="dob" class="col-md-6 col-form-label text-md-right">{{ __('messages.Date Of Birth') }} *</label>

                            <div class="col-md-6">
                                <input id="datepicker" type="text" 
                                       class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}"
                                       name="dob"
                                       readonly="">

                                @if ($errors->has('dob'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>		
						
                        <div class="form-group row">
                            <label for="country" class="col-md-6 col-form-label text-md-right">{{ __('messages.Country') }} *</label>

                            <div class="col-md-6">
                                <select name="country" class="countries">
                                    @foreach($countries as $country)
                                        <option value="{{ $country->iso2_code}}"
                                            {{ isset($user->id) ? ($user->country) == $country->iso2_code ? 'selected' : '' : '' }}>
                                            {{ $country->country_name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('country'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="telephone" class="col-md-6 col-form-label text-md-right">{{ __('messages.Telephone') }} *</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" 
                                       class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" 
                                       name="phone" value="{{ old('phone') }}" >

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-6 col-form-label text-md-right">{{ __('messages.Username') }} *</label>

                            <div class="col-md-6">
                                <input id="nick" type="text" 
                                       class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" 
                                       value="{{ old('username') }}"
                                       name="username" >

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-6 col-form-label text-md-right">{{ __('Password') }} *</label>

                            <div class="col-md-6">
                                <input id="password" type="password" 
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                                       name="password" >

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary all-btn-theme">
                                    {{ __('Register') }}
                                </button>
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
<script>
    $( function() {
        $( "#datepicker" ).datepicker();
    });
</script>
@stop
