@extends('admin.layout.app')
@section('title', 'Add Course')
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
                        {{ isset($course->id) ? 'Edit Course' : 'Add Course' }}
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
                <form class="form-horizontal login_validator" id="tryitForm"
                          enctype="multipart/form-data" action="{{ isset($course->id) ? url('/admin/edit_counse/' . $course->id) : url('/admin/add_course') }}"
                          method="post">
                <div class="card-block m-t-35">
                    <div>
                        <h4>Page Information</h4>
                    </div>
                    
                        <div class="row">
                            <div class="col-12">
                                {{ csrf_field() }}
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="category" class="col-form-label">
                                            Category *</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <select class="form-control chzn-select" tabindex="2" name="category" required>
                                                <option value="">Choose a Category</option>
                                                @if($categories)
                                                @foreach($categories as $id => $category)
                                                <option value="{{ $id }}"
                                                        {{ (isset($course->id) ? $course['cat_id'] : old('category')) == $id ? 'selected' : '' }}>
                                                    {{ $category }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @if ($errors->has('category'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="u-name" class="col-form-label">
                                            Course Title *</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <input type="text" name="title"
                                                   class="form-control" value="{{ isset($course->title) ? $course->title : old('title') }}">
                                        </div>
                                        @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-center text-lg-right">
                                        <label class="col-form-label">Upload Video</label>
                                    </div>
                                    <div class="col-lg-3 text-center text-lg-left">
                                        <div class="fileinput fileinput-new" 
                                             data-provides="fileinput">
                                            <div class="fileinput-new img-thumbnail text-center">
                                            
                                            <img src="" 
                                                 data-src="holder.js/100%x100%"
                                                 alt="not found"></div>
                                        
                                        <div class="fileinput-preview fileinput-exists img-thumbnail"></div>
                                        <div class="m-t-20 text-center">
                                            <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new">Select Video</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="video_name" accept="video/mp4,video/x-m4v,video/*"></span>
                                            <a href="#" class="btn btn-warning fileinput-exists"
                                               data-dismiss="fileinput">Remove</a>
                                        </div>
                                        @if ($errors->has('video'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('video_name') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-3 text-center text-lg-left">
                                        @if(isset($course->id) && Storage::exists('uploads/files/course_videos/' . $course->video_name))
                                        <video width="320" height="240" controls>
                                            <source src="{{ asset('storage/app/uploads/files/course_videos/' . $course->video_name) }}">
                                            Your browser does not support the video tag.
                                        </video>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="description" class="col-form-label">Description</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <textarea class="editor" name="description" 
                                                  cols="50" rows="6">{{ isset($course->description) ? $course->description : old('description') }}</textarea>                                             
                                        </div>
                                        @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="upload_pdf" class="col-form-label">Upload PDF File</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <input id="input-22" name="file_name" type="file" class="file-loading" accept="application/pdf">
                                            @if(isset($course->id) && Storage::exists('uploads/files/course_pdf/' . $course->file_name))
                                                <a href="{{ asset('storage/app/uploads/files/course_pdf/' . $course->file_name) }}">Download PDF</a>
                                            @endif
                                        </div>
                                        @if ($errors->has('file_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('file_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group gender_message row">
                                    <div class="col-lg-3 text-lg-right">
                                        <label class="col-form-label">Select Level</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" name="level"
                                                       class="custom-control-input" value="1"
                                                       {{ (isset($course->level) ? $course->level : old('level')) == 1 ? 'checked' : ''}}>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Level 1</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" name="level"
                                                       class="custom-control-input" value="2"
                                                       {{ (isset($course->level) ? $course->level : old('level')) == 2 ? 'checked' : ''}}>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Level 2</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" name="level"
                                                       class="custom-control-input" value="3"
                                                       {{ (isset($course->level) ? $course->level : old('level')) == 3 ? 'checked' : ''}}>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Level 3</span>
                                            </label>
                                        </div>
                                        @if ($errors->has('level'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('level') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
								
								<div class="form-group row m-t-25">
                                    <div class="col-lg-3 text-lg-right">
                                        <label for="upload_pdf" class="col-form-label">Upload Thumbnail</label>
                                    </div>
                                    <div class="col-xl-6 col-lg-8">
                                        <div class="input-group">
                                            <input id="input-22" name="thumbnail" type="file" class="file-loading" >
                                            @if(isset($course->id) &&  public_path('/images/blog/'))
                                                <img src="{{ asset('public/images/blog/' . $course->thumbnail) }}" height="100" width="100">
											@endif
                                        </div>
                                        @if ($errors->has('thumbnail'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('thumbnail') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
								
                                <div class="form-group row">
                                    <div class="col-lg-9 push-lg-3">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-user"></i>
                                            {{ isset($course->id) ? 'Edit' : 'Add' }}
                                        </button>
                                        <a class="btn btn-warning" href="{{ url('admin/course_list') }}">
                                            <i class="fa fa-arrow-left"></i>
                                            Back
                                        </a>
                                        
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
<script src='https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.11.3/ckeditor.js'></script>
<script>
// Normal ckEditor example code
var elements = CKEDITOR.document.find( '.editor' ),
    i = 0,
    element;
while (( element = elements.getItem( i++ ) )) {
    CKEDITOR.replace( element );
}
</script>
<script src="{{URL::asset('/public/admn/js/pluginjs/jasny-bootstrap.js')}}"></script>
<script src="{{URL::asset('/public/admn/js/holder.js')}}"></script>
@stop