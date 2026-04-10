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

                            <div class="tabs__pane -tab-item-9 is-active">
                                <div class="row y-gap-20 justify-between">


                                    <div class="col-auto">
                                        {{-- Put any thing right side --}}
                                    </div>
                                </div>

                                <div class="row"> <!-- Pledged Amount -->
                                    <div class="col-xl-3 col-md-6 col-12 mb-4">
                                        <div class="card d-flex justify-content-between align-items-center py-35 px-30 rounded-16"
                                            style="background-color: #d3ccbc;">

                                            <table class="max-auto">
                                                <tr>
                                                    <th class="text-center">
                                                        Pledgers
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Full Paid:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS {{ number_format($event->card_types->single_amount) }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Icomplete:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            TZS {{ number_format($event->card_types->double_amount) }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Not Paid:</td>
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
                                                        Invitation Card
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
                                                    <td>Double:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->double_invitations->count()) }}</h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Total:</td>
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
                                                        Normal SMS
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Sent:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->single_invitations->count()) }} </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Delivered:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->single_invitations->count()) }} </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Failed:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->single_invitations->count()) }} </h6>
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
                                                        WhatsApp SMS
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Sent:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->single_invitations->count()) }} </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Delivered:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->single_invitations->count()) }} </h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Failed:</td>
                                                    <td class="text-right">
                                                        <h6 class="text-dark-5" style="color: white;">
                                                            {{ number_format($event->single_invitations->count()) }} </h6>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div style="padding-bottom: 20px"></div>

                                <div class="mb-4">
                                    <h4 class="">
                                        Generate reports
                                    </h4>
                                </div>

                                <div class="row">
                                    {{-- Report drop down Generate reports --}}

                                    <form action="{{ route('dash.event.report.filter', $event->id) }}" method="post"
                                        class="contact-form row x-gap-30 y-gap-30">
                                        @csrf

                                        <div class="col-md-2">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Pledgers Category</label>
                                            <select name="event_attendees_category_id">
                                                <option value="">Pledgers Category</option>
                                                @foreach ($event_attendees_categories as $event_attendees_categorie)
                                                    <option value="{{ $event_attendees_categorie->id }}">
                                                        {{ $event_attendees_categorie->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Pledger Type</label>
                                            <select name="card_type">
                                                <option value="">All</option>
                                                <option value="single">Single</option>
                                                <option value="double">Double</option>
                                                <option value="incomplete">Incomplete</option>
                                                <option value="not_paid">Not Paid</option>
                                                <option value="partial_paid">Partial Paid</option>

                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Is Attending</label>
                                            <select name="is_attending">
                                                <option value="">All</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Card Received</label>
                                            <select name="card_received">
                                                <option value="">All</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2"></div>


                                        <div class="col-md-2">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">&nbsp;</label>
                                            <button type="submit"
                                                class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1 mt-2 sm:mt-3 md:mt-4">
                                                <i class="fa fa-filter mr-2"></i> Filter
                                            </button>
                                        </div>


                                    </form>

                                    @if ($event_attendess->count() > 0)
                                        <div class="table-responsive mt-4">
                                            <table id="myTable" class="table table-bordered table-striped">

                                                <thead style="background-color: #d3ccbc;  color: white;">
                                                    <tr>
                                                        <th style="color: white;">#</th>
                                                        <th style="color: white;">Full Name</th>
                                                        <th style="color: white;">Phone</th>
                                                        <th style="color: white;">Amount</th>
                                                        <th style="color: white;">Paid</th>
                                                        <th style="color: white;">Card Type</th>
                                                        <th style="color: white;">Category</th>
                                                        <th style="color: white;">Is Attending</th>
                                                        <th style="color: white;">Card Received</th>
                                                        <th style="color: white;">Table Number</th>

                                                        {{-- <th>Check-in Count</th>
                                                        <th>Attending Response</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($event_attendess as $index => $attendee)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $attendee['full_name'] }}</td>
                                                            <td>{{ $attendee['phone'] }}</td>
                                                            <td>{{ number_format($attendee['amount']) }}</td>
                                                            <td>{{ number_format($attendee['paid']) }}</td>
                                                            <td>{{ $attendee['card_type'] ?? '-' }}</td>
                                                            <td>{{ $attendee['category_name'] ?? '-' }}</td>
                                                            <td>
                                                                @if ($attendee['is_attending'])
                                                                    <span class="badge bg-success text-white">Yes</span>
                                                                @else
                                                                    <span class="badge bg-danger text-white">No</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($attendee['card_received'])
                                                                    <span class="badge bg-success text-white">Yes</span>
                                                                @else
                                                                    <span class="badge bg-secondary text-white">No</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $attendee['table_number'] ?? '-' }}</td>

                                                            {{-- <td>{{ $attendee['checkin_count'] }}</td>
                                                            <td>{{ ucfirst($attendee['attending_response']) }}</td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="mt-3 text-muted">No attendees found for this filter.</p>
                                    @endif


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                });
            });

            function confirmDelete() {
                return confirm('Are you sure you want to delete all pledges? This action cannot be undone.');
            }
        </script>
    @endpush


@endsection
