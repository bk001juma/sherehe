@php
    $user = Auth::user();
@endphp
<!DOCTYPE html>
<html lang="en"
    @isset($user->id) @if ($user->profile->tutor_background == 1) class="-dark-mode" @endif @endisset>


<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    {{--  <link rel="stylesheet" href="../../../cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    {{--  <link rel="stylesheet" href="../../../unpkg.com/leaflet%401.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" /> --}}

    <link rel="shortcut icon" href="/logo.png">
    <link href="{{ url('/') }}/assets/plugins/global/plugins.bundle15aa.css?v=7.2.2" rel="stylesheet"
        type="text/css" />

    {{--    Css Bootstrap for Modals --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    @yield('page_css')


    @livewireStyles
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/css/vendors.css">
    <link rel="stylesheet" href="/css/main.css">

    <title>
        @hasSection('template_title')
            @yield('template_title') |
        @endif {{ config('app.name', Lang::get('titles.app')) }}
    </title>
</head>

<body class="preloader-visible" data-barba="wrapper">
    <!-- preloader start -->
    <div class="preloader js-preloader">
        {{--    <div class="preloader__bg"></div> --}}
    </div>
    <!-- preloader end -->

    <!-- barba container start -->
    <div class="barba-container" data-barba="container">


        <main class="main-content">

            @include('sherehe.dash.includes.header')

            <div class="content-wrapper js-content-wrapper">
                <div
                    class="dashboard -home-9 js-dashboard-home-9 {{ Route::is('lesson_materials') ? '-is-sidebar-hidden' : null }}">
                    <div class="dashboard__sidebar scroll-bar-1">

                        @include('sherehe.dash.includes.menu')

                    </div>

                    <div class="dashboard__main">
                        @yield('content')

                        @include('sherehe.dash.includes.footer')
                    </div>
                </div>
            </div>
        </main>

        <aside class="sidebar-menu toggle-element js-msg-toggle js-dsbh-sidebar-menu">
            <div class="sidebar-menu__bg"></div>

            <div class="sidebar-menu__content scroll-bar-1 py-30 px-40 sm:py-25 sm:px-20 bg-white -dark-bg-dark-1">
                <div class="row items-center justify-between mb-30">
                    <div class="col-auto">
                        <div class="-sidebar-buttons">

                            <button data-sidebar-menu-button="messages"
                                class="text-17 text-dark-1 fw-500 -is-button-active">
                                Messages
                            </button>

                            <button data-sidebar-menu-button="messages-2" data-sidebar-menu-target="messages"
                                class="d-flex items-center text-17 text-dark-1 fw-500">
                                <i class="icon-chevron-left text-11 text-purple-1 mr-10"></i>
                                Messages
                            </button>

                            <button data-sidebar-menu-button="settings" data-sidebar-menu-target="messages"
                                class="d-flex items-center text-17 text-dark-1 fw-500">
                                <i class="icon-chevron-left text-11 text-purple-1 mr-10"></i>
                                Settings
                            </button>

                            <button data-sidebar-menu-button="contacts" data-sidebar-menu-target="messages"
                                class="d-flex items-center text-17 text-dark-1 fw-500">
                                <i class="icon-chevron-left text-11 text-purple-1 mr-10"></i>
                                Contacts
                            </button>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="row x-gap-10">
                            <div class="col-auto">
                                <button data-sidebar-menu-target="settings"
                                    class="button -purple-3 text-purple-1 size-40 d-flex items-center justify-center rounded-full">
                                    <i class="icon-setting text-16"></i>
                                </button>
                            </div>
                            <div class="col-auto">
                                <button data-sidebar-menu-target="contacts"
                                    class="button -purple-3 text-purple-1 size-40 d-flex items-center justify-center rounded-full">
                                    <i class="icon-friend text-16"></i>
                                </button>
                            </div>
                            <div class="col-auto">
                                <button data-el-toggle=".js-msg-toggle"
                                    class="button -purple-3 text-purple-1 size-40 d-flex items-center justify-center rounded-full">
                                    <i class="icon-close text-14"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative js-menu-switch">
                    <div data-sidebar-menu-open="messages"
                        class="sidebar-menu__item -sidebar-menu -sidebar-menu-opened">
                        <form class="search-field rounded-8 h-50"
                            action="https://creativelayers.net/themes/educrat-html/post">
                            <input class="bg-light-3 pr-50" type="text" placeholder="Search Courses">
                            <button class="" type="submit">
                                <i class="icon-search text-light-1 text-20"></i>
                            </button>
                        </form>

                        <div class="accordion -block text-left pt-20 js-accordion">

                            <div class="accordion__item border-light rounded-16">
                                <div class="accordion__button">
                                    <div class="accordion__icon size-30 -dark-bg-dark-2 mr-10">
                                        <div class="icon d-flex items-center justify-center">
                                            <span class="lh-1 fw-500">2</span>
                                        </div>
                                        <div class="icon d-flex items-center justify-center">
                                            <span class="lh-1 fw-500">2</span>
                                        </div>
                                    </div>
                                    <span class="text-17 fw-500 text-dark-1 pt-3">Starred</span>
                                </div>

                                <div class="accordion__content">
                                    <div class="accordion__content__inner pl-20 pr-20 pb-20">
                                        <div data-sidebar-menu-target="messages-2"
                                            class="row x-gap-10 y-gap-10 pointer">
                                            <div class="col-auto">
                                                <img src="/img/dashboard/right-sidebar/messages/1.png" alt="image">
                                            </div>
                                            <div class="col">
                                                <div class="text-15 lh-12 fw-500 text-dark-1 pt-8">Darlene Robertson
                                                </div>
                                                <div class="text-14 lh-1 mt-5"><span class="text-dark-1">You:</span>
                                                    Hello</div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-13 lh-12 pt-8">35 mins</div>
                                            </div>
                                        </div>

                                        <div data-sidebar-menu-target="messages-2"
                                            class="row x-gap-10 y-gap-10 pt-15 pointer">
                                            <div class="col-auto">
                                                <img src="/img/dashboard/right-sidebar/messages/1.png" alt="image">
                                            </div>
                                            <div class="col">
                                                <div class="text-15 lh-12 fw-500 text-dark-1 pt-8">Darlene Robertson
                                                </div>
                                                <div class="text-14 lh-1 mt-5"><span class="text-dark-1">You:</span>
                                                    Hello</div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-13 lh-12 pt-8">35 mins</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion__item border-light rounded-16">
                                <div class="accordion__button">
                                    <div class="accordion__icon size-30 -dark-bg-dark-2 mr-10">
                                        <div class="icon d-flex items-center justify-center">
                                            <span class="lh-1 fw-500">2</span>
                                        </div>
                                        <div class="icon d-flex items-center justify-center">
                                            <span class="lh-1 fw-500">2</span>
                                        </div>
                                    </div>
                                    <span class="text-17 fw-500 text-dark-1 pt-3">Group</span>
                                </div>

                                <div class="accordion__content">
                                    <div class="accordion__content__inner pl-20 pr-20 pb-20">
                                        <div data-sidebar-menu-target="messages-2"
                                            class="row x-gap-10 y-gap-10 pointer">
                                            <div class="col-auto">
                                                <img src="/img/dashboard/right-sidebar/messages/1.png" alt="image">
                                            </div>
                                            <div class="col">
                                                <div class="text-15 lh-12 fw-500 text-dark-1 pt-8">Darlene Robertson
                                                </div>
                                                <div class="text-14 lh-1 mt-5"><span class="text-dark-1">You:</span>
                                                    Hello</div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-13 lh-12 pt-8">35 mins</div>
                                            </div>
                                        </div>

                                        <div data-sidebar-menu-target="messages-2"
                                            class="row x-gap-10 y-gap-10 pt-15 pointer">
                                            <div class="col-auto">
                                                <img src="/img/dashboard/right-sidebar/messages/1.png" alt="image">
                                            </div>
                                            <div class="col">
                                                <div class="text-15 lh-12 fw-500 text-dark-1 pt-8">Darlene Robertson
                                                </div>
                                                <div class="text-14 lh-1 mt-5"><span class="text-dark-1">You:</span>
                                                    Hello</div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-13 lh-12 pt-8">35 mins</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion__item border-light rounded-16">
                                <div class="accordion__button">
                                    <div class="accordion__icon size-30 -dark-bg-dark-2 mr-10">
                                        <div class="icon d-flex items-center justify-center">
                                            <span class="lh-1 fw-500">2</span>
                                        </div>
                                        <div class="icon d-flex items-center justify-center">
                                            <span class="lh-1 fw-500">2</span>
                                        </div>
                                    </div>
                                    <span class="text-17 fw-500 text-dark-1 pt-3">Private</span>
                                </div>

                                <div class="accordion__content">
                                    <div class="accordion__content__inner pl-20 pr-20 pb-20">
                                        <div data-sidebar-menu-target="messages-2"
                                            class="row x-gap-10 y-gap-10 pointer">
                                            <div class="col-auto">
                                                <img src="/img/dashboard/right-sidebar/messages/1.png" alt="image">
                                            </div>
                                            <div class="col">
                                                <div class="text-15 lh-12 fw-500 text-dark-1 pt-8">Darlene Robertson
                                                </div>
                                                <div class="text-14 lh-1 mt-5"><span class="text-dark-1">You:</span>
                                                    Hello</div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-13 lh-12 pt-8">35 mins</div>
                                            </div>
                                        </div>

                                        <div data-sidebar-menu-target="messages-2"
                                            class="row x-gap-10 y-gap-10 pt-15 pointer">
                                            <div class="col-auto">
                                                <img src="/img/dashboard/right-sidebar/messages/1.png" alt="image">
                                            </div>
                                            <div class="col">
                                                <div class="text-15 lh-12 fw-500 text-dark-1 pt-8">Darlene Robertson
                                                </div>
                                                <div class="text-14 lh-1 mt-5"><span class="text-dark-1">You:</span>
                                                    Hello</div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-13 lh-12 pt-8">35 mins</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div data-sidebar-menu-open="messages-2" class="sidebar-menu__item -sidebar-menu">
                        <div class="row x-gap-10 y-gap-10">
                            <div class="col-auto">
                                <img src="/img/dashboard/right-sidebar/messages-2/1.png" alt="image">
                            </div>
                            <div class="col">
                                <div class="text-15 lh-12 fw-500 text-dark-1 pt-8">Arlene McCoy</div>
                                <div class="text-14 lh-1 mt-5">Active</div>
                            </div>
                        </div>

                        <div class="mt-20 pt-30 border-top-light">
                            <div class="row y-gap-20">
                                <div class="col-12">
                                    <div class="row x-gap-10 y-gap-10 items-center">
                                        <div class="col-auto">
                                            <img src="/img/dashboard/right-sidebar/messages-2/2.png" alt="image">
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-15 lh-12 fw-500 text-dark-1">Albert Flores</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-14 lh-1 ml-3">35 mins</div>
                                        </div>
                                    </div>
                                    <div class="bg-light-3 rounded-8 px-30 py-20 mt-15">
                                        How likely are you to recommend our company to your friends and family?
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row x-gap-10 y-gap-10 items-center justify-end">
                                        <div class="col-auto">
                                            <div class="text-14 lh-1 mr-3">35 mins</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-15 lh-12 fw-500 text-dark-1">You</div>
                                        </div>
                                        <div class="col-auto">
                                            <img src="/img/dashboard/right-sidebar/messages-2/3.png" alt="image">
                                        </div>
                                    </div>
                                    <div
                                        class="text-right bg-light-7 -dark-bg-dark-2 text-purple-1 rounded-8 px-30 py-20 mt-15">
                                        How likely are you to recommend our company to your friends and family?
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row x-gap-10 y-gap-10 items-center">
                                        <div class="col-auto">
                                            <img src="/img/dashboard/right-sidebar/messages-2/3.png" alt="image">
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-15 lh-12 fw-500 text-dark-1">Cameron Williamson</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-14 lh-1 ml-3">35 mins</div>
                                        </div>
                                    </div>
                                    <div class="bg-light-3 rounded-8 px-30 py-20 mt-15">
                                        Ok, Understood!
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-30 pb-20">
                            <form class="contact-form row y-gap-20"
                                action="https://creativelayers.net/themes/educrat-html/post">

                                <div class="col-12">

                                    <textarea placeholder="Write a message" rows="7"></textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="button -md -purple-1 text-white">Send
                                        Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div data-sidebar-menu-open="contacts" class="sidebar-menu__item -sidebar-menu">
                        <div class="tabs -pills js-tabs">
                            <div class="tabs__controls d-flex js-tabs-controls">

                                <button class="tabs__button px-15 py-8 rounded-8 text-dark-1 js-tabs-button is-active"
                                    data-tab-target=".-tab-item-1" type="button">Contacts</button>

                                <button class="tabs__button px-15 py-8 rounded-8 text-dark-1 js-tabs-button "
                                    data-tab-target=".-tab-item-2" type="button">Request</button>

                            </div>

                            <div class="tabs__content pt-30 js-tabs-content">

                                <div class="tabs__pane -tab-item-1 is-active">
                                    <div class="row x-gap-10 y-gap-10 items-center">
                                        <div class="col-auto">
                                            <img src="/img/dashboard/right-sidebar/contacts/1.png" alt="image">
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-15 lh-12 fw-500 text-dark-1">Darlene Robertson</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tabs__pane -tab-item-2 ">
                                    <div class="row x-gap-10 y-gap-10 items-center">
                                        <div class="col-auto">
                                            <img src="/img/dashboard/right-sidebar/contacts/1.png" alt="image">
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-15 lh-12 fw-500 text-dark-1">Darlene Robertson</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div data-sidebar-menu-open="settings" class="sidebar-menu__item -sidebar-menu">
                        <div class="text-17 text-dark-1 fw-500">Privacy</div>
                        <div class="text-15 mt-5">You can restrict who can message you</div>
                        <div class="mt-30">

                            <div class="form-radio d-flex items-center ">
                                <div class="radio">
                                    <input type="radio">
                                    <div class="radio__mark">
                                        <div class="radio__icon"></div>
                                    </div>
                                </div>
                                <div class="lh-1 text-13 text-dark-1 ml-12">My contacts only</div>
                            </div>


                            <div class="form-radio d-flex items-center mt-15">
                                <div class="radio">
                                    <input type="radio">
                                    <div class="radio__mark">
                                        <div class="radio__icon"></div>
                                    </div>
                                </div>
                                <div class="lh-1 text-13 text-dark-1 ml-12">My contacts and anyone in my courses</div>
                            </div>


                            <div class="form-radio d-flex items-center mt-15">
                                <div class="radio">
                                    <input type="radio">
                                    <div class="radio__mark">
                                        <div class="radio__icon"></div>
                                    </div>
                                </div>
                                <div class="lh-1 text-13 text-dark-1 ml-12">Anyone on the site</div>
                            </div>

                        </div>

                        <div class="text-17 text-dark-1 fw-500 mt-30 mb-30">Notification preferences</div>
                        <div class="form-switch d-flex items-center">
                            <div class="switch">
                                <input type="checkbox">
                                <span class="switch__slider"></span>
                            </div>
                            <div class="text-13 lh-1 text-dark-1 ml-10">Email</div>
                        </div>

                        <div class="text-17 text-dark-1 fw-500 mt-30 mb-30">General</div>
                        <div class="form-switch d-flex items-center">
                            <div class="switch">
                                <input type="checkbox">
                                <span class="switch__slider"></span>
                            </div>
                            <div class="text-13 lh-1 text-dark-1 ml-10">Use enter to send</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    <!-- barba container end -->

    <!-- JavaScript -->
    {{--  <script src="../../../cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    {{--  <script src="../../../unpkg.com/leaflet%401.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script> --}}
    <script src="/js/vendors.js"></script>
    <script src="/js/main.js"></script>
    {{-- <script src="https://cdn.datatables.net/2.1.0/js/dataTables.js"></script> --}}
    <script>
        // Dark Mode Switch
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            const newColorScheme = event.matches ? "dark" : "light";
            const url = "/account/settings/update_profile/light_mode/" + newColorScheme;
            httpGet(url);
            location.reload();
        });

        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // dark mode
            const url = "/account/settings/update_profile/light_mode/dark";
        }

        function httpGet(theUrl) {
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", theUrl, false); // false for synchronous request
            xmlHttp.setRequestHeader('Content-Type', 'application/json');
            xmlHttp.send(JSON.stringify({
                value: "value"
            }));
            return xmlHttp.responseText;
        }

        var stat = null;
        var dd = "No";

        @if (session()->has('status'))
            stat = "{{ session()->get('status') }}";
            dd = "Something Went Wrong!";
        @endif

        @if (session()->has('message'))
            dd = '{!! session()->get('message') !!}';
        @endif
    </script>

    @livewireScripts


    {{-- New JS for Modals --}}
    {{--  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    {{--    <script src="{{url('/')}}/assets/js/pages/crud/file-upload/dropzonejs588.js"></script> --}}

    @yield('page_js')

    <script src="{{ url('/') }}/assets/plugins/global/plugins.bundle15aa.js?v=7.2.2"></script>
    {{--  <script src="{{url('/')}}/assets/js/scripts.bundle15aa.js?v=7.2.2"></script> --}}

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CP9F4QW1JC"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-CP9F4QW1JC');
    </script>

    <script>
        function loading() {
            x = document.getElementById('submit')
            // x.style.visibility  = 'hidden';
            x.show = ".btn .fa-spinner";
            x.innerHTML = "Saving...";
            // x.disabled  = true;
            // document.getElementById("myForm").submit();
        }

        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-HJJJ6S76Q' +
            '' +
            'T');
    </script>

    <script>
        function redirectMe(to_here) {
            window.location = to_here;
        }
    </script>

    @yield('after_js')
    @stack('scripts')
</body>


</html>
