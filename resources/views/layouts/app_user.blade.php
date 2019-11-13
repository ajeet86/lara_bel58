<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <link rel="stylesheet" href="{{ asset('public/css/app.css') }}"> -->

        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <!-- FontAwesome CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
        <link rel="icon" href="{{ asset('public/sites/images/favicon.ico') }}" type="image/ico" sizes="16x16">

        <!-- Custom styles  -->
        <link href="{{asset('public/sites/css/custom.css')}}" rel="stylesheet">
        <link href="{{asset('public/sites/css/responsive.css')}}" rel="stylesheet">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        @yield('css')

        <title>{{config('app.name')}}</title>
    </head>
    <body>

        <!--main-header-->
        <div class="top-header dashboard-top">
            <div class="container-fluid"> 
                <div class="navigation-main">
                    <div class="">
                        <div class="fix-sc" data-toggle="sticky-onscroll">
                            <nav class="cust-nav navbar header-top navbar-expand-lg navbar-dark">
                                <a href="{{  url('/') }}" class="logo-dash"> <img src="{{ asset('public/sites/images/logo.png') }}">  </a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarText">
                                    <ul class="navbar-nav dash-list">
                                        <li class="nav-item">
                                            <a class="nav-link" href="" onClick="slideDownwindow('home')">
                                                <div class="top-mainn-cls dash-search">
                                                    <input type="text" class="search-text" placeholder="" name="">
                                                    <button type="button" class="search-buton"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                </div>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" href="" onclick="slideDownwindow('about')">SELL</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="" onclick="slideDownwindow('reifen')">APAREL</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="" onclick="slideDownwindow('anhanger')">WHO WE ARE</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="" onclick="slideDownwindow('kfz')">FAQ</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="" onclick="slideDownwindow('kontakt')">SEARCH</a>
                                        </li> -->
                                    </ul>
                                    <div class="right-text dash-b-right user-info-details-list">
                                        <ul>
                                            <!--<li><a href="#"><i class="fa fa-shopping-bag bags" aria-hidden="true"></i> my bundles </a></li>
                                            <li><a href="#"><i class="fa fa-heart-o"></i> my likes </a></li>
                                            <li><a href="#"><i class="fa fa-bell-o"></i> news </a></li>-->
                                            <li> <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button"> <span class="user-icon"> {{ substr(Auth::user()->name, 0, 1) }} </span>
                                                        <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="{{route('edit_profile')}}">Edit Profile</a></li>
                                                        <!--<li><a href="#">CSS</a></li>
                                                        <li><a href="#">my recentrly viewed</a></li>
                                                        <li><a href="#">my size </a></li>
                                                        <li><a href="#">my bundles </a></li>
                                                        <li><a href="#">my likes</a></li>-->
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
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <section class="dashboard-body product-wrapper">

        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-3">
                    <aside>

                        <h3> {{ Auth::user()->name }} </h3>
                        <ul>
                            <li> 
                                <a href="{{route('edit_profile')}}"><i class="fa fa-shopping-bag" aria-hidden="true">
                                    </i> Edit Profile </a>
                            </li>    
                            <li>
                                <a href="{{ route('add_book') }}">
                                    <i class="fa fa-dollar" aria-hidden="true"></i> Sell Books 
                                </a>
                            </li> 
                            <li> 
                                <a href="{{ route('my_book_list') }}">
                                    <i class="fa fa-folder-open" aria-hidden="true"></i> My Books
                                </a> 
                            </li>
                            <li> 
                                <a href="{{ url('orders') }}">
                                    <i class="fa fa-gift" aria-hidden="true"></i> My Orders 
                                </a> 
                            </li>
                            <li> 
                                <a href="{{ url('my_sells') }}">
                                    <i class="fa fa-money" aria-hidden="true"></i> My Sells 
                                </a> 
                            </li>
                            <li> 
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off" aria-hidden="true"></i> Logout </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>

                        </ul>

                    </aside>
                </div>


                @yield('content')

            </div>

        </div>


    </section>




    <!-- footer section starts-->
    <section class="kontact" id="contact"> 
        <div class="container-fluid">
            <div class="kfz-servics-main">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="kontact-icons">
                            <img src="{{ asset('public/sites/images/logo.png') }}">

                        </div>
                        <div class="social-icons">
                            <i class="fa fa-facebook-f"></i>
                            <i class="fa fa-twitter"></i>
                            <i class="fa fa-google-plus-square"></i>
                            <i class="fa fa-rss"></i>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="manues">

                            <ul>
                                <li><a>Search</a></li>
                                <li><a>Buy</a></li>
                                <li><a>FAQ</a></li>
                                <li><a>Contact Us</a></li>
                                <li><a>Sell</a></li>
                            </ul>

                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="text-cls">   <h6>Search for your books here first!</h6>
                            <p>Here at School Shark we work hard to make sure all of our listings are up to date and ready for you to buy to get you the best deal possible. Take advantage of that! </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="form-sec"> <p>Sign up to our email list for only important updates and deals! (You hate spam? So do we!)</p>
                            <div class="form-vbxcv"><input type="text" class="email-text" placeholder="Email address">
                                <button type="button" class="subs-buton">Subscribe</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>
    <!-- end of footer section-->
    <!-- copyright section starts-->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <p> © 2019, School Shark Powered by Shopify </p>
                </div>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="iconddf-img">
                        <img src="{{ asset('public/sites/images/amex.png') }}" >
                        <img src="{{ asset('public/sites/images/black-pay.png') }}">
                        <img src="{{ asset('public/sites/images/sdvd.png') }}">
                        <img src="{{ asset('public/sites/images/yeloww.png') }}">
                        <img src="{{ asset('public/sites/images/colg.png') }}">
                        <img src="{{ asset('public/sites/images/circ.png') }}">
                        <img src="{{ asset('public/sites/images/payy.png') }}">
                        <img src="{{ asset('public/sites/images/pay.png') }}">
                        <img src="{{ asset('public/sites/images/vs.png') }}">
                        <img src="{{ asset('public/sites/images/visa.png') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- copyright section ends-->



<!-- Bootstrap core JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

<script src="{{URL::asset('/public/sites/js/custom.js')}}"></script>



<script type="text/javascript">


</script>



<script>
    $(document).ready(function () {

    });
</script>
@yield('pagescript')

</body>

</html>
