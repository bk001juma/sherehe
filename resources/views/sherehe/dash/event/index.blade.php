@extends('layouts.dash')

@section('template_title')
    My Events
@endsection

@section('content')
    <div class="dashboard__content bg-light-4" style="background-color: #f2f4f4">
        <div class="row y-gap-20 justify-between pt-30">
            <div class="col-auto sm:w-1/1">
                <h1 class="text-30 lh-12 fw-700">{{ Route::is('dash.events.all') ? 'All' : 'My' }} events</h1>
                {{--            <div class="mt-10">Lorem ipsum dolor sit amet, consectetur.</div> --}}
            </div>

            <div class="col-auto sm:w-1/1">
                <button data-toggle="modal" data-target="#create_event_pricing" class="button -sm -black text-dark-1 sm:w-1/1"
                    style="background-color: #003366;">Create Event
                </button>
            </div>
        </div>


        <div class="row y-gap-30">
            <div class="col-12">
                <div class="rounded-16 bg-white -dark-bg-dark-1 shadow-4 h-100">
                    <div class="tabs -active-purple-2 js-tabs">
                        <div class="tabs__controls d-flex items-center pt-20 px-30 border-bottom-light js-tabs-controls">

                            <button class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 is-active"
                                data-tab-target=".-tab-item-1" type="button">
                                Active Events
                            </button>
                            <button class="text-light-1 lh-12 tabs__button js-tabs-button ml-30"
                                data-tab-target=".-tab-item-2" type="button">
                                Complete Events
                            </button>
                            <button class="text-light-1 lh-12 tabs__button js-tabs-button ml-30"
                                data-tab-target=".-tab-item-3" type="button">
                                All Events
                            </button>

                        </div>

                        <div class="tabs__content py-30 px-30 js-tabs-content">
                            <div class="tabs__pane -tab-item-1 is-active">
                                <div class="row y-gap-30 pt-30">

                                    @foreach ($activeEvents->reverse() as $my_event)
                                        <div class="col-md-6">
                                            <a href="#"
                                                class="relative d-block rounded-8 px-10 py-10 border-dark bg-light-4">
                                                <div class="row x-gap-20 y-gap-20 items-center"
                                                    style="background-color: #f2f4f4 ">
                                                    <div class="col-md-auto">
                                                        <div class="shrink-0">
                                                            <img class="w-1/1 rounded-8" style="height: 150px"
                                                                src="/{{ $my_event->image }}" alt="image">
                                                        </div>
                                                    </div>

                                                    <div class="col-md">
                                                        @if ($my_event->status == 'pending')
                                                            <div class="absolute-bookmark -dark-bg-dark-2 shadow-5">
                                                                <i class="fa fa-exclamation-triangle text-red-1"></i>
                                                            </div>
                                                        @else
                                                            <div class="absolute-bookmark">
                                                                <img src="/logo.png" style="height: 30px"
                                                                    alt="Ubunifu Academy Logo">
                                                            </div>
                                                        @endif

                                                        <h3 class="text-17 lh-16 fw-500 mt-10 pr-40 xl:pr-0">
                                                            {{ $my_event->event_name }}</h3>
                                                        {{-- <div class="w-25 items-center">
                                                            <div class="progress-bar mt-10">
                                                                <div class="progress-bar__bg bg-light-3"></div>
                                                                <div class="progress-bar__bar bg-purple-1"
                                                                    style="width:{{ $my_event->items->sum('amount') > 0 ? ($my_event->items->sum('paid') / $my_event->items->sum('amount')) * 100 : 0 }}%">
                                                                </div>
                                                            </div>
                                                            <div class="d-flex y-gap-10 justify-between items-center mt-10">
                                                                <div class="text-dark-1">
                                                                    {{ $my_event->items->sum('amount') > 0 ? ($my_event->items->sum('paid') / $my_event->items->sum('amount')) * 100 : 0 }}
                                                                    % Collected</div>

                                                            </div>
                                                        </div> --}}


                                                        {{-- <div class="d-flex x-gap-20 y-gap-5 items-center flex-wrap pt-10">

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-person-2"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">{{ $my_event->pledges->count() }}
                                                                    Pledges</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-save-money"></i>
                                                                </div>
                                                                <div class="text-14 lh-1 text-success">
                                                                    {{ number_format($my_event->pledges->sum('paid')) }} TZS
                                                                    Paid</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-save-money"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">
                                                                    {{ number_format($my_event->items->sum('amount')) }} TZS
                                                                    Budget</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-email"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">
                                                                    {{ number_format($my_event->package->attendees) }} Cards
                                                                </div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <img class="mt-0" style="height: 20px"
                                                                        src="/img/pricing/{{ $my_event->package->id }}.svg"
                                                                        alt="icon">
                                                                </div>
                                                                <div class="text-14 lh-1">{{ $my_event->package->name }}
                                                                </div>
                                                            </div>


                                                        </div> --}}

                                                        <div
                                                            class="d-flex y-gap-10 justify-between items-center flex-wrap border-top-light pt-10 mt-10">
                                                            <div class="d-flex items-center">
                                                                <i class="icon icon-location"></i>
                                                                <div class="text-14 lh-1 ml-10">{{ $my_event->location }}
                                                                </div>
                                                                <div class="text-14 lh-1 ml-10">
                                                                    {{ date('D d M Y', strtotime($my_event->event_date)) }}
                                                                </div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="shopCart-footer__item">

                                                                    <button class="button -sm mr-5" data-toggle="modal"
                                                                        data-target="#edit_event_{{ $my_event->id }}"><i
                                                                            class="fa fa-edit"
                                                                            style="color: orange"></i></button>
                                                                    <button class="button -sm text-red-1"
                                                                        data-toggle="modal"
                                                                        data-target="#delete_class_{{ $my_event->id }}"><i
                                                                            class="fa fa-trash" style="color: orange"></i>
                                                                    </button>
                                                                    <button
                                                                        onclick="redirectMe('{{ route('dash.event', [$my_event->id]) }}')"
                                                                        class="button -sm -purple-3 text-purple-1">Manage</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tabs__pane -tab-item-2">
                                <div class="row y-gap-30 pt-30">

                                    @foreach ($completedEvents->reverse() as $my_event)
                                        <div class="col-md-12">
                                            <a href="#"
                                                class="relative d-block rounded-8 px-10 py-10 border-dark bg-light-4">
                                                <div class="row x-gap-20 y-gap-20 items-center">
                                                    <div class="col-md-auto">
                                                        <div class="shrink-0">
                                                            <img class="w-1/1 rounded-8" style="height: 150px"
                                                                src="/{{ $my_event->image }}" alt="image">
                                                        </div>
                                                    </div>

                                                    <div class="col-md">
                                                        @if ($my_event->status == 'pending')
                                                            <div class="absolute-bookmark -dark-bg-dark-2 shadow-5">
                                                                <i class="fa fa-exclamation-triangle text-red-1"></i>
                                                            </div>
                                                        @endif

                                                        <h3 class="text-17 lh-16 fw-500 mt-10 pr-40 xl:pr-0">
                                                            {{ $my_event->event_name }}</h3>
                                                        <div class="w-25 items-center">
                                                            <div class="progress-bar mt-10">
                                                                <div class="progress-bar__bg bg-light-3"></div>
                                                                <div class="progress-bar__bar bg-purple-1"
                                                                    style="width:{{ $my_event->items->sum('amount') > 0 ? ($my_event->items->sum('paid') / $my_event->items->sum('amount')) * 100 : 0 }}%">
                                                                </div>
                                                            </div>
                                                            <div class="d-flex y-gap-10 justify-between items-center mt-10">
                                                                <div class="text-dark-1">
                                                                    {{ $my_event->items->sum('amount') > 0 ? ($my_event->items->sum('paid') / $my_event->items->sum('amount')) * 100 : 0 }}
                                                                    % Collected</div>
                                                                {{--                                                                <div>{{number_format($my_event->items->sum('amount'))}} TZS</div> --}}
                                                            </div>
                                                        </div>


                                                        <div class="d-flex x-gap-20 y-gap-5 items-center flex-wrap pt-10">

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-person-2"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">{{ $my_event->pledges->count() }}
                                                                    Pledges</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-save-money"></i>
                                                                </div>
                                                                <div class="text-14 lh-1 text-success">
                                                                    {{ number_format($my_event->pledges->sum('paid')) }}
                                                                    TZS
                                                                    Paid</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-save-money"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">
                                                                    {{ number_format($my_event->items->sum('amount')) }}
                                                                    TZS
                                                                    Budget</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-email"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">
                                                                    {{ number_format($my_event->package->attendees) }}
                                                                    Cards
                                                                </div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <img class="mt-0" style="height: 20px"
                                                                        src="/img/pricing/{{ $my_event->package->id }}.svg"
                                                                        alt="icon">
                                                                </div>
                                                                <div class="text-14 lh-1">{{ $my_event->package->name }}
                                                                </div>
                                                            </div>


                                                        </div>

                                                        <div
                                                            class="d-flex y-gap-10 justify-between items-center flex-wrap border-top-light pt-10 mt-10">
                                                            <div class="d-flex items-center">
                                                                <i class="icon icon-location"></i>
                                                                <div class="text-14 lh-1 ml-10">{{ $my_event->location }}
                                                                </div>
                                                                <div class="text-14 lh-1 ml-10">
                                                                    {{ date('D d M Y', strtotime($my_event->event_date)) }}
                                                                </div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="shopCart-footer__item">
                                                                    <button class="button -sm -purple-3 text-purple-1 mr-5"
                                                                        data-toggle="modal"
                                                                        data-target="#edit_event_{{ $my_event->id }}">Edit</button>
                                                                    <button
                                                                        onclick="redirectMe('{{ route('dash.event', [$my_event->id]) }}')"
                                                                        class="button -sm -purple-3 text-purple-1 mr-5">Manage</button>
                                                                    <button class="button -sm -outline-red-1 text-red-1"
                                                                        data-toggle="modal"
                                                                        data-target="#delete_class_{{ $my_event->id }}">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tabs__pane -tab-item-3">

                                <div class="row y-gap-30 pt-30">

                                    @foreach ($events->reverse() as $my_event)
                                        <div class="col-md-12">
                                            <a href="#"
                                                class="relative d-block rounded-8 px-10 py-10 border-dark bg-light-4">
                                                <div class="row x-gap-20 y-gap-20 items-center">
                                                    <div class="col-md-auto">
                                                        <div class="shrink-0">
                                                            <img class="w-1/1 rounded-8" style="height: 150px"
                                                                src="/{{ $my_event->image }}" alt="image">
                                                        </div>
                                                    </div>

                                                    <div class="col-md">
                                                        @if ($my_event->status == 'pending')
                                                            <div class="absolute-bookmark -dark-bg-dark-2 shadow-5">
                                                                <i class="fa fa-exclamation-triangle text-red-1"></i>
                                                            </div>
                                                        @endif

                                                        <h3 class="text-17 lh-16 fw-500 mt-10 pr-40 xl:pr-0">
                                                            {{ $my_event->event_name }}</h3>
                                                        <div class="w-25 items-center">
                                                            <div class="progress-bar mt-10">
                                                                <div class="progress-bar__bg bg-light-3"></div>
                                                                <div class="progress-bar__bar bg-purple-1"
                                                                    style="width:{{ $my_event->items->sum('amount') > 0 ? ($my_event->items->sum('paid') / $my_event->items->sum('amount')) * 100 : 0 }}%">
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex y-gap-10 justify-between items-center mt-10">
                                                                <div class="text-dark-1">
                                                                    {{ $my_event->items->sum('amount') > 0 ? ($my_event->items->sum('paid') / $my_event->items->sum('amount')) * 100 : 0 }}
                                                                    % Collected</div>
                                                                {{--                                                                <div>{{number_format($my_event->items->sum('amount'))}} TZS</div> --}}
                                                            </div>
                                                        </div>


                                                        <div class="d-flex x-gap-20 y-gap-5 items-center flex-wrap pt-10">

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-person-2"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">
                                                                    {{ $my_event->pledges->count() }}
                                                                    Pledges</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-save-money"></i>
                                                                </div>
                                                                <div class="text-14 lh-1 text-success">
                                                                    {{ number_format($my_event->pledges->sum('paid')) }}
                                                                    TZS
                                                                    Paid</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-save-money"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">
                                                                    {{ number_format($my_event->items->sum('amount')) }}
                                                                    TZS
                                                                    Budget</div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <i class="icon icon-email"></i>
                                                                </div>
                                                                <div class="text-14 lh-1">
                                                                    {{ number_format($my_event->package->attendees) }}
                                                                    Cards
                                                                </div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="mr-10">
                                                                    <img class="mt-0" style="height: 20px"
                                                                        src="/img/pricing/{{ $my_event->package->id }}.svg"
                                                                        alt="icon">
                                                                </div>
                                                                <div class="text-14 lh-1">{{ $my_event->package->name }}
                                                                </div>
                                                            </div>


                                                        </div>

                                                        <div
                                                            class="d-flex y-gap-10 justify-between items-center flex-wrap border-top-light pt-10 mt-10">
                                                            <div class="d-flex items-center">
                                                                <i class="icon icon-location"></i>
                                                                <div class="text-14 lh-1 ml-10">{{ $my_event->location }}
                                                                </div>
                                                                <div class="text-14 lh-1 ml-10">
                                                                    {{ date('D d M Y', strtotime($my_event->event_date)) }}
                                                                </div>
                                                            </div>

                                                            <div class="d-flex items-center">
                                                                <div class="shopCart-footer__item">
                                                                    <button class="button -sm -purple-3 text-purple-1 mr-5"
                                                                        data-toggle="modal"
                                                                        data-target="#edit_event_{{ $my_event->id }}">Edit</button>
                                                                    <button
                                                                        onclick="redirectMe('{{ route('dash.event', [$my_event->id]) }}')"
                                                                        class="button -sm -purple-3 text-purple-1 mr-5">Manage</button>
                                                                    <button class="button -sm -outline-red-1 text-red-1"
                                                                        data-toggle="modal"
                                                                        data-target="#delete_class_{{ $my_event->id }}">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row justify-center pt-30">
                                    {{--                                    <div class="col-auto"> --}}
                                    {{--                                        <div class="pagination -buttons"> --}}
                                    {{--                                            <button class="pagination__button -prev"> --}}
                                    {{--                                                <i class="icon icon-chevron-left"></i> --}}
                                    {{--                                            </button> --}}

                                    {{--                                            <div class="pagination__count"> --}}
                                    {{--                                                <a href="#">1</a> --}}
                                    {{--                                                <a class="-count-is-active" href="#">2</a> --}}
                                    {{--                                                <a href="#">3</a> --}}
                                    {{--                                                <span>...</span> --}}
                                    {{--                                                <a href="#">67</a> --}}
                                    {{--                                            </div> --}}

                                    {{--                                            <button class="pagination__button -next"> --}}
                                    {{--                                                <i class="icon icon-chevron-right"></i> --}}
                                    {{--                                            </button> --}}
                                    {{--                                        </div> --}}
                                    {{--                                    </div> --}}
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('sherehe.dash.event.includes.delete_event_modal')
    @include('sherehe.dash.event.includes.modal_create_event_pricing')
    @include('sherehe.dash.event.includes.modal_event_edit')
@endsection

@section('page_css')
    <meta http-equiv="cache-control" content="no-cache" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
@endsection

@section('page_js')
    <script>
        function family(val) {
            var x = document.getElementById('family_name')
            if (val === '1') {
                x.style.display = 'none';
            } else {
                x.style.display = 'block';
            }
        }

        $(function() {
            $("#checkin").datepicker();
            $("#event_date").datepicker();
        });
    </script>
@endsection
{{-- @php --}}
{{--    function toTime($seconds) { --}}
{{--      $t = round($seconds); --}}
{{--      if ($seconds > 3600) --}}
{{--          return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60); --}}

{{--      return sprintf('%02d:%02d', ($t/60%60), $t%60); --}}
{{--    } --}}
{{-- @endphp --}}
