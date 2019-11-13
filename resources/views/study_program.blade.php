@extends('layouts.app')
@section('title', 'Login')
@section('description', '')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="video_section text-center">
                <!--<iframe width="1099" height="545" src="https://www.youtube.com/embed/tgbNymZ7vqY">
                </iframe>-->
				
				<video width="900" height="500" controls autoplay>
  <source src="http://3.132.139.12/storage/app/uploads/files/course_videos/20191104_5dbfcb56a3668.mp4" type="video/mp4">
</video>


            </div>	
        </div>
    </div>
</div>

<div class="level_section"> 
    <div class="container text-center"> 
        <div class="row"> 
            <div class="col-md-4">
                <div class="level_1"> 
                    <i class="fa fa-user-o" aria-hidden="true"></i>
                    <h2>Level - 1</h2>
                    <p>Available to access to all who register</p>
                    <a href="{{ url('/register') }}" class="banner_button">Register Now</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="level_1"> 
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <h2>Level - 2</h2>
                    <p>In order to gain access to level 2 must register at betsala.com; in order to verify this 
                        customer must email or submit a form to us stating their username registered with
                        the other (betsala) website</p>
                    <a href="{{ url('/register') }}" class="banner_button">Register Now</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="level_1"> 
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h2>Level - 3</h2>
                    <p>In order to gain access to level 3 must deposit as bestsala.com; cutomer will need to send a confirmation th
                        that he has made a deposit, once we confirm acces will then be provided t
                        to level 3</p>
                    <a href="{{ url('/register') }}" class="banner_button">Register Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection