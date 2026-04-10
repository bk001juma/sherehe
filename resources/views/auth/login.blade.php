@extends('auth.layouts.sherehe')

@section('template_title')
    {{ __('Login') }}
@endsection

@section('button')
    {{--    <a href="/register" class="text-dark-1 ml-30">{{ __('Register')}}</a> --}}
@endsection

@section('button2')
    {{--    <a href="/register" class="button -sm -rounded -dark-1 text-white">{{ __('Sign Up')}}</a> --}}
@endsection

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <div class="content-wrapper  js-content-wrapper" style="background-color: #d3d3d3">
        <section class="form-page js-mouse-move-container">
            <div class="form-page__img" style="background-image: url(img/home-9/hero/bg.png); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="form-page-composition">
                    {{-- <div class="-bg"><img data-move="30" class="js-mouse-move" src="img/login/bg.png" alt="bg"></div> --}}
                    {{-- <div class="-el-1"><img data-move="20" class="" src="img/home-9/hero/bg.png" alt="image"></div> --}}
                    {{-- <div class="-el-2"><img data-move="40" class="js-mouse-move" src="img/home-9/hero/1.png" alt="icon"></div>
              <div class="-el-3"><img data-move="40" class="js-mouse-move" src="img/home-9/hero/2.png" alt="icon"></div>
              <div class="-el-4"><img data-move="40" class="js-mouse-move" src="img/home-9/hero/3.png" alt="icon"></div> --}}
                </div>
            </div>

            <div class="form-page__content lg:py-50">
                <div class="container">
                    <div class="row justify-center items-center">
                        <div class="col-xl-6 col-lg-8">

                            <h4
                                style="color: #003366; margin: 0 0 20px; text-align: center; font-family: 'Pacifico', cursive; font-size: 1.5em; font-weight:normal;">
                                Optimize Your Event for an Exceptional Experience
                            </h4>

                            <div class="px-50 py-50 md:px-25 md:py-25 bg-white shadow-1 rounded-16">
                                <div style="display: flex; justify-content: center;" class="mb-3">
                                    <a data-barba href="/">
                                        <img src="/logo.png" style="max-height: 100px;" alt="logo">
                                    </a>
                                </div>

                                <h3 style="display: flex; justify-content: center; font-weight:normal"
                                    class="text-26 lh-10">{{ __('Login') }}</h3>
                                {{-- <p class="mt-10">Tafadhali ingiza namba ya simu</p> --}}
                                <form id="login" class="contact-form respondForm__form row y-gap-20 pt-30"
                                    method="POST" action="{{ route('web_login') }}">
                                    @csrf
                                    <div class="col-12">
                                        {{-- <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">{{ __('Phone Number') }}
                                            @if ($errors->has('phone'))
                                                <span style="color: red">{{ $errors->first('phone') }}</span>
                                            @endif
                                        </label> --}}
                                        <input required type="text" minlength="10" maxlength="10" name="phone"
                                            value="{{ old('phone') }}" style="text-align: center;"
                                            placeholder="Phone Number" onkeypress='validate(event)'>
                                    </div>
                                    <div class="col-12">
                                        <a onclick="submitForm()" id="submit"
                                            class="button -md -black text-dark-1 fw-500 w-1/1"
                                            style="background-color: #003366;">
                                            {{ __('Login') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="mb-4"></div>
                            <div style="display: flex; justify-content: center;">
                                <span style="padding-right: 6px">
                                <i class="fa fa-instagram" style="color: #003366;font-size: 1.3em;">
                                </i> </span><span style="color: #003366; font-size: 1em; font-weight:20;">sherehedigital</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
@endsection

@section('js')
    <script>
        function validate(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\./;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }

        function submitForm() {
            document.getElementById('submit').innerText = "Loading ...";
            document.getElementById('login').submit();
        }
    </script>
@endsection
