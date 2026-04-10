@extends('layouts.dash')

@section('template_title')
    {{ $event->event_name }}
@endsection



@section('content')
    <div class="dashboard__content bg-light-4" style="background-color: #f4f6f6 ">
        <div class="row y-gap-20 justify-between pt-30">
            <div class="col-auto sm:w-1/1">
                <h1 class="text-30 lh-12 fw-700">{{ $event->event_name }}</h1>
                <div class="mt-10"><i class="icon icon-location"></i> <strong> {{ $event->location }}</strong>
                    {{ date('D d M Y', strtotime($event->event_date)) }}</div>
            </div>

            <div class="col-auto sm:w-1/1 d-flex gap-3">

                @include('sherehe.dash.event.tabs.delete-all-pledges', ['eventId' => $event->id])



            </div>

        </div>


        <div class="row y-gap-30">
            <div class="col-12">
                @include('sherehe.dash.event.tabs.alerts')


                <div class="rounded-16 bg-white -dark-bg-dark-1 shadow-4 h-100">
                    <div class="tabs -active-purple-2 js-tabs">
                        <div class="tabs__controls d-flex items-center pt-20 px-30 border-bottom-light js-tabs-controls">

                            {{-- Include Top side Tab --}}
                            @include('sherehe.dash.event.tabs.top_side_tab', ['eventId' => $event->id])

                        </div>

                        <div class="tabs__content py-30 px-30 js-tabs-content">


                            <div class="tabs__pane -tab-item-3 is-active">
                                <div class="row y-gap-20 justify-between pt-5 mb-4">
                                    <div class="col-auto sm:w-1/1">
                                        <h1 class="text-30 lh-12 fw-700">Event Pledges</h1>
                                        <div class="mt-10">

                                        </div>
                                    </div>

                                    <div class="col-auto sm:w-1/1 d-flex gap-3">
                                        <button data-toggle="modal" data-target="#add_pledge"
                                            class="button -pulse -sm -black text-blue-1 sm:w-1/1 mr-2">
                                            <i class="icon icon-email mr-5"></i> Add Pledge
                                        </button>
                                        <a class="button -sm -dark-5 text-blue-1 mr-5"
                                            href="{{ route('dash.event.card.send.invitation.all', [$event->id]) }}"
                                            id="invitationButton"
                                            onclick="handleInvitationClick(event, 'invitationButton')">
                                            Invitation to All
                                        </a>
                                    </div>
                                </div>

                                <div class="row"> <!-- Pledged Amount -->
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <div class="card d-flex justify-content-between align-items-center py-35 px-30 rounded-16"
                                            style="background-color: #d3ccbc;">

                                            <table class="max-auto">
                                                <tr>
                                                    <td>Pledged:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS {{ number_format($event->pledges->sum('amount')) }}</h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Collected:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS {{ number_format($event->pledges->sum('paid')) }}</h6>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div> <!-- Collected Amount -->
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <div class="card d-flex justify-content-between align-items-center py-35 px-30 rounded-16"
                                            style="background-color: #9aa89b;">

                                            <table class="max-auto">
                                                <tr>
                                                    <td>Paid:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS {{ number_format($event->pledges->sum('paid')) }}</h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Not Paid:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS
                                                            {{ number_format(($event->pledges->sum('amount') ?? 0) - ($event->pledges->sum('paid') ?? 0)) }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <div class="card d-flex justify-content-between align-items-center py-35 px-30 rounded-16"
                                            style="background-color: #e5d7b4;">

                                            <table class="max-auto">
                                                <tr>
                                                    <td>Full Paid:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS
                                                            {{ number_format($event->complete_paid_pledges->sum('paid')) }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Incomplete:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS
                                                            {{ number_format($event->incomplete_paid_pledges->sum('paid')) }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Collected Amount -->
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <div class="card d-flex justify-content-between align-items-center py-35 px-30 rounded-16"
                                            style="background-color: #d3d3d3;">

                                            <table class="max-auto">
                                                <tr>
                                                    <td>Invitation Card:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ $event->pledges->filter(function ($pledge) use ($event) {
                                                                    return ($pledge->paid >= $event->card_types->single_amount &&
                                                                        $pledge->paid < $event->card_types->double_amount) ||
                                                                        $pledge->paid >= $event->card_types->double_amount;
                                                                })->count() }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sent Card:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ $event->pledges->where('card_received', true)->count() }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <div></div>

                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <label for="categorySelect"
                                            style="font-weight: bold; color: rgba(161,194,53,255);">Select
                                            Category:</label>
                                        <select id="categorySelect" class="form-control" onchange="showCategory(this.value)"
                                            style="border-radius: 5px; padding: 10px; border: 1px solid rgba(161,194,53,255);">
                                            <option value="all">All Pledges</option>
                                            @foreach ($event->attendeesCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-7"></div>

                                    <!-- Add the Search Input -->
                                    <div class="col-md-3 mb-4">
                                        <label for="searchPledge" style="font-weight: bold;">Search Pledger:</label>
                                        <input type="text" id="searchPledge" class="form-control"
                                            placeholder="Search by name or phone" onkeyup="filterPledges()"
                                            style="border-radius: 5px; padding: 10px; border: 1px solid rgba(161,194,53,255);">
                                    </div>
                                </div>


                                <div class="content" id="contentAll">
                                    <div class="table-responsive users-table">
                                        <table class="table table-striped table-sm data-table">
                                            <caption id="user_count">{{ $event->pledges->count() }} Pledges
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
                                                    <th class="text-center" style="color: white;">{!! trans('usersmanagement.users-table.actions') !!}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="users_table">
                                                @foreach ($event->pledges as $pledge)
                                                    <tr class="pledge-row" data-category="{{ $pledge->category_id }}">
                                                        <td class="p-2 text-center">{{ $loop->iteration }}</td>
                                                        <td class="p-2">
                                                            <p>{{ $pledge->full_name }}</p>
                                                            <a href="tel:{{ $pledge->phone }}">{{ $pledge->phone }}</a>
                                                        </td>
                                                        <td class="p-2">{{ number_format($pledge->amount) }}</td>
                                                        <td class="p-2">{{ number_format($pledge->paid) }}</td>
                                                        <td class="p-2">
                                                            {{ number_format($pledge->amount - $pledge->paid) }}</td>
                                                        <td class="p-2">
                                                            {{ $pledge->paid >= $event->card_types->single_amount && $pledge->paid < $event->card_types->double_amount
                                                                ? 'Single'
                                                                : ($pledge->paid >= $event->card_types->double_amount
                                                                    ? 'Double'
                                                                    : 'Incomplete') }}
                                                        </td>


                                                        <td class="p-2">
                                                            <span
                                                                class="badge badge-{{ $pledge->amount > $pledge->paid ? 'warning' : 'success' }}">
                                                                {{ $pledge->amount > $pledge->paid ? 'Pending' : 'Paid' }}
                                                            </span>
                                                        </td>
                                                        <td class="p-2">
                                                            <div class="shopCart-footer__item">

                                                                <button
                                                                    class="button -sm text-purple-1 mr-5 d-flex align-items-center justify-content-center"
                                                                    data-toggle="modal"
                                                                    style="background-color: #9aa89b; color: white; padding: 5px 10px; border: none; border-radius: 5px;"
                                                                    data-target="#pledge_pay_{{ $pledge->id }}">
                                                                    <i class="fa fa-plus" style="margin-right: 5px;"></i>
                                                                    Payment
                                                                </button>

                                                                @if (
                                                                    ($pledge->paid >= $event->card_types->single_amount && $pledge->paid < $event->card_types->double_amount) ||
                                                                        $pledge->paid >= $event->card_types->double_amount)
                                                                    @if ($pledge->card_received)
                                                                        <a class="button -sm -black text-blue-1 mr-5"
                                                                            style="width: 200px;text-align: center;"
                                                                            href="{{ route('dash.event.card.send.invitation', [$pledge->id]) }}"
                                                                            id="resendInvitationButton"
                                                                            onclick="handleInvitationClick(event, 'resendInvitationButton')">
                                                                            Resend Card
                                                                        </a>
                                                                    @else
                                                                        <a class="button -sm -dark-5 text-blue-1 mr-5"
                                                                            style="width: 200px;text-align: center;"
                                                                            href="{{ route('dash.event.card.send.invitation', [$pledge->id]) }}"
                                                                            id="inviteCardButton"
                                                                            onclick="handleInvitationClick(event, 'inviteCardButton')">
                                                                            Invitation Card
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <a class="button -sm -dark-5 text-gray-500 mr-5"
                                                                        href=""
                                                                        style="pointer-events: none; opacity: 0.5;width: 200px;text-align: center;">
                                                                        Invitation Card
                                                                    </a>
                                                                @endif

                                                                <button class="button -sm text-purple-1 mr-5"
                                                                    data-toggle="modal"
                                                                    data-target="#pledge_edit_{{ $pledge->id }}"><i
                                                                        class="fa fa-edit"></i></i></button>
                                                                {{-- @if (!$pledge->card_received) --}}
                                                                <button class="button -sm text-red-1" data-toggle="modal"
                                                                    data-target="#pledge_delete_{{ $pledge->id }}"><i
                                                                        class="fa fa-trash"
                                                                        style="color:orange"></i></button>
                                                                {{-- @endif --}}

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @foreach ($event->attendeesCategories as $category)
                                    <div class="content category-content" id="contentCategory{{ $category->id }}"
                                        style="display: none;">
                                        <h3>{{ $category->name }}</h3>
                                        <div class="table-responsive users-table">
                                            <table class="table table-striped table-sm data-table">
                                                <caption id="user_count">{{ $category->eventAttendees()->count() }}
                                                    Pledges in {{ $category->name }}</caption>
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
                                                            {!! trans('usersmanagement.users-table.actions') !!}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($category->eventAttendees as $pledge)
                                                        <tr>
                                                            <td class="p-2 text-center">{{ $loop->iteration }}</td>
                                                            <td class="p-2">
                                                                <p>{{ $pledge->full_name }}</p>
                                                                <a
                                                                    href="tel:{{ $pledge->phone }}">{{ $pledge->phone }}</a>
                                                            </td>
                                                            <td class="p-2">{{ number_format($pledge->amount) }}</td>
                                                            <td class="p-2">{{ number_format($pledge->paid) }}</td>
                                                            <td class="p-2">
                                                                {{ number_format($pledge->amount - $pledge->paid) }}</td>
                                                            <td class="p-2">
                                                                {{ $pledge->paid >= $event->card_types->single_amount && $pledge->paid < $event->card_types->double_amount
                                                                    ? 'Single'
                                                                    : ($pledge->paid >= $event->card_types->double_amount
                                                                        ? 'Double'
                                                                        : 'Incomplete') }}
                                                            </td>


                                                            <td class="p-2">
                                                                <span
                                                                    class="badge badge-{{ $pledge->amount > $pledge->paid ? 'warning' : 'success' }}">
                                                                    {{ $pledge->amount > $pledge->paid ? 'Pending' : 'Paid' }}
                                                                </span>
                                                            </td>
                                                            <td class="p-2">
                                                                <div class="shopCart-footer__item">

                                                                    <button
                                                                        class="button -sm text-purple-1 mr-5 d-flex align-items-center justify-content-center"
                                                                        data-toggle="modal"
                                                                        style="background-color: #9aa89b; color: white; padding: 5px 10px; border: none; border-radius: 5px;"
                                                                        data-target="#pledge_pay_{{ $pledge->id }}">
                                                                        <i class="fa fa-plus"
                                                                            style="margin-right: 5px;"></i>
                                                                        Payment
                                                                    </button>

                                                                    @if (
                                                                        ($pledge->paid >= $event->card_types->single_amount && $pledge->paid < $event->card_types->double_amount) ||
                                                                            $pledge->paid >= $event->card_types->double_amount)
                                                                        @if ($pledge->card_received)
                                                                            <a class="button -sm -black text-blue-1 mr-5"
                                                                                style="width: 200px;text-align: center;"
                                                                                href="{{ route('dash.event.card.send.invitation', [$pledge->id]) }}"
                                                                                id="resendInvitationButton1"
                                                                                onclick="handleInvitationClick(event, 'resendInvitationButton1')">
                                                                                Resend Card
                                                                            </a>
                                                                        @else
                                                                            <a class="button -sm -dark-5 text-blue-1 mr-5"
                                                                                style="width: 200px;text-align: center;"
                                                                                href="{{ route('dash.event.card.send.invitation', [$pledge->id]) }}"
                                                                                id="inviteCardButton1"
                                                                                onclick="handleInvitationClick(event, 'inviteCardButton1')">
                                                                                Invitation Card
                                                                            </a>
                                                                        @endif
                                                                    @else
                                                                        <a class="button -sm -dark-5 text-gray-500 mr-5"
                                                                            href=""
                                                                            style="pointer-events: none; opacity: 0.5;width: 200px;text-align: center;">
                                                                            Invitation Card
                                                                        </a>
                                                                    @endif


                                                                    <button class="button -sm text-purple-1 mr-5"
                                                                        data-toggle="modal"
                                                                        data-target="#pledge_edit_{{ $pledge->id }}"><i
                                                                            class="fa fa-edit"></i></button>
                                                                    {{-- @if (!$pledge->card_received) --}}
                                                                    <button class="button -sm text-red-1"
                                                                        data-toggle="modal"
                                                                        data-target="#pledge_delete_{{ $pledge->id }}"><i
                                                                            class="fa fa-trash"
                                                                            style="color:orange"></i></button>
                                                                    {{-- @endif --}}

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('sherehe.dash.event.includes.modal_add_item')
    @include('sherehe.dash.event.includes.modal_item_edit')
    @include('sherehe.dash.event.includes.modal_item_pay')
    @include('sherehe.dash.event.includes.modal_item_delete')

    @include('sherehe.dash.event.includes.modal_pledge_add')
    @include('sherehe.dash.event.includes.modal_pledge_category_add')

    @include('sherehe.dash.event.includes.modal_pledge_edit')
    @include('sherehe.dash.event.includes.modal_pledge_category_edit')
    @include('sherehe.dash.event.includes.modal_pledge_pay')
    @include('sherehe.dash.event.includes.modal_pledge_delete')
    @include('sherehe.dash.event.includes.modal_pledge_category_delete')

    @include('sherehe.dash.event.includes.modal_card_price')
    @include('sherehe.dash.event.includes.modal_upload_card_design')
    @include('sherehe.dash.event.includes.modal_upload_card_welcome_note')

    @include('sherehe.dash.event.includes.modal_ticket_price')
    @include('sherehe.dash.event.includes.modal_upload_ticket_design')
    @include('sherehe.dash.event.includes.modal_upload_ticket_welcome_note')


    @include('sherehe.dash.event.includes.modal_notify_pledges')
    @include('sherehe.dash.event.includes.modal_whatssap_notify_pledges')


    @include('sherehe.dash.event.includes.modal_buy_sms')
    @include('sherehe.dash.event.includes.modal_buy_whatsapp_sms')

    <script>
        function redirectMe(to_here) {
            window.location = to_here;
        }
    </script>
@endsection


@section('after_js')
    <script>
        function fileSet() {
            var x = document.getElementById('one')
            var y = document.getElementById('two')
            var z = document.getElementById('file_button')

            console.log(z.innerText)

            if (z.innerText === 'Excel Import') {
                x.style.display = 'none';
                y.style.display = 'block';
                z.innerText = 'Single Input'
            } else {
                x.style.display = 'block';
                y.style.display = 'none';
                z.innerText = 'Excel Import'
            }
        }

        function showCategory(categoryId) {
            const allContent = document.getElementById('contentAll');
            const categoryContents = document.querySelectorAll('.category-content');

            // Hide all category contents
            categoryContents.forEach(content => {
                content.style.display = 'none';
            });

            // Show all pledges if "All Pledges" is selected
            if (categoryId === 'all') {
                allContent.style.display = 'block';
            } else {
                allContent.style.display = 'none';
                document.getElementById('contentCategory' + categoryId).style.display = 'block';
            }
        }

        function filterPledges() {
            const searchInput = document.getElementById("searchPledge").value.toLowerCase();
            const rows = document.querySelectorAll("#users_table .pledge-row");

            rows.forEach(row => {
                const name = row.cells[1].innerText.toLowerCase();
                const amount = row.cells[2].innerText.toLowerCase();

                if (name.includes(searchInput) || amount.includes(searchInput)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function showCategory1(categoryId) {
            const allContent = document.getElementById('contentAll1');
            const categoryContents = document.querySelectorAll('.category-content1');

            // Hide all category contents
            categoryContents.forEach(content => {
                content.style.display = 'none';
            });

            // Show all pledges if "All Pledges" is selected
            if (categoryId === 'all') {
                allContent.style.display = 'block';
            } else {
                allContent.style.display = 'none';
                document.getElementById('contentCategory1' + categoryId).style.display = 'block';
            }
        }

        function showCategoryTicket(categoryId) {
            const allContent = document.getElementById('contentAllPaidTicket');
            const categoryContents = document.querySelectorAll('.category-content_ticket');

            // Hide all category contents
            categoryContents.forEach(content => {
                content.style.display = 'none';
            });

            // Show all pledges if "All Pledges" is selected
            if (categoryId === 'all') {
                allContent.style.display = 'block';
            } else {
                allContent.style.display = 'none';
                document.getElementById('contentCategoryAllPaidTicket' + categoryId).style.display = 'block';
            }
        }

        function filterPledgesPaidTickets() {
            const searchInput = document.getElementById("searchPledgeTicket").value.toLowerCase();
            const rows = document.querySelectorAll("#users_table_ticket .pledge-row_ticket");

            rows.forEach(row => {
                const name = row.cells[1].innerText.toLowerCase();
                const amount = row.cells[2].innerText.toLowerCase();

                if (name.includes(searchInput) || amount.includes(searchInput)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function filterPledges1() {
            const searchInput = document.getElementById("searchPledge1").value.toLowerCase();
            const rows = document.querySelectorAll("#users_table1 .pledge-row1");

            rows.forEach(row => {
                const name = row.cells[1].innerText.toLowerCase();
                const amount = row.cells[2].innerText.toLowerCase();

                if (name.includes(searchInput) || amount.includes(searchInput)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }



        $(function() {
            $('#event_date').daterangepicker({
                autoUpdateInput: false, // Prevents auto-populating before selection
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            // Handle date selection
            $('#event_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });

            // Handle cancel/clear action
            $('#event_date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cardBalanceButton = document.getElementById('cardBalanceButton');
            const buyCardButton = document.getElementById('buyCardButton');

            const smsBalanceButton = document.getElementById('smsBalanceButton');
            const buySMSButton = document.getElementById('buySMSButton');

            const whatsAppBalanceButton = document.getElementById('whatsAppBalanceButton');
            const buyWhatsAppButton = document.getElementById('buyWhatsAppButton');

            // Ensure the sections are hidden on page load
            buyCardButton.style.display = 'none';
            cardBalanceButton.style.display = 'none';

            smsBalanceButton.style.display = 'none';
            buySMSButton.style.display = 'none';

            whatsAppBalanceButton.style.display = 'none';
            buyWhatsAppButton.style.display = 'none';

            // Add event listener to the Cards button
            const cardsButton = document.getElementById('cardsButton');
            if (cardsButton) {
                cardsButton.addEventListener('click', function() {
                    buyCardButton.style.display = 'block';
                    cardBalanceButton.style.display = 'block';
                });
            }

            // Add event listeners to all other tab buttons
            const tabButtons = document.querySelectorAll('.js-tabs-button:not(#cardsButton)');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    buyCardButton.style.display = 'none';
                    cardBalanceButton.style.display = 'none';
                });
            });

            // Add event listener to the Cards button
            const smsNotificationButton = document.getElementById('smsNotificationButton');
            if (smsNotificationButton) {
                smsNotificationButton.addEventListener('click', function() {
                    smsBalanceButton.style.display = 'block';
                    buySMSButton.style.display = 'block';
                });
            }

            // Add event listeners to all other tab buttons
            const tabButtons1 = document.querySelectorAll('.js-tabs-button:not(#smsNotificationButton)');
            tabButtons1.forEach(button => {
                button.addEventListener('click', function() {
                    smsBalanceButton.style.display = 'none';
                    buySMSButton.style.display = 'none';
                });
            });

            // Add event listener to the Cards button
            const whatNotificationButton = document.getElementById('whatNotificationButton');
            if (whatNotificationButton) {
                whatNotificationButton.addEventListener('click', function() {
                    whatsAppBalanceButton.style.display = 'block';
                    buyWhatsAppButton.style.display = 'block';
                });
            }

            // Add event listeners to all other tab buttons
            const tabButtons2 = document.querySelectorAll('.js-tabs-button:not(#whatNotificationButton)');
            tabButtons2.forEach(button => {
                button.addEventListener('click', function() {
                    whatsAppBalanceButton.style.display = 'none';
                    buyWhatsAppButton.style.display = 'none';
                });
            });

        });

        function handleInvitationClick(event, buttonId) {
            // Prevent the default link action
            event.preventDefault();

            // Show a confirmation alert
            const confirmation = confirm("Are you sure you want to send the invitation?");
            if (!confirmation) {
                // If the user cancels, do nothing
                return;
            }

            // Get all buttons and disable them
            const allButtons = document.querySelectorAll('button');
            allButtons.forEach(button => {
                button.style.pointerEvents = 'none';
                button.style.backgroundColor = 'grey';
                button.style.color = 'white';
            });

            // Get the clicked button
            const button = document.getElementById(buttonId);

            // Save original styles for restoring later
            const originalBackgroundColor = button.style.backgroundColor;
            const originalColor = button.style.color;
            const originalText = button.innerText;

            // Show the loading modal
            showLoadingModal();

            fetch(event.target.href, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                })
                .then(data => {
                    console.log('Success:', data);
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred: ' + error.message);
                })
                .finally(() => {
                    // Hide the loading modal
                    hideLoadingModal();

                    // Restore all buttons to their original state
                    allButtons.forEach(button => {
                        button.style.pointerEvents = 'auto';
                        button.style.backgroundColor = '';
                        button.style.color = '';
                    });

                    // Restore clicked button state
                    button.style.backgroundColor = originalBackgroundColor;
                    button.style.color = originalColor;
                    button.innerText = originalText;
                });
        }

        // Function to show loading modal
        function showLoadingModal() {
            const modal = document.createElement('div');
            modal.id = 'loadingModal';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '1000';

            const spinner = document.createElement('div');
            spinner.style.border = '4px solid rgba(255, 255, 255, 0.3)';
            spinner.style.borderTop = '4px solid white';
            spinner.style.borderRadius = '50%';
            spinner.style.width = '40px';
            spinner.style.height = '40px';
            spinner.style.animation = 'spin 1s linear infinite';

            modal.appendChild(spinner);
            document.body.appendChild(modal);

            // Add CSS for spinner animation
            const style = document.createElement('style');
            style.innerHTML = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
            document.head.appendChild(style);
        }

        // Function to hide loading modal
        function hideLoadingModal() {
            const modal = document.getElementById('loadingModal');
            if (modal) {
                modal.remove();
            }
        }




        //     $(document).ready(function() {
        //     // Check for last active tab in localStorage
        //     var lastActiveTab = localStorage.getItem('activeTab');

        //     // If there is a saved tab, set it as active
        //     if (lastActiveTab) {
        //         $('.tabs__pane').removeClass('active'); // Hide all tabs
        //         $(lastActiveTab).addClass('active'); // Show the last active tab
        //         $('.tabs__button').removeClass('is-active'); // Remove active class from all buttons
        //         $('.tabs__button[data-tab-target="' + lastActiveTab + '"]').addClass('is-active'); // Set last active button
        //     }

        //     // Handle tab click
        //     $('.js-tabs-button').on('click', function() {
        //         var targetTab = $(this).data('tab-target');
        //         $('.tabs__pane').removeClass('active'); // Hide all tabs
        //         $(targetTab).addClass('active'); // Show the selected tab
        //         $('.tabs__button').removeClass('is-active'); // Remove active class from all buttons
        //         $(this).addClass('is-active'); // Set clicked button as active

        //         // Save the active tab in localStorage
        //         localStorage.setItem('activeTab', targetTab);
        //     });
        // });

        // Initialize by showing all pledges
        document.addEventListener('DOMContentLoaded', () => {
            showCategory('all');
        });
    </script>



    @if (config('usersmanagement.enableSearchUsers'))
        @include('scripts.search-users')
    @endif

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete all pledges? This action cannot be undone.');
        }
    </script>
@endsection
