<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sherehe Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The Best digital cards for your event. Digitalise event planing and invitations">
    <meta name="keywords" content="Sherehe, Sherehe Digital, Harusi, Send Off, Party">
    <meta name="author" content="Jackson Mwatuka">

    <!--[if lt IE 9]>
 <script src="js/html5shiv.js"></script>
 <![endif]-->

    <!-- CSS Files
    ================================================== -->
    <link rel="stylesheet" href="{{ asset('new/css/bootstrap.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('new/css/animate.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('new/css/owl.carousel.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('new/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('new/css/jquery.countdown.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('new/css/style.css') }}" type="text/css">

    <!-- background -->
    <link rel="stylesheet" href="{{ asset('new/css/bg.css') }}" type="text/css">
    <!-- color -->
    <link rel="stylesheet" href="{{ asset('new/css/color.css') }}" type="text/css">
</head>

<body id="homepage">

    <div id="wrapper">

        <!-- header begin -->
        <header>

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- logo begin -->
                        <div id="logo">
                            <a href="/">
                                <img class="logo" height="40px" src="{{ asset('new/images/logo-light.png') }}" alt="">
                                <img class="logo-2" height="40px" src="{{ asset('new/images/logo-dark.png') }}" alt="">

                            </a>
                        </div>
                        <!-- logo close -->

                        <!-- small button begin -->
                        <span id="menu-btn"></span>
                        <!-- small button close -->

                        <!-- mainmenu begin -->
                        <nav>
                            {{-- <a href="index_3.html#" class="btn btn-custom">Get Ticket</a>&nbsp; --}}
                            {{-- <ul id="mainmenu"> --}}
                            {{-- <li><a class="active" href="/">Home</a>
                                </li> --}}
                            <div id="logo">
                                @if (isset(Auth::user()->id))
                                    <a href="{{ route('logout') }}" class="btn btn-custom"
                                        style="
  background-color: #a1c235;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: bold;
  text-decoration: none;
  /* box-shadow: 0 0 20px 5px #dbf39a;
  filter: drop-shadow(0 0 10px #dbf39a); */
  transition: box-shadow 0.3s ease-in-out;
  "
                                        onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }} </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;"> @csrf</form>
                                    <a href="{{ route('dashboard') }}" class="btn btn-custom"
                                        style="
  background-color: #003366;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: bold;
  text-decoration: none;
  /* box-shadow: 0 0 20px 5px #0073e5;
  filter: drop-shadow(0 0 10px #0073e5); */
  transition: box-shadow 0.3s ease-in-out;
  ">{{ __('My Account') }}</a>
                                @else
                                    <a href="/login" class="btn btn-custom"
                                        style="
  background-color: #a1c235;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: bold;
  text-decoration: none;
  box-shadow: 0 0 20px 5px #dbf39a;
  filter: drop-shadow(0 0 10px #dbf39a);
  transition: box-shadow 0.3s ease-in-out;
  ">
                                        {{ __('Log in') }}
                                    </a>
                                @endif
                            </div>
                            {{-- </ul> --}}
                        </nav>

                    </div>
                    <!-- mainmenu close -->

                </div>
            </div>
        </header>
        <!-- header close -->

        <!-- content begin -->
        <div id="content" class="no-bottom no-top">
            <div id="top"></div>

            @yield('content')

        </div>
        <!-- content close -->

        <!-- footer begin -->
        <footer>
            <div class="container text-center text-light">
                <div class="row">
                    <div class="col-md-12">
                        <div class="social-icons big">
                            <a href="index.html#"><i class="fa fa-facebook fa-lg"></i></a>
                            <a href="index.html#"><i class="fa fa-twitter fa-lg"></i></a>
                            <a href="index.html#"><i class="fa fa-rss fa-lg"></i></a>
                            <a href="index.html#"><i class="fa fa-google-plus fa-lg"></i></a>
                            <a href="index.html#"><i class="fa fa-skype fa-lg"></i></a>
                            <a href="index.html#"><i class="fa fa-dribbble fa-lg"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="subfooter">
                <div class="container text-center">
                    <div class="row">
                        <div class="col-md-12">
                            &copy; Copyright 2025 - Designed By <a href="https://humtech.co.tz">Humtech</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer close -->

        <a href="/" id="back-to-top"></a>
        <div id="preloader">
            <div class="preloader1"></div>
        </div>

    </div>

    <!-- Javascript Files
    ================================================== -->
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="{{ asset('new/js/jquery.min.js') }}"></script>
    <script src="{{ asset('new/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('new/js/jquery.isotope.min.js') }}"></script>
    <script src="{{ asset('new/js/easing.js') }}"></script>
    <script src="{{ asset('new/js/owl.carousel.js') }}"></script>
    <script src="{{ asset('new/js/jquery.countTo.js') }}"></script>
    <script src="{{ asset('new/js/wow.min.js') }}"></script>
    <script src="{{ asset('new/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('new/js/enquire.min.js') }}"></script>
    <script src="{{ asset('new/js/jquery.stellar.min.js') }}"></script>
    <script src="{{ asset('new/js/jquery.plugin.js') }}"></script>
    <script src="{{ asset('new/js/jquery.countdown.js') }}"></script>
    <script src="{{ asset('new/js/countdown-custom.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc5d1RS_0yWJ1Hyw2hGWfbRZ9KKaxFAZo"></script>
    <script src="{{ asset('new/js/map.js') }}"></script>
    <script src="{{ asset('new/js/designesia.js') }}"></script>

</body>

</html>
