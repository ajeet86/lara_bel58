@extends('admin.layout.app')
@section('title', 'Tag')
@section('css')
    <link href="{{URL::asset('/public/admn/css/plugincss/jasny-bootstrap.min.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="content" class="bg-container">
    <header class="head">
        <div class="main-bar">
            <div class="row no-gutters">
                <div class="col-6">
                    <h4 class="m-t-5">
                        <i class="fa fa-plus-square"></i>
                        {{ isset($tag->id) ? 'Edit Tag' : 'Add Tag' }}
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
                        <h4>Page Information</h4>
                    </div>
                    <form class="form-horizontal login_validator" id="tryitForm"
                          enctype="multipart/form-data" action="{{ isset($tag->id) ? url('/admin/edit_tag/' . $tag->id) : url('/admin/add_tag') }}"
                          method="post">
                        <div class="row">
                            <div class="col-12">
                                {{ csrf_field() }}
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="u-name" class="col-form-label">
                                            Tag Name *</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <input type="text" name="name"
                                                   class="form-control" value="{{ isset($tag->name) ? $tag->name : old('name') }}">
                                        </div>
                                        @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-9 push-lg-3">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-user"></i>
                                            {{ isset($tag->id) ? 'Edit Tag' : 'Add Tag' }}
                                        </button>
                                    </div>
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
@stop