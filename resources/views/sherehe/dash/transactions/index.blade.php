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

                            <div class="tabs__pane -tab-item-1 is-active">
                                <div class="row y-gap-30 mb-10 d-flex" style="gap: 15px; flex-wrap: nowrap;">


                                    <div class="content" id="contentAllPaidTicket">
                                        <div class="table-responsive users-table">
                                            <table class="table table-striped table-sm data-table">
                                                <caption id="user_count"> {{ $events->count()}} Event Transactions</caption>
                                                <thead class="thead" style="background-color: #d3ccbc;">
                                                    <tr>
                                                        <th style="color: white;">No.</th>
                                                        <th style="color: white;">Event Name</th>
                                                        <th style="color: white;">Event Type</th>
                                                        <th style="color: white;">Package</th>
                                                        <th style="color: white;">Initial Payment</th>
                                                        <th style="color: white;">Final Payment</th>
                                                        <th style="color: white;">Amount</th>
                                                        <th class="text-center" style="color: white;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($events as $index => $event)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $event->event_name }}</td>
                                                            <td>{{ $event->category->title ?? 'N/S' }}</td>
                                                            <td>{{ $event->package->name }}</td>
                                                            <td>
                                                                @if ($event->initial_payment <= 0)
                                                                    <button class="btn btn-sm btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#initialPaymentModal"
                                                                        data-id="{{ $event->id }}">Add
                                                                        Initial</button>
                                                                @else
                                                                    {{ number_format($event->initial_payment) }}
                                                                @endif

                                                            </td>
                                                            <td>
                                                                @if ($event->final_payment <= 0)
                                                                    <button class="btn btn-sm btn-success"
                                                                        data-toggle="modal" data-target="#finalPaymentModal"
                                                                        data-id="{{ $event->id }}">Add Final</button>
                                                                @else
                                                                    {{ number_format($event->final_payment) }}
                                                                @endif
                                                            </td>
                                                            <td>{{ number_format($event->initial_payment + $event->final_payment) }}
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="/home/events/{{ $event->id }}/manage"
                                                                    class="btn btn-sm btn-info">View</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- Initial Payment Modal -->
    <div class="modal fade" id="initialPaymentModal" tabindex="-1" aria-labelledby="initialPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('event.payment.initial') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" id="initial_event_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="initialPaymentModalLabel">Enter Initial Payment</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="number" name="initial_payment" class="form-control" placeholder="Enter amount"
                            required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Final Payment Modal -->
    <div class="modal fade" id="finalPaymentModal" tabindex="-1" aria-labelledby="finalPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('event.payment.final') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" id="final_event_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="finalPaymentModalLabel">Enter Final Payment</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="number" name="final_payment" class="form-control" placeholder="Enter amount"
                            required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function redirectMe(to_here) {
            window.location = to_here;
        }
    </script>
    <script>
        // $(document).ready(function() {
        console.log('Jackson Mwatuka');

        $('#initialPaymentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            console.log('Initial Modal Opened');
            console.log('Button:', button);
            var eventId = button.data('id');
            console.log('Event ID:', eventId);
            $('#initial_event_id').val(eventId);
        });

        $('#finalPaymentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            console.log('Final Modal Opened');
            var eventId = button.data('id');
            console.log('Event ID:', eventId);
            $('#final_event_id').val(eventId);
        });
        // });
    </script>
@endsection
