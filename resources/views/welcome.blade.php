@extends('layouts.sherehe')


@section('content')
    <div class="content-wrapper  js-content-wrapper">

        <section class="masthead -type-1 js-mouse-move-container" style="height: 1000px">
            <div class="masthead__bg" style="background-color: black">
                <img src="/images/bg2.jpg" style="opacity: 0.5" alt="image">
            </div>

            <div class="container" style="margin-top: 100px">
                <div data-anim-wrap class="row y-gap-30 justify-between items-end">
                    <div class="col-xl-6 col-lg-6 col-sm-10">
                        <div class="masthead__content">
                            <h1 data-anim-child="slide-up" class="masthead__title">
                                Digital home for a memorable
                                <span class="text-white ">Event</span>
                            </h1>
                            <p data-anim-child="slide-up delay-1" class="masthead__text">
                                Plan your event, WE HELP TO DIGITIZE, give you and your guests a historical EXPERIENCE.
                            </p>
                            <div data-anim-child="slide-up delay-2" class="masthead__buttons row x-gap-10 y-gap-10">
                                <div class="col-12 col-sm-auto">
                                    <a data-barba href="/home" class="button -md -dark-5 text-white">Create Event</a>
                                </div>
                                <div class="col-12 col-sm-auto">
                                    <a data-barba href="/home" class="button -md -dark-5 text-white">Digital Cards</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div data-anim-child="slide-up delay-5" class="col-xl-6 col-lg-6">
                        <div class="masthead-image">
                            <div class="masthead-image__el1">
                                {{--                                <img class="js-mouse-move" data-move="40" src="/images/fd1.jpg" style="width: 200px" alt="image"> --}}

                                <div data-move="10"
                                    class="lg:d-none img-el -w-250 px-20 py-20 d-flex items-center bg-white rounded-8 js-mouse-move">
                                    <div class="size-50 d-flex justify-center items-center bg-red-2 rounded-full">
                                        <img src="/img/masthead/messages.gif" alt="icon">
                                        {{-- <i class="icon icon-message" style="color: white"></i> --}}
                                    </div>
                                    <div class="ml-20">
                                        <div class="text-orange-1 text-16 fw-500 lh-1">100,000 +</div>
                                        <div class="mt-3">Bulk SMS Sent</div>
                                    </div>
                                </div>
                            </div>

                            <div class="masthead-image__el1">
                                {{--                                <img class="js-mouse-move" data-move="40" src="/images/fd1.jpg" style="width: 200px" alt="image"> --}}

                                <div data-move="30"
                                    class="lg:d-none img-el -w-250 px-20 py-20 d-flex items-center bg-white rounded-8 js-mouse-move">
                                    <div class="size-50 d-flex justify-center items-center bg-red-2 rounded-full">
                                        <img src="/img/masthead/event_card.gif" alt="icon">
                                    </div>
                                    <div class="ml-20">
                                        <div class="text-orange-1 text-16 fw-500 lh-1">3,000 +</div>
                                        <div class="mt-3">Events Done</div>
                                    </div>
                                </div>
                            </div>

                            <div class="masthead-image__el2">
                                {{--                                <img class="js-mouse-move" data-move="70" src="/images/fd1.jpg" style="width: 200px" alt="image"> --}}

                                <div data-move="60"
                                    class="lg:d-none img-el -w-260 px-20 py-20 d-flex items-center bg-white rounded-8 js-mouse-move">
                                    <div class="size-50 d-flex justify-center items-center bg-red-2 rounded-full">
                                        <img src="/img/masthead/vendors.gif" alt="icon">
                                    </div>
                                    <div class="ml-20">
                                        <div class="text-orange-1 text-16 fw-500 lh-1">500 +</div>
                                        <div class="mt-3">Active vendors</div>
                                    </div>
                                </div>
                            </div>

                            <div class="masthead-image__el3">
                                {{--                                <img class="js-mouse-move" data-move="40" src="/img/masthead/3.png" alt="image"> --}}

                                <div data-move="30"
                                    class="shadow-4 img-el -w-260 px-30 py-20 d-flex items-center bg-white rounded-8 js-mouse-move">
                                    <div class="size-50 d-flex justify-center items-center bg-red-2 rounded-full">
                                        <img src="/img/masthead/digita_card.gif" alt="icon">
                                    </div>
                                    <div class="ml-20">
                                        <div class="text-orange-1 text-16 fw-500 lh-1">10,000 +</div>
                                        <div class="mt-3">Digital Cards</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <svg class="svg-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>
                <g class="svg-waves__parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" />
                    <use xlink:href="#gentle-wave" x="48" y="3" />
                    <use xlink:href="#gentle-wave" x="48" y="5" />
                    <use xlink:href="#gentle-wave" x="48" y="7" />
                </g>
            </svg>
        </section>

        <section class="page-header -type-1 pb-0">
            <div class="container">
                <div class="page-header__content">
                    <div class="row justify-center text-center">
                        <div class="col-auto">
                            <div data-anim="slide-up delay-1">
                                <h1 class="page-header__title">Packages</h1>
                            </div>

                            <div data-anim="slide-up delay-2">
                                <p class="page-header__text">We’re on a mission to deliver engaging, curated courses at a
                                    reasonable price.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="layout-pt-sm layout-pb-md pt-0">
            <div data-anim-wrap class="container">


                <div class="row y-gap-30 justify-between pt-20 lg:pt-20">
                    @foreach ($packages as $package)
                        <div class="col-lg-4 col-md-6">
                            <div class="priceCard -type-1 rounded-16 bg-white shadow-2">
                                <div class="priceCard__content py-45 px-60 text-center">
                                    <div class="priceCard__type text-18 lh-11 fw-500 text-dark-1" style="color: #003366">
                                        {{ $package->name }}</div>
                                    <div class="priceCard__price text-45 lh-11 fw-700 text-dark-1 mt-15">
                                        <span style="font-size: 0.3em">Tsh</span> {{ number_format($package->price) }}
                                    </div>
                                    {{-- <div class="priceCard__period">{{number_format($package->price)}}</div> --}}
                                    {{-- <img class="mt-30" src="/img/pricing/{{$package->id}}.svg" alt="icon"> --}}
                                    {{--                                    <div class="priceCard__text text-left pr-15 mt-40">{{$package->desc}}</div> --}}

                                    <div class="text-left y-gap-15 mt-35">
                                        <div>
                                            <i class="text-purple-1 pr-8" data-feather="check"></i>
                                            We provide budget dashboard for self management of the event.
                                        </div>

                                        <div>
                                            <i class="text-purple-1 pr-8" data-feather="check"></i>
                                            SMS Reminders during fund raising for the event.
                                        </div>

                                        <div>
                                            <i class="text-purple-1 pr-8" data-feather="check"></i>
                                            Designing & Distribution of E - Cards.
                                        </div>

                                        @if (!empty($package->optional_field_1))
                                            <div>
                                                <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                {!! $package->optional_field_1 !!}
                                            </div>
                                        @endif

                                        @if (!empty($package->optional_field_2))
                                            <div>
                                                <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                {{ $package->optional_field_2 }}
                                            </div>
                                        @endif

                                        @if (!empty($package->optional_field_3))
                                            <div>
                                                <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                {{ $package->optional_field_3 }}
                                            </div>
                                        @endif


                                    </div>

                                    <div class="d-inline-block mt-30">
                                        <a class="button -md -purple-3 text-dark-5" href="/home">Get Started</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>

        <section class="layout-pt-lg layout-pb-lg bg-light-3">
            <div data-anim-wrap class="container">
                <div class="row y-gap-20 items-center">
                    <div class="col-xl-7 col-lg-7">
                        <div data-anim-child="slide-up delay-1" class="app-image">
                            <img src="/img/app/1.png" alt="image">
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="app-content">
                            <h2 data-anim-child="slide-up delay-3" class="app-content__title">Create Events<br>
                                <span>Anywhere</span></h2>
                            <p data-anim-child="slide-up delay-4" class="app-content__text">Create Event on the go with
                                the {{ config('app.name', Lang::get('titles.app')) }} app.</p>
                            <div data-anim-child="slide-up delay-5" class="app-content__buttons">
                                <a href="#"><img src="/img/app/buttons/1.svg" alt="button"></a>
                                <a href="#"><img src="/img/app/buttons/2.svg" alt="button"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
