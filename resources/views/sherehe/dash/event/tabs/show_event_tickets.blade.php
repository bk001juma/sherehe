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


                            <div class="tabs__pane -tab-item-11 is-active">

                                <div class="row">
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <a href="#" data-toggle="modal" data-target="#ticket_price"
                                            class="button -sm text-white" style="background-color: white"></a>
                                    </div>
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <a href="#" data-toggle="modal" data-target="#ticket_price"
                                            class="button -sm -black text-blue-1">Change Ticket Price</a>
                                    </div>
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <a href="#" data-toggle="modal" data-target="#upload_ticket_design"
                                            class="button -sm -black text-blue-1">Upload Your Design</a>
                                    </div>
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <a href="#" data-toggle="modal" data-target="#upload_ticket_welcome_note"
                                            class="button -sm -black text-blue-1">Upload Ticket Welcome Note</a>
                                    </div>
                                </div>
                                {{-- </div> --}}

                                <div class="row"> <!-- Pledged Amount -->
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <div class="card d-flex justify-content-between align-items-center py-35 px-30 rounded-16"
                                            style="background-color: #d3ccbc;">

                                            <table class="max-auto">
                                                <tr>
                                                    <th class="text-center">
                                                        Ticket Price
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Single:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS {{ number_format($event->card_types->single_amount) }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Couple:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS {{ number_format($event->card_types->double_amount) }}
                                                        </h6>
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
                                                    <th class="text-center">
                                                        Ticket Count
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Single:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->single_invitations->count()) }} </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Couple:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->double_invitations->count()) }}</h6>
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
                                                    <th class="text-center">
                                                        Ticket Status
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Sent:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->pledges->where('card_received', true)->count()) }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Delivered:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->pledges->where('card_received', true)->count()) }}
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
                                                    <th class="text-center">
                                                        Total
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Ticket:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->card_balance) }}</h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Welcome Note:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ $event->welcome_note != null ? 'Yes' : 'No' }}</h6>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <hr>
                                <div class="pt-30">
                                    <form action="{{ route('dash.event.card.send') }}" method="POST">
                                        <!-- Form to handle card selection -->
                                        @csrf

                                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                                        <div class="text-16 fw-500 text-dark-1 mb-4">Ticket Designs</div>
                                        <div class="row g-3"> <!-- Use 'g-3' for gap between columns -->



                                            @if ($event->designCard)
                                                @if ($event->designCard->single_card)
                                                    <div class="col-md-6">
                                                        <div class="card h-100">
                                                            <div class="card-body">
                                                                <img src="{{ asset($event->designCard->single_card) }}"
                                                                    height="600px"
                                                                    style="width: 100%; border: none;"></img>
                                                            </div>
                                                            <div class="card-footer text-center">
                                                                <label for="card2">Single Ticket</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            @if ($event->designCard)
                                                @if ($event->designCard->double_card)
                                                    <div class="col-md-6">
                                                        <div class="card h-100">
                                                            <div class="card-body">
                                                                <img src="{{ asset($event->designCard->double_card) }}"
                                                                    height="600px"
                                                                    style="width: 100%; border: none;"></img>
                                                            </div>
                                                            <div class="card-footer text-center">
                                                                <label for="card2">Double Ticket</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                        </div>

                                        <div class="text-center mt-4">
                                            {{-- <button type="submit" class="btn btn-success">Confirm</button> --}}
                                            <!-- Submit button -->
                                        </div>
                                    </form>
                                </div>


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
