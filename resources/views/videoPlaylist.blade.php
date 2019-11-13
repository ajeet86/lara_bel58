@extends('layouts.appNew')
@section('title', 'Login')
@section('description', '')
@section('content')
<div class="container">
    
	
	<div class="rvs-container">

	<div class="rvs-item-container">
		<div class="rvs-item-stage">
@foreach($courses as $course)
   

			<!-- The below is a single item, simply duplicate this layout for each item -->
			<div class="rvs-item" style="background-image: url({{asset('public/sites/videoplayerlist/imd.png')}})">
				<p class="rvs-item-text"> {{$course->title }}<small> <a class="" style="position: relative!important; z-index: 10!important;" href="{{asset('storage/app/uploads/files/course_pdf/')}}/{{ $course->file_name }}" target="_blank">Download PDF</a></small></p>
				
				<a  href="{{asset('storage/app/uploads/files/course_videos/')}}/{{ $course->video_name }}" class="rvs-play-video" ></a>
				
			</div>
			
		@endforeach	
			
		</div>
	</div>

	<div class="rvs-nav-container">
		<a class="rvs-nav-prev"></a>
		<div class="rvs-nav-stage">

			@foreach($courses as $course)
			<a class="rvs-nav-item">
				<span class="rvs-nav-item-thumb" style="background-image: url({{asset('public/sites/videoplayerlist/imd.png')}})"></span>
				<h4 class="rvs-nav-item-title"> {{$course->title }}</h4>
				<!--<small class="rvs-nav-item-credits">Download VIDEO_CREDITS</small>-->
			</a>
			
			<!--<a class="" href="{{asset('storage/app/uploads/files/course_pdf/')}}/{{ $course->file_name }}" target="_blank">Download PDF</a>-->
            @endforeach	
		</div>
		<a class="rvs-nav-next"></a>
	</div>

</div>
	
	
	
	
</div>


@endsection