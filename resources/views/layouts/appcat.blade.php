<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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


    <!-- filter and slider script and css -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.js"></script> 

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.4.1/slick-theme.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.4.1/slick.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.4.1/slick.css">
	
	
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
						<ul class="pull-right">
                                                    <li>
                                                        <a href="{{ route('edit_profile') }}">
                                                                My Profile
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('logout') }}" 
                                                        onclick="event.preventDefault();
                                                                        document.getElementById('logout-form').submit();">
                                                                Logout
                                                        </a>

                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                {{ csrf_field() }}
                                                        </form>
                                                    </li>
						</ul>
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
 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript" src="{{asset('public/sites/js/price_range_script.js')}}"></script>
  <script src="{{asset('public/sites/js/owl.carousel.min.js')}}"></script> 
  
  
  
  @yield('pagescript')
  

  
  <script type="text/javascript">
    //banner owl
    $('#owl-carousel_1').owlCarousel({
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

// most related product owl    
$('#owl-carousel_5').owlCarousel({
    loop:true,
    margin:10,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:3,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }
    }
})
  </script>

   <!-- filter and slider script -->
<script type="text/javascript">//<![CDATA[

$(document).ready(function(){
$(".product-button").click(function(){
  var ButtonText = $(this).attr('id');
 // $(this).addClass('active');
  
 
  $(".slick-prev").css("display", "block");
  $(".slick-next").css("display", "block");
  //$('.product-button').removeClass('active');
    $(this).addClass('active');
  //$(this).attr('name');
  //alert(ButtonText);
  
  $.ajax({
              
			  url: '{{ url('ajaxcategorylist') }}',
              type: "get",
               data: { id : ButtonText },
               success: function(response){ 
              
            //alert(response); 
			
			$("#ajax_result").html(response);
			
        }
            });
  
});
});
    $(window).load(function(){
      
$(document).ready(function(){
    $('.product-carousel').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            slidesToShow: 4
          }
        },
        {
          breakpoint: 480,
          settings: {
            arrows: false,
            centerMode: true,
            centerPadding: '10px',
            slidesToShow: 1
          }
        }
      ]
    });
});

var filtered = false;

$('.product-button').on('click', function(){
    var filtername = $(this).parent('li').attr('id');
    var currentclass = $(this).attr('class');
  if (filtered === false) {
    $('.product-carousel').slick('slickUnfilter');
    $('.product-carousel').slick('slickFilter','.filter-' + filtername);
    $('.product-button').attr('class', 'btn btn-xs btn-default product-button');
    $(this).attr('class', 'btn btn-xs btn-primary product-button');
  } else {
    $('.product-carousel').slick('slickUnfilter');
    $('.product-carousel').slick('slickFilter', '.filter-' + filtername);
    $('.product-carousel').slickGoTo(0);
    $('.product-button').attr('class', 'btn btn-xs btn-default product-button');
    $(this).attr('class', 'btn btn-xs btn-primary product-button');
    filtered = false;
  }
});
				
    });

  //]]></script>
  <script>
    // tell the embed parent frame the height of the content
    if (window.parent && window.parent.parent){
      window.parent.parent.postMessage(["resultsFrame", {
        height: document.body.getBoundingClientRect().height,
        slug: "hr1155fm"
      }], "*")
    }

    // always overwrite window.name, in case users try to set it manually
    window.name = "result"
  </script>
<script type="text/javascript">
    $(function(){
    // $('.product-button').click(function(e){
        // $('.product-button').removeClass("active");
        // var clickid = $(this).parent().attr('id');
        // setTimeout(function(){ $("#"+clickid).find('.product-button').addClass('active'); }, 500);
        
    // });
});
</script>
  </body>
</html>
