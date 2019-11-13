<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
	
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Academy</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link href="{{asset('public/sites/css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/sites/css/owl.theme.default.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/sites/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('public/sites/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('public/sites/css/jquery-ui.min.css')}}">
    <link type="text/css" href="{{asset('public/sites/css/price_range_style.css')}}">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    @yield('css')
  </head>
   <body>
  	<div class="top_header">	
  		<div class="container">	
	  		<div class="row">	
	  			<div class="col-md-6">	
					<a href="{{ url('/') }}"><img src="{{ asset('public/sites/images/logo.png') }}"></a>
	  			</div>
	  			<div class="col-md-6">
					@if (Auth::guest())
						<ul class="pull-right">	
							<li><a href="{{ url('/login') }}">LOGIN</a></li>
							<li><a href="{{ url('/register') }}">SIGNUP</a></li>
						</ul>
					@else
						<div class="dropdown profile-list">
							<button class="profile-btn dropdown-toggle" type="button" data-toggle="dropdown">Welcome {{ Auth::user()->username }}
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="{{ route('edit_profile') }}">My Profile</a></li>
								<li>
									<a href="{{ route('logout') }}" onclick="event.preventDefault();
																document.getElementById('logout-form').submit();"> Logout</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
												{{ csrf_field() }}
										</form>
								</li>
							</ul>
						</div>
					@endif
	  			</div>
	  		

	  		<div class="navbar_menu">
	  			
	  			<ul class="pull-left menu">	
	  				<li><a href="{{ url('/') }}">HOME</a></li>
	  				<li><a href="{{ url('/study_program') }}">STUDY PROGRAM</a></li>
	  				<li><a href="{{ url('/blog') }}">BLOG</a></li>
	  			</ul>
	  		
	  			
	  			<ul class="pull-right social_media">
	  				<li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
	  				<li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
	  				<li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
	  			</ul>
	  		
	  		</div>
	  		</div>
  		</div>
  	</div>
       @yield('content')
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="foot_1">
						<p>Duis semper mauris vitae purus rhoncus suscipit. Nunc dictum dapibus tellus, at viverra risus pharetra id. Nulla facilisi. Ut mollis et augue non gravida.</p>
						<p> contact xxx@xxx.com for support</p>
					</div>
				</div>
				<div class="col-md-6 ">
					<div class="foot_2 pull-right">
						<img src="{{ asset('public/sites/images/logo.png') }}">
					</div>
				</div>
			</div>
		</div>
	</div>
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript" src="{{asset('public/sites/js/price_range_script.js')}}"></script>
  @yield('pagescript')
  <script src="{{asset('public/sites/js/owl.carousel.min.js')}}"></script> 
  <script type="text/javascript">
  	$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
})
  </script>
  </body>
</html>
