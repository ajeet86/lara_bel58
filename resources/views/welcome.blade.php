@extends('layouts.app')
@section('title', '')
@section('description', '')
@section('content')
<div class="slider_banner">
    <div class="owl-carousel owl-theme">
        <div class="item"><img src="{{ asset('public/sites/images/banner.jpg') }}"></div>
        <div class="item"><img src="{{ asset('public/sites/images/1.jpg') }}"></div>
    </div>
    <div class="banner_content">
            <p>YOU ONLY NEED TO KNOW ONE THING</p>
            <h1>BEST ONLINE LEARNING SYSTEM</h1>
            <a href="http://3.132.139.12/study_program"><button class="button btn-circle"><i class="fa fa-play" aria-hidden="true"></i></button></a>
			@if (Auth::guest())
				<div class="banner_btn">
                                    <button class="banner_button" onclick="window.location.href='{{ url('/register') }}'">Register Now</button>
				</div>
			@endif
    </div>
</div>
<div class="box">
    <div class="container">
            <div class="gray_box">
                    <ul>
                            <li>Sections <span>36 </span></li>
                            <li>Students  <span>135 </span></li>
                            <li>Articles <span>29</span></li>
                    </ul>

                    </div>
            </div>
</div>
<div class="welcome_section">
    <div class="container">
            <h4>I N T R O D U C T I O N</h4>
            <h2>Welcome </h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rhoncus elit aliquam facilisis pharetra. Aenean consectetur lacinia diam a tincidunt. Vivamus facilisis lacus non velit suscipit pellentesque. Nam ac mauris nec mi lobortis sollicitudin sed at lacus.</p>
            <div class="row">
                    <div class="col-md-4">
                            <div class="w_box1">
                                    <img src="{{ asset('public/sites/images/img1.jpg') }}">
                                    <div class="img_content">
                                            <i class="fa fa-laptop" aria-hidden="true"></i>
                                            <h3>Learn Online</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu nisl</p>
                                            <!--<button class="img_btn">Read More</button>-->
                                    </div>
                            </div>
                    </div>
                    <div class="col-md-4">
                            <div class="w_box1">
                                    <img src="{{ asset('public/sites/images/img1.jpg') }}">
                                    <div class="img_content">
                                            <i class="fa fa-youtube" aria-hidden="true"></i>
                                            <h3>Online videos</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu nisl</p>
                                            <!--<button class="img_btn">Read More</button>-->
                                    </div>
                            </div>
                    </div>
                    <div class="col-md-4">
                            <div class="w_box1">
                                    <img src="{{ asset('public/sites/images/img1.jpg') }}">
                                    <div class="img_content">
                                            <i class="fa fa-gift" aria-hidden="true"></i>
                                            <h3>Blog Community</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu nisl</p>
                                            <!--<button class="img_btn">Read More</button>-->
                                    </div>
                            </div>
                    </div>
            </div>
    </div>
</div>
<!-- end of service section-->
@endsection