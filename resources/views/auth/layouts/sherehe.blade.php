<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
  <link rel="stylesheet" href="../../../cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../../../unpkg.com/leaflet%401.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

  <!-- Stylesheets -->
  <link rel="stylesheet" href="/css/vendors.css">
  <link rel="stylesheet" href="/css/main.css">

  <title>@hasSection('template_title')@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
</head>

<body class="preloader-visible" data-barba="wrapper">

  <!-- preloader start -->
  <div class="preloader js-preloader">
    <div class="preloader__bg"></div>
  </div>
  <!-- preloader end -->

  <!-- barba container start -->
  <div class="barba-container" data-barba="container">


    <main class="main-content bg-beige-1">

      <header data-anim="fade" data-add-bg="" class="header -base js-header">


        <div class="header__container py-10">
          <div class="row justify-between items-center">

            <div class="col-auto">
              <div class="header-left">

                <div class="header__logo ">
                  <a data-barba href="/">
                    <img src="/logo.png" style="max-height: 50px" alt="logo">
                  </a>
                </div>

              </div>
            </div>


            <div class="col-auto">
              <div class="header-right d-flex items-center">

                <div class="header-menu js-mobile-menu-toggle ">
                  <div class="header-menu__content">
                    <div class="mobile-bg js-mobile-bg"></div>

                    <div class="d-none xl:d-flex items-center px-20 py-20 border-bottom-light">
                        @yield('button')
                    </div>

                    <div class="menu js-navList">
                        <ul class="menu__nav text-dark-1 -is-active">
                            @include('sherehe.includes.menu')
                        </ul>
                    </div>

{{--                    <div class="mobile-footer px-20 py-20 border-top-light js-mobile-footer">--}}
{{--                      <div class="mobile-footer__number">--}}
{{--                        <div class="text-17 fw-500 text-dark-1">Call us</div>--}}
{{--                        <a href="tel:+255 785 008 133" class="text-17 fw-500 text-purple-1">+255 785 008 133</a>--}}
{{--                      </div>--}}

{{--                      <div class="lh-2 mt-10">--}}
{{--                        <div>NHC Samora House 10th Floor,<br>--}}
{{--                            Dar es salaam, Tanzania.</div>--}}
{{--                        <a href="mailto:habari@ubunifuacademy.co.tz">habari@ubunifuacademy.co.tz</a>--}}
{{--                      </div>--}}

{{--                      <div class="mobile-socials mt-10">--}}

{{--                        <a href="#" class="d-flex items-center justify-center rounded-full size-40">--}}
{{--                          <i class="fa fa-facebook"></i>--}}
{{--                        </a>--}}

{{--                        <a href="#" class="d-flex items-center justify-center rounded-full size-40">--}}
{{--                          <i class="fa fa-twitter"></i>--}}
{{--                        </a>--}}

{{--                        <a href="#" class="d-flex items-center justify-center rounded-full size-40">--}}
{{--                          <i class="fa fa-instagram"></i>--}}
{{--                        </a>--}}

{{--                        <a href="#" class="d-flex items-center justify-center rounded-full size-40">--}}
{{--                          <i class="fa fa-linkedin"></i>--}}
{{--                        </a>--}}

{{--                      </div>--}}
{{--                    </div>--}}
                  </div>

                  <div class="header-menu-close" data-el-toggle=".js-mobile-menu-toggle">
                    <div class="size-40 d-flex items-center justify-center rounded-full bg-white">
                      <div class="icon-close text-dark-1 text-16"></div>
                    </div>
                  </div>

                  <div class="header-menu-bg"></div>
                </div>


                <div class="mr-30">

                  <div class="d-none xl:d-block ml-20">
                    <button class="text-dark-1 items-center" data-el-toggle=".js-mobile-menu-toggle">
                      <i class="text-11 icon icon-mobile-menu"></i>
                    </button>
                  </div>

                </div>

                <div class="header-right__buttons md:d-none">
                    @yield('button2')
                </div>
              </div>
            </div>

          </div>
        </div>
      </header>

        @yield('content')
    </main>
  </div>
  <!-- barba container end -->

  <!-- JavaScript -->
  <script src="/js/vendors.js"></script>
  <script src="/js/main.js"></script>

  @yield('js')
<script>
    var stat = null;
    var dd = "No";

    @if(session()->has('status'))
      stat = "{{session()->get('status')}}";
      dd = "Something Went Wrong!";
    @endif

    @if(session()->has('message'))
     dd = "{{session()->get('message')}}";
    @endif
</script>

</body>


</html>
