@extends('admin.layout.app')
@section('title', 'Add Categories')
@section('css')
<link href="{{URL::asset('/public/admn/css/plugincss/jasny-bootstrap.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('/public/admn/css/bootstrapValidator.min.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="content" class="bg-container">
    <header class="head">
        <div class="main-bar">
            <div class="row no-gutters">
                <div class="col-6">
                    <h4 class="m-t-5">
                        <i class="fa fa-bars"></i>
                        {{ isset($category->id) ? 'Edit Category' : 'Add Categories' }}
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
                        <h4>Category Information</h4>
                    </div>
                    <form class="form-horizontal login_validator" id="tryitForm"
                          enctype="multipart/form-data" action="{{ isset($category->id) ? url('/admin/edit_category/' . $category->id) : url('/admin/add_category') }}"
                          method="post">
                        <div class="row">
                            <div class="col-12">
                                {{ csrf_field() }}
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-center text-lg-right">
                                        <label class="col-form-label">Category Image</label>
                                    </div>
                                    <div class="col-lg-6 text-center text-lg-left">
                                        <div class="fileinput fileinput-new" 
                                             data-provides="fileinput">
                                            <div class="fileinput-new img-thumbnail text-center">
                                                @if (isset($category->cat_image))
                                                <img src="{{url('public/images/categories/original/' . $category->cat_image)}}"></div>
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
                                                <input type="file" name="cat_image"></span>
                                            <a href="#" class="btn btn-warning fileinput-exists"
                                               data-dismiss="fileinput">Remove</a>
                                        </div>
										@if ($errors->has('cat_image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('cat_image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row m-t-25">
                                <div class="col-lg-3 text-lg-right">
                                    <label for="u-name" class="col-form-label">
                                        Category Name *</label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <input type="text" name="name"
                                               class="form-control" value="{{ isset($category->name) ? $category->name : old('name') }}">
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
                                    <label for="u-name" class="col-form-label">Description </label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="input-group">
                                        <textarea class="form-control" name="cat_desc"
                                                  cols="50" rows="5">{{ isset($category->cat_desc) ? $category->cat_desc : old('cat_desc') }}</textarea>                                            
                                    </div>
                                    @if ($errors->has('cat_desc'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cat_desc') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row m-t-25">
                                <div class="col-lg-3 text-lg-right">
                                    <label for="u-name" class="col-form-label">Feature </label>
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <label class="custom-control custom-checkbox error_color">
                                        <input type="checkbox" class="custom-control-input" name="feature" value="1" {{ (isset($category->feature) ? $category['feature'] : old('feature')) == 1 ? 'checked' : '' }}>
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-9 push-lg-3">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-user"></i>
                                        {{ isset($category->id) ? 'Edit Category' : 'Add Category' }}
                                    </button>
                                    @if(!isset($category->id))
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
<script src="{{URL::asset('/public/admn/js/pages/validation.js')}}"></script>
@stop