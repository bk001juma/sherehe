<footer class="footer -type-1 bg-dark-1 -green-links">
  <div class="container">
    <div class="footer-header">
      <div class="row y-gap-20 justify-between items-center">
        <div class="col-auto">
          <div class="footer-header__logo">
              <a href="/">
                  <img src="/logo.png" style="height: 50px" alt="logo">
              </a>
          </div>
        </div>
        <div class="col-auto">
          <div class="footer-header-socials">
            <div class="footer-header-socials__title text-white">
                {{__('Follow us on social media')}} <br>
                +255 743 005 002 <br>
                +255 673 255 194 <br>
                info@sherehe.co.tz
            </div>
            <div class="footer-header-socials__list">
              <a target="_blank" href="https://www.facebook.com/ubunifuacademy"><i class="icon-facebook"></i></a>
              <a target="_blank" href="https://twitter.com/UbunifuAcademy"><i class="icon-twitter"></i></a>
              <a target="_blank" href="https://www.instagram.com/ubunifuacademy/"><i class="icon-instagram"></i></a>
              <a target="_blank" href="https://www.linkedin.com/company/ubunifu-academy/"><i class="icon-linkedin"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

{{--    <div class="footer-columns">--}}
{{--      <div class="row y-gap-30">--}}
{{--        <div class="col-xl-2 col-lg-4 col-md-6">--}}
{{--          <div class="text-17 fw-500 text-white uppercase mb-25">ABOUT</div>--}}
{{--          <div class="d-flex y-gap-10 flex-column">--}}
{{--            <a href="{{route('about')}}">About Us</a>--}}
{{--            <a href="{{route('businesses')}}">Instructors</a>--}}
{{--            <a href="{{route('contacts')}}">Contact Us</a>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="col-xl-4 col-lg-8">--}}
{{--          <div class="text-17 fw-500 text-white uppercase mb-25">CATEGORIES</div>--}}
{{--          <div class="row justify-between y-gap-20">--}}
{{--            <div class="col-md-6">--}}
{{--              <div class="d-flex y-gap-10 flex-column">--}}
{{--                  @foreach(\App\Models\Learn\MasterClassCategory::get() as $category)--}}
{{--                      <a href="{{route('master_classes',['category_id'=>$category->id])}}">{{$category->name}}</a>--}}
{{--                  @endforeach--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="col-xl-2 offset-xl-1 col-lg-4 col-md-6">--}}
{{--          <div class="text-17 fw-500 text-white uppercase mb-25">SUPPORT</div>--}}
{{--          <div class="d-flex y-gap-10 flex-column">--}}
{{--            <a href="{{route('terms_and_conditions')}}">Documentation</a>--}}
{{--            <a href="{{route('faq')}}">FAQS</a>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="col-xl-3 col-lg-4 col-md-6">--}}
{{--          <div class="text-17 fw-500 text-white uppercase mb-25">GET IN TOUCH</div>--}}
{{--          <div class="footer-columns-form">--}}
{{--            <div>We don’t send spam so don’t worry.</div>--}}
{{--            <form action="">--}}
{{--              <div class="form-group">--}}
{{--                <input type="text" placeholder="Email...">--}}
{{--                <button type="submit">Submit</button>--}}
{{--              </div>--}}
{{--            </form>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}

    <div class="py-30 border-top-light-15">
      <div class="row justify-between items-center y-gap-20">
        <div class="col-auto">
          <div class="d-flex items-center h-100 text-white">
              <p>
                  © {{date('Y')}} <a target="_blank" href="http://ubunifuacademy.co.tz"> {{ config('app.name', Lang::get('titles.app')) }}</a>. All Right Reserved.
              </p>
          </div>
        </div>

        <div class="col-auto">
          <div class="d-flex x-gap-20 y-gap-20 items-center flex-wrap">

{{--            <div>--}}
{{--              <div class="d-flex x-gap-15 text-white">--}}
{{--                <a href="{{route('faq')}}">Help</a>--}}
{{--                <a href="{{route('terms_and_conditions')}}">Terms of Use</a>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div>--}}
{{--              <a href="@if(App::currentLocale() === 'en') {{route('set_locale',['sw'])}} @else {{route('set_locale',['en'])}} @endif" class="button px-30 h-50 -dark-6 rounded-200 text-white">--}}
{{--                <i class="icon-worldwide text-20 mr-15"></i><span class="text-15">@if(App::currentLocale() === 'en') Swahili @else English @endif</span>--}}
{{--              </a>--}}
{{--            </div>--}}

          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
