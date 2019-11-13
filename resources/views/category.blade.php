@extends('layouts.appcat')
@section('title', 'Contact Us')
@section('description', 'Do you have any questions? Please do not hesitate to contact us directly. Our team will come back to you within a matter of hours to help you.')
@section('content')


<div class="welcome_section">
		<div class="container" >
    <div class="row">
        <div class="col-xs-12">
        	<div class="vendors-wrapper">
                <ul class="vendors">
				@foreach($categories as $category)
			<li id="busi"><button class="btn btn-xs btn-primary product-button" id="{{ $category->id }}">{{ $category->name }} </button></li>
			@endforeach	
                </ul>
            </div>
            <div class="product-carousel" id="ajax_result">
			@foreach($courses as $course)
                <div class="item filter-busi">
                    <img class="product-selector" src="{{asset('public/images/blog/')}}/{{ $course->thumbnail }}"/>
                    <div class="product-detail">
                    <div class="course-title-">{{ $course->title }}</div>
                    <!--<span class="instructor-titles">{{ $course->description }}</span>-->
                    <span class="star-rating" style="width: 100%;"></span>
                    <div class="price-c"><span class="org-price"> 8,000 </span> 500 </div>
                	</div>
                </div>
            @endforeach	
            </div>          
        </div>
    </div>
</div>
	</div>

	<div class="pros-section">
		<div class="container">
			<div class="row">
			<div class="col-md-4">
				<div class="pros-block">
					<div class="value-props-img"><img alt="" width="42" height="42" class="" src="images/go_own_pace.svg"></div>
					<div class="fx"><h4 class="unit-title">Go at your own pace</h4><p class="unit-desc">Enjoy lifetime access to courses on Udemy’s website and app</p></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="pros-block">
					<div class="value-props-img"><img alt="" width="42" height="42" class="" src="images/learn_from_experts.svg"></div>
					<div class="fx"><h4 class="unit-title">Go at your own pace</h4><p class="unit-desc">Enjoy lifetime access to courses on Udemy’s website and app</p></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="pros-block">
					<div class="value-props-img"><img alt="" width="42" height="42" class="" src="images/video_courses.svg"></div>
					<div class="fx"><h4 class="unit-title">Go at your own pace</h4><p class="unit-desc">Enjoy lifetime access to courses on Udemy’s website and app</p></div>
				</div>
			</div>
		</div>
		</div>
	</div>


    <!-- most reated product -->

    <div class="most-related-p">
        <div class="container">
        <div id="owl-carousel_5" class="owl-carousel owl-theme owl-loaded owl-drag">
          <div class="owl-stage-outer"><div class="owl-stage">
		  @foreach($courses as $course)
            <div class="owl-item">
			
			<div class="item">
              <div class="most_rel_pro">
                    <img class="product-selector" src="{{asset('public/images/blog/')}}/{{ $course->thumbnail }}" />
                    <div class="product-detail">
                    <div class="course-title-">{{ $course->title }}</div>
                    <span class="instructor-titles">{{ $course->description }}</span>
                    <span class="star-rating" style="width: 100%;"></span>
                    <div class="price-c"> 500 </div>
                    </div>
                </div>
            </div>
			
			</div>
            @endforeach	
			
			</div></div>
        </div>
    </div>
</div>


<div class="ger-person">
    <div class="container">
        <div class="ger-personlized">
            <h4> Get personalized recommendations </h4>
            <p> Answer a few questions for your top picks </p>
            <div class="get-per" ><button type="button" class="btn btn-primary">Get started</button></div>
        </div>
    </div>
</div>

@endsection