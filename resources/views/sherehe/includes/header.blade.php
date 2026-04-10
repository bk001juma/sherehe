<header data-anim="fade" data-add-bg="bg-dark-1" class="header -type-1 js-header">

    <div class="header__container ">
      <div class="row justify-between items-center">

        <div class="col-auto">
          <div class="header-left">

            <div class="header__logo ">
              <a data-barba href="/">
                <img src="/logo.png" style="height: 50px" alt="logo">
              </a>
            </div>

          </div>
        </div>


        <div class="header-menu js-mobile-menu-toggle ">
          <div class="header-menu__content">
            <div class="mobile-bg js-mobile-bg"></div>

            <div class="d-none xl:d-flex items-center px-20 py-20 border-bottom-light">
                @if(isset(Auth::user()->id))
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"> {{ __('Logout') }} </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf</form>
                    <a href="{{route('dashboard')}}" class="text-dark-1 ml-30">{{__('My Account')}}</a>
                @else
                    <a href="/login" class="text-dark-1">{{__('Log in')}}</a>
{{--                    <a href="/register" class="text-dark-1 ml-30">{{__('Sign Up')}}</a>--}}
                @endif
            </div>

            <div class="menu js-navList">
              <ul class="menu__nav text-white -is-active">
{{--                  @include('sherehe.includes.menu')--}}
              </ul>
            </div>

            <div class="mobile-footer px-20 py-20 border-top-light js-mobile-footer">
              <div class="mobile-footer__number">
                <div class="text-17 fw-500 text-dark-1">Call us</div>
                <a href="tel:+255 743 005 002" class="text-17 fw-500 text-purple-1">+255 743 005 002</a>

              </div>

              <div class="lh-2 mt-10">
                <div>Dar es salaam,<br> Tanzania</div>
                <a href="mailto:info@sherehe.co.tz">info@sherehe.co.tz</a>
              </div>

{{--                <a href="@if(App::currentLocale() === 'en') {{route('set_locale',['sw'])}} @else {{route('set_locale',['en'])}} @endif" class="button btn-sm px-0 h-40 -dark-6 rounded-200 text-white">--}}
{{--                    <i class="icon-worldwide text-20 mr-15"></i><span class="text-15">@if(App::currentLocale() === 'en') Swahili @else English @endif</span>--}}
{{--                </a>--}}

              <div class="mobile-socials mt-10">
                  <a class="d-flex items-center justify-center rounded-full size-40" target="_blank" href="https://www.facebook.com/ubunifuacademy"><i class="icon-facebook"></i></a>
                  <a class="d-flex items-center justify-center rounded-full size-40" target="_blank" href="https://twitter.com/UbunifuAcademy"><i class="icon-twitter"></i></a>
                  <a class="d-flex items-center justify-center rounded-full size-40" target="_blank" href="https://www.instagram.com/ubunifuacademy/"><i class="icon-instagram"></i></a>
                  <a class="d-flex items-center justify-center rounded-full size-40" target="_blank" href="https://www.linkedin.com/company/ubunifu-academy/"><i class="icon-linkedin"></i></a>

              </div>
            </div>
          </div>

          <div class="header-menu-close" data-el-toggle=".js-mobile-menu-toggle">
            <div class="size-40 d-flex items-center justify-center rounded-full bg-white">
              <div class="icon-close text-dark-1 text-16"></div>
            </div>
          </div>

          <div class="header-menu-bg"></div>
        </div>


        <div class="col-auto">
          <div class="header-right d-flex items-center">
            <div class="header-right__icons text-white d-flex items-center">



              <div class="relative ml-30 xl:ml-20">

              </div>


              <div class="d-none xl:d-block ml-20">
                <button class="text-white items-center" data-el-toggle=".js-mobile-menu-toggle">
                  <i class="text-11 icon icon-mobile-menu"></i>
                </button>
              </div>

            </div>

            <div class="header-right__buttons d-flex items-center ml-30 md:d-none">
                {{-- <a data-barba class="text-white" href=/login">{{__('Contact Us')}}</a> --}}
                @if(isset(Auth::user()->id))
{{--                    <a  class="button -underline text-white"href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"> {{ __('Logout') }}</a>--}}
                    <a href="{{route('dashboard')}}" class="button -sm -white text-dark-1 ml-30">My Account</a>
                @else
                    <a href="/login" class="button -sm -dark-4 text-white ml-30">Log in</a>
{{--                    <a href="/register" class="button -sm -white text-dark-1 ml-30">Sign up</a>--}}
                @endif

            </div>
          </div>
        </div>

      </div>
    </div>
  </header>
