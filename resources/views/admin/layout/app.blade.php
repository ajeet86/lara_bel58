<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Admin</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('public/admn/img/logo.ico') }}"/>

        <!--global styles-->
        <link type="text/css" rel="stylesheet" href="{{ asset('public/admn/css/components.css') }}" />
        <link type="text/css" rel="stylesheet" href="{{ asset('public/admn/css/custom.css') }}" />
        <!-- end of global styles-->
        <link type="text/css" rel="stylesheet" href="{{ asset('public/admn/vendors/chartist/css/chartist.min.css') }}" />
        <link type="text/css" rel="stylesheet" href="#" id="skin_change" />
        @yield('css')
    </head>

    <body class="body">
        <div class="preloader" style=" position: fixed;
             width: 100%;
             height: 100%;
             top: 0;
             left: 0;
             z-index: 100000;
             backface-visibility: hidden;
             background: #ffffff;">
            <div class="preloader_img" style="width: 200px;
                 height: 200px;
                 position: absolute;
                 left: 48%;
                 top: 48%;
                 background-position: center;
                 z-index: 999999">
                <img src="{{ asset('public/admn/img/loader.gif') }}" style=" width: 50px;" alt="loading...">
            </div>
        </div>
        <div id="wrap">
            <div id="top">
                <!-- .navbar -->
                <nav class="navbar navbar-static-top">
                    <div class="container-fluid m-0">
                        <a class="navbar-brand float-left" href="{{url('admin/dashboard')}}">
                            <h4><!--<img src="{{ asset('public/admn/img/logo.ico') }}" class="admin_img" alt="logo">--> Demo</h4>
                        </a>
                        <div class="menu">
                            <span class="toggle-left" id="menu-toggle">
                                <i class="fa fa-bars"></i>
                            </span>
                        </div>
                        <div class="topnav dropdown-menu-right float-right">
                            <!--<div class="btn-group hidden-md-up small_device_search" data-toggle="modal"
                                 data-target="#search_modal">
                                <i class="fa fa-search text-primary"></i>
                            </div>
                            <div class="btn-group">
                                <div class="notifications no-bg">
                                    <a class="btn btn-default btn-sm messages" data-toggle="dropdown" id="messages_section"> <i
                                            class="fa fa-envelope-o fa-1x"></i>
                                        <span class="badge badge-pill badge-warning notifications_badge_top">8</span>
                                    </a>
                                    <div class="dropdown-menu drop_box_align" role="menu" id="messages_dropdown">
                                        <div class="popover-title">You have 8 Messages</div>
                                        <div id="messages">
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/5.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data"><strong>hally</strong>
                                                        sent you an image.
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/8.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data"><strong>Meri</strong>
                                                        invitation for party.
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/7.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <strong>Remo</strong>
                                                        meeting details .
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/6.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <strong>David</strong>
                                                        upcoming events list.
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/5.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data"><strong>hally</strong>
                                                        sent you an image.
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/8.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data"><strong>Meri</strong>
                                                        invitation for party.
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/7.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <strong>Remo</strong>
                                                        meeting details .
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/6.jpg')}}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <strong>David</strong>
                                                        upcoming events list.
                                                        <br>
                                                        <small>add to timeline</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="popover-footer">
                                            <a href="mail_inbox.html" class="text-white">Inbox</a>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <!--<div class="btn-group">
                                <div class="notifications messages no-bg">
                                    <a class="btn btn-default btn-sm" data-toggle="dropdown" id="notifications_section"> <i
                                            class="fa fa-bell-o"></i>
                                        <span class="badge badge-pill badge-danger notifications_badge_top">9</span>
                                    </a>
                                    <div class="dropdown-menu drop_box_align" role="menu" id="notifications_dropdown">
                                        <div class="popover-title">You have 9 Notifications</div>
                                        <div id="notifications">
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/1.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>Remo</strong>
                                                        sent you an image
                                                        <br>
                                                        <small class="primary_txt">just now.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/2.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>clay</strong>
                                                        business propasals
                                                        <br>
                                                        <small class="primary_txt">20min Back.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/3.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>John</strong>
                                                        meeting at Ritz
                                                        <br>
                                                        <small class="primary_txt">2hrs Back.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/6.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>Luicy</strong>
                                                        Request Invitation
                                                        <br>
                                                        <small class="primary_txt">Yesterday.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/1.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>Remo</strong>
                                                        sent you an image
                                                        <br>
                                                        <small class="primary_txt">just now.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/2.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>clay</strong>
                                                        business propasals
                                                        <br>
                                                        <small class="primary_txt">20min Back.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/3.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>John</strong>
                                                        meeting at Ritz
                                                        <br>
                                                        <small class="primary_txt">2hrs Back.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/6.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>Luicy</strong>
                                                        Request Invitation
                                                        <br>
                                                        <small class="primary_txt">Yesterday.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="data">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="{{ asset('public/admn/img/mailbox_imgs/1.jpg') }}" class="message-img avatar rounded-circle"
                                                             alt="avatar1"></div>
                                                    <div class="col-10 message-data">
                                                        <i class="fa fa-clock-o"></i>
                                                        <strong>Remo</strong>
                                                        sent you an image
                                                        <br>
                                                        <small class="primary_txt">just now.</small>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="popover-footer">
                                            <a href="#" class="text-white">View All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <div class="btn-group">
                                <div class="notifications request_section no-bg">
                                    <a class="btn btn-default btn-sm messages" id="request_btn"> <i
                                            class="fa fa-sliders" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="btn-group">
                                <div class="user-settings no-bg">
                                    <button type="button" class="btn btn-default no-bg micheal_btn" data-toggle="dropdown">
                                        <img src="{{ isset(Auth::user()->avatar)
                                                ? url('public/images/users/' . Auth::user()->avatar)
                                                : url('public/images/users/default.png')}}"
                accesskey="                     "class="admin_img2 img-thumbnail rounded-circle avatar-img"
                                             alt="avatar">
                                        <strong>{{ Auth::user()->username }}</strong>
                                        <span class="fa fa-sort-down white_bg"></span>
                                    </button>
                                    <div class="dropdown-menu admire_admin">
                                        <a class="dropdown-item" href="{{url('admin/settings')}}"><i class="fa fa-cogs"></i>
                                            Account Settings</a>
                                        <a class="dropdown-item" href="{{url('admin/change_password')}}"><i class="fa fa-lock"></i>
                                            Change Password</a>
                                        {{-- <a class="dropdown-item" href="login2.html"><i class="fa fa-sign-out"></i>
                                    Log Out</a> --}}



                                        @if(Auth::guard('admin')->check())


                                        <a class="dropdown-item" href="#"
                                           onclick="event.preventDefault();
                                                   document.querySelector('#admin-logout-form').submit();"> <i class="fa fa-sign-out"></i>
                                            Logout
                                        </a>

                                        <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @endif


                                        <!--	
        <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i>
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
                                        
                                        -->




                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--<div class="top_search_box float-right hidden-sm-down">
                            <form class="header_input_search float-right">
                                <input type="text" placeholder="Search" name="search">
                                <button type="submit">
                                    <span class="font-icon-search"></span>
                                </button>
                                <div class="overlay"></div>
                            </form>
                        </div>-->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
                <!-- /.navbar -->
                <!-- /.head -->
            </div>
            <!-- /#top -->
            <div class="wrapper">
                <div id="left">
                    <div class="menu_scroll">
                        <div class="left_media">
                            <div class="media user-media">
                                <div class="user-media-toggleHover">
                                    <span class="fa fa-user"></span>
                                </div>
                                <div class="user-wrapper">
                                    <a class="user-link" href="#">  
                                        <img class="media-object img-thumbnail user-img rounded-circle admin_img3" alt="User Picture"
                                            src="{{ isset(Auth::user()->avatar)
                                                ? url('public/images/users/' . Auth::user()->avatar)
                                                : url('public/images/users/default.png')}}">
                                        <p class="user-info menu_hide">Welcome {{ Auth::user()->username }}</p>
                                    </a>
                                </div>
                            </div>
                            <hr/>
                        </div>
                        <ul id="menu">
                            <li>
                                <a href="{{url('admin/dashboard')}}">
                                    <i class="fa fa-home"></i>
                                    <span class="link-title menu_hide">&nbsp;Dashboard</span>
                                </a>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    <span class="link-title menu_hide">&nbsp; User Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/user_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; User Grid
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/user')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Users
                                        </a>
                                    </li>
									<!--<li>
                                        <a href="{{url('admin/level_approvals')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Level Approval
                                        </a>
                                    </li>-->
                                </ul>
                            </li>
							
							<li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-bars"></i>
                                    <span class="link-title menu_hide">&nbsp; Category Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/category_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Category List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/category')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Categories
                                        </a>
                                    </li>
                                </ul>
                            </li>
							
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-certificate"></i>
                                    <span class="link-title menu_hide">&nbsp; Course Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/course_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Course List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/course')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Course
                                        </a>
                                    </li>
                                </ul>
                            </li>
							
							<!--<li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-bold"></i>
                                    <span class="link-title menu_hide">&nbsp; Blog Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/blog_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Blog List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/blog')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Blog
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/blog_approval')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Blog Comment Approval
                                        </a>
                                    </li>
                                </ul>
                            </li>-->
							<!-- <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-bitcoin"></i>
                                    <span class="link-title menu_hide">&nbsp; Book Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/book_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Book List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/book')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Books
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-quote-left"></i>
                                    <span class="link-title menu_hide">&nbsp; Order Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/order_list')}}">
                                            <i class="fa fa-gift"></i>
                                            &nbsp; Order List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/order/cancel_request_list')}}">
                                            <i class="fa fa-gift"></i>
                                            &nbsp; Order Cancellation Request
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/order/refund_list')}}">
                                            <i class="fa fa-gift"></i>
                                            &nbsp; Refund History
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-vcard-o"></i>
                                    <span class="link-title menu_hide">&nbsp; CMS Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/cms_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; CMS List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/cms')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add CMS
                                        </a>
                                    </li>
									<li>
                                        <a href="{{url('admin/homecms')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Homepage Cms
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-quote-left"></i>
                                    <span class="link-title menu_hide">&nbsp; Testimonial Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/testimonials')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Testimonial List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/testimonial')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Testimonial
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-text-width"></i>
                                    <span class="link-title menu_hide">&nbsp; Tag Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/tag_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Tag List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/tag')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Tag
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-bold"></i>
                                    <span class="link-title menu_hide">&nbsp; Blog Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/blog_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Blog List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/blog')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Blog
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/blog_approval')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Blog Comment Approval
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-file-powerpoint-o"></i>
                                    <span class="link-title menu_hide">&nbsp; Podcast Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/pod_list')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Pod List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/podcast')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Add Pod
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('admin/pod_approval')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Pod Comment Approval
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown_menu">
                                <a href="#">
                                    <i class="fa fa-envelope-open"></i>
                                    <span class="link-title menu_hide">&nbsp; Subscribers Management</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{url('admin/subscribers')}}">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp; Subscribers List
                                        </a>
                                    </li>
                                </ul>
                            </li>
							<li>
                                <a href="{{url('admin/seller_rating')}}">
                                    <i class="fa fa-star"></i>
                                    <span class="link-title menu_hide">&nbsp;Seller Rating</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{url('admin/contact_us_list')}}">
                                    <i class="fa fa-phone"></i>
                                    <span class="link-title menu_hide">&nbsp;Contact Us</span>
                                </a>
                            </li>-->
                            

                            <!--<li class="dropdown_menu">
                                <a href="javascript:;">
                                    <i class="fa fa-sitemap"></i>
                                    <span class="link-title menu_hide">&nbsp; Multi Level Menu</span>
                                    <span class="fa arrow menu_hide"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="javascript:;">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp;Level 1
                                            <span class="fa arrow"></span>
                                        </a>
                                        <ul class="sub-menu sub-submenu">
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                    &nbsp;Level 2
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                    &nbsp;Level 2
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                    &nbsp;Level 2
                                                    <span class="fa arrow"></span>
                                                </a>
                                                <ul class="sub-menu sub-submenu">
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-angle-right"></i>
                                                            &nbsp;Level 3
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-angle-right"></i>
                                                            &nbsp;Level 3
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-angle-right"></i>
                                                            &nbsp;Level 3
                                                            <span class="fa arrow"></span>
                                                        </a>
                                                        <ul class="sub-menu sub-submenu">
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <i class="fa fa-angle-right"></i>
                                                                    &nbsp;Level 4
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <i class="fa fa-angle-right"></i>
                                                                    &nbsp;Level 4
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <i class="fa fa-angle-right"></i>
                                                                    &nbsp;Level 4
                                                                    <span class="fa arrow"></span>
                                                                </a>
                                                                <ul class="sub-menu sub-submenu">
                                                                    <li>
                                                                        <a href="javascript:;">
                                                                            <i class="fa fa-angle-right"></i>
                                                                            &nbsp;Level 5
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;">
                                                                            <i class="fa fa-angle-right"></i>
                                                                            &nbsp;Level 5
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;">
                                                                            <i class="fa fa-angle-right"></i>
                                                                            &nbsp;Level 5
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-angle-right"></i>
                                                            &nbsp;Level 4
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                    &nbsp;Level 2
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp;Level 1
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <i class="fa fa-angle-right"></i>
                                            &nbsp;Level 1
                                            <span class="fa arrow"></span>
                                        </a>
                                        <ul class="sub-menu sub-submenu">
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                    &nbsp;Level 2
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                    &nbsp;Level 2
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                    &nbsp;Level 2
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>-->
                        </ul>
                        <!-- /#menu -->
                    </div>
                </div>
                <!-- /#left -->



                @yield('content')





                <!-- /.inner -->
            </div>
            <!-- /.outer -->

            <!-- /#content -->
            <!-- Modal -->
            <div class="modal fade" id="search_modal" tabindex="-1" role="dialog"  aria-hidden="true">
                <form>
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="float-right" aria-hidden="true">&times;</span>
                            </button>
                            <div class="input-group search_bar_small">
                                <input type="text" class="form-control" placeholder="Search..." name="search">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="submit"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- # right side -->
            @include('admin.layout.right_sidebar')
            <!-- # right side -->
        </div>
        <!-- /#wrap -->
        <!-- global scripts-->
        <script type="text/javascript" src="{{ asset('public/admn/js/components.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/admn/js/custom.js') }}"></script>
        <!--end of global scripts-->
        @yield('pagescript')
    </body>

</html>