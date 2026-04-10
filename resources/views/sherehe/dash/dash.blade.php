@extends('layouts.dash')

@section('template_title')
    Dashboard
@endsection

@section('page_css')
    <link rel="stylesheet" href="/cdn/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/cdn/unpkg/leaflet%401.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
@endsection

@section('content')
    <div class="dashboard__content bg-light-4" style="background-color:#f4f6f6 ">
        <div class="row y-gap-20 justify-between pt-30">
            <div class="col-auto sm:w-1/1">
                <h1 class="text-30 lh-12 fw-700">Dashboard</h1>
            </div>

            <div class="col-auto sm:w-1/1">
                <button data-toggle="modal" data-target="#create_event_pricing"
                    class="button -sm -black text-dark-1 sm:w-1/1" style="background-color: #003366;">Create Event
                </button>
            </div>
        </div>

        <div class="row y-gap-50">
            <div class="col-xl-12 col-lg-12">
                @if ($user->hasRole('user'))
                    <div class="row y-gap-30 mb-20">

                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #d3ccbc">
                                <div>
                                    <div class="lh-1 fw-500">SMS</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">
                                        {{ $my_active_events->sum(function ($event) {
                                            return optional($event->package)->messages ?? 0;
                                        }) }}

                                    </div>
                                    <div class="lh-1 mt-25">Balance: <span class="text-2xl font-bold"
                                            style="color: white">{{ $my_active_events->sum('sms_balance') }}</span>

                                    </div>
                                </div>

                                <i class="text-40 icon-message text-white"></i>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #9aa89b">
                                <div>
                                    <div class="lh-1 fw-500">WhatsApp SMS</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">
                                        {{ $my_active_events->sum('whatsapp_balance') }}</div>
                                    <div class="lh-1 mt-25">Sent: <span class="text-2xl font-bold" style="color: white">
                                            {{ $my_active_events->sum(function ($event) {
                                                return $event->notifications->where('sender_name', 'Whatssap_SMS')->sum('messages');
                                            }) }}
                                        </span></div>
                                </div>

                                <i class="text-40 fa fa-whatsapp text-purple-1"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #e5d7b4">
                                <div>
                                    <div class="lh-1 fw-500">My Events</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">{{ count($user_total_events) }}</div>
                                    <div class="lh-1 mt-25">Active: <span class="text-2xl font-bold"
                                            style="color: white">{{ count($my_active_events) }}</span></div>
                                </div>

                                <i class="text-40 icon-calendar text-white"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #d3d3d3">
                                <div>
                                    <div class="lh-1 fw-500">My Cards</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">
                                        {{ $my_active_events->sum(function ($event) {
                                            return optional($event->package)->digital_cards ?? 0;
                                        }) }}
                                    </div>
                                    <div class="lh-1 mt-25">
                                        {{-- Balance: <span class="text-2xl font-bold" style="color: white">
                                            {{ $my_active_events->sum('card_balance') }}</span> --}}

                                        Sent: <span class="text-2xl font-bold" style="color: white">
                                            {{ $my_active_events->sum(function ($event) {
                                                // Count the pledges where 'card_received' is true for each event
                                                return $event->pledges->where('card_received', true)->count();
                                            }) }}</span>
                                    </div>
                                </div>

                                <i class="text-40 icon-list text-white"></i>
                            </div>
                        </div>

                    </div>
                @endif

                @if ($user->hasRole('admin'))
                    <div class="row y-gap-30 mb-20">

                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #d3ccbc">
                                <div>
                                    <div class="lh-1 fw-500">SMS</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">
                                        {{ $active_events->sum(function ($event) {
                                            return optional($event->package)->messages ?? 0;
                                        }) }}
                                    </div>
                                    <div class="lh-1 mt-25">Balance:
                                        <span class="text-2xl font-bold"
                                            style="color: white">{{ $active_events->sum('sms_balance') }}</span>
                                    </div>
                                </div>

                                <i class="text-40 icon-message text-white"></i>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #9aa89b">
                                <div>
                                    <div class="lh-1 fw-500">WhatsApp SMS</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">
                                        {{ $active_events->sum('whatsapp_balance') }}</div>
                                    <div class="lh-1 mt-25">Sent: <span class="text-2xl font-bold" style="color: white">
                                            {{ $active_events->sum(function ($event) {
                                                return $event->notifications->where('sender_name', 'Whatssap_SMS')->sum('messages');
                                            }) }}
                                        </span>
                                    </div>
                                </div>

                                <i class="text-40 fa fa-whatsapp text-purple-1"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #e5d7b4">
                                <div>
                                    <div class="lh-1 fw-500">Total Events</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">{{ count($active_events) }}</div>
                                    <div class="lh-1 mt-25">Active: <span class="text-2xl font-bold"
                                            style="color: white">{{ $active_events->where('event_date', '>=', \Carbon\Carbon::today())->count() }}
                                        </span></div>
                                </div>

                                <i class="text-40 icon-calendar text-white"></i>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="d-flex justify-between items-center py-35 px-30 rounded-16"
                                style="background-color: #d3d3d3">
                                <div>
                                    <div class="lh-1 fw-500">My Cards</div>
                                    <div class="text-24 lh-1 fw-700 text-white mt-20">
                                        {{ $active_events->sum(function ($event) {
                                            return optional($event->package)->digital_cards ?? 0;
                                        }) }}
                                    </div>
                                    <div class="lh-1 mt-25">
                                        Sent: <span class="text-2xl font-bold" style="color: white">
                                            {{ $active_events->sum(function ($event) {
                                                // Count the pledges where 'card_received' is true for each event
                                                return $event->pledges->where('card_received', true)->count();
                                            }) }}</span>
                                    </div>
                                </div>

                                <i class="text-40 icon-list text-white"></i>
                            </div>
                        </div>

                    </div>
                @endif

                <div class="row y-gap-30 mb-10 d-flex">
                    <div class="col-xl-8 col-md-8 d-flex">
                        <div class="rounded-16 bg-white flex-grow-1">
                            <div style="padding: 5%">
                                <h3 class="text-center">Budget</h3>
                                <canvas id="barChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 d-flex">
                        <div class="rounded-16 bg-white flex-grow-1">
                            <div style="padding: 15%">
                                <h3 class="text-center mb-3" style="padding-bottom: 5%">Pledges</h3>
                                <canvas id="pieChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div class="col-xl-3 col-lg-12">

            </div>
        </div>

    </div>
    @include('sherehe.dash.event.includes.modal_create_event_pricing')
