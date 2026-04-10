@extends('layouts.dash')

@section('template_title')
    Transactions
@endsection



@section('content')
    <div class="dashboard__content bg-light-4" style="background-color: #f4f6f6 ">
        <div class="row y-gap-20 justify-between pt-30">
            <div class="col-auto sm:w-1/1">
                <h1 class="text-30 lh-12 fw-700">Transactions</h1>
                <div class="mt-10"><i class="icon icon-clock"></i> <strong> Transactions</strong>
                    {{ date('D d M Y') }}
                </div>
            </div>



            @include('sherehe.dash.includes.alerts')

        </div>


        <div class="row y-gap-30">
            <div class="col-12">

                @include('sherehe.dash.event.tabs.alerts')

                {{-- Include Top side Tab --}}

                <div class="rounded-16 bg-white -dark-bg-dark-1 shadow-4 h-100">
                    <div class="tabs -active-purple-2 js-tabs">
                        <div class="tabs__controls d-flex items-center pt-20 px-30 border-bottom-light js-tabs-controls">

                            {{-- Include Top side Tab --}}
                            <a href="{{ route('transactions.events') }}"
                                class="text-light-1 lh-12 tabs__button js-tabs-button {{ request()->routeIs('transactions.events') ? 'is-active' : '' }}"
                                data-tab-target=".-tab-item-1" type="button">
                                Events
                            </a>

                            <a href="{{ route('transactions.sms') }}"
                                class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('transactions.sms') ? 'is-active' : '' }}"
                                data-tab-target=".-tab-item-2" type="button">
                                SMS
                            </a>

                        </div>

                        <div class="tabs__content py-30 px-30 js-tabs-content">

                            <div class="tabs__pane -tab-item-2 is-active">
                                <div class="row y-gap-30 mb-10 d-flex" style="gap: 15px; flex-wrap: nowrap;">


                                    <h1>Coming Soon</h1>
                                    {{-- <div class="content" id="contentAllPaidTicket">
                                        <div class="table-responsive users-table">
                                            <table class="table table-striped table-sm data-table">
                                                <caption id="user_count"> Sms Transactions
                                                </caption>
                                                <thead class="thead" style="background-color: #d3ccbc;">
                                                    <tr>
                                                        <th style="color: white;">No.</th>
                                                        <th style="color: white;">Name</th>
                                                        <th style="color: white;">Amount</th>
                                                        <th style="color: white;">Paid</th>
                                                        <th style="color: white;">Balance</th>
                                                        <th style="color: white;">Type</th>
                                                        <th style="color: white;">Status</th>
                                                        <th class="text-center" style="color: white;">
                                                            {!! trans('usersmanagement.users-table.actions') !!}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="users_table_ticket">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}


                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <script>
        function redirectMe(to_here) {
            window.location = to_here;
        }
    </script>
@endsection