@endsection


@section('page_js')
    <script></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const m_data = [];
        const a_data = [];
        @isset($user->business->id)
            const items = ['Courses', 'Products', 'Events'];
            const amount = [{{ $course_sales }}, {{ $product_sales }}, {{ $event_sales }}];
        @endisset
        {{--        @foreach ($months as $month => $amount) --}}
        {{--            m_data.push('{{$month}}'); --}}
        {{--            a_data.push('{{$amount}}'); --}}
        {{--        @endforeach --}}

        console.log(a_data);
    </script>
    <script src="/cdn/ajax/libs/Chart.js/3.7.1/chart.min.js"
        integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/dcn/unpkg/leaflet%401.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    <script>
        var chartData = @json($budget_graph_data);
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Budget', 'Pledge', 'Collection', 'Expenditure'], // X-axis labels
                datasets: [{
                    label: 'Events Data',
                    data: [chartData.budget, chartData.pledge, chartData.collection, chartData.expenditure],
                    backgroundColor: [
                        '#d3ccbc',
                        '#9aa89b',
                        '#e5d7b4',
                        '#d3d3d3',
                    ],
                    borderColor: [
                        '#d3ccbc',
                        '#9aa89b',
                        '#e5d7b4',
                        '#d3d3d3',
                    ],
                    borderWidth: 1,
                    barThickness: 20, // Adjust this value to reduce bar width
                    maxBarThickness: 20, //
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>


    <script>
        var chartData = @json($chartData);
        var ctx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['complete paid', 'incomplete paid'],
                datasets: [{
                    label: 'Pedge',
                    data: [chartData.complete, chartData.incomplete],
                    backgroundColor: [
                        '#9aa89b',
                        '#e5d7b4',
                    ],
                    borderColor: [
                        '#9aa89b',
                        '#e5d7b4',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endsection
