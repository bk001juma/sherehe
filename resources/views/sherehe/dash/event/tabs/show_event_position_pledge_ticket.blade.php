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


                {{-- <h1 id="cardBalanceHeader" style="display: none;">Card Balance</h1> --}}

                <button data-toggle="modal" style="background-color: #9aa89b;" class="button -sm  text-white sm:w-1/1 mr-2">
                    Card Balance: {{ number_format($event->card_balance) }}
                </button>
                {{-- <button data-toggle="modal" data-target="#buy_sms" style="background-color: #9aa89b;"
                    class="button -sm text-white sm:w-1/1 mr-2">
                    <i class="fa fa-plus mr-5"></i> Buy Cards
                </button> --}}


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


                            <div class="tabs__pane -tab-item-5 is-active">


                                <hr>
                                <div class="pt-30">
                                    <form id="generateCardForm" action="{{ route('dash.event.ticket.send.name') }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <div class="row mb-4">
                                            <div class="col-md-2">
                                                <label for="top" class="form-label">Top (%)</label>
                                                <input type="number" name="top" id="top" class="form-control"
                                                    placeholder="e.g. 35" min="0" max="100"
                                                    value="{{ $event->top }}" style="border: 0.1px solid grey;" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="left" class="form-label">Left (%)</label>
                                                <input type="number" name="left" id="left" class="form-control"
                                                    placeholder="e.g. 50" min="0" max="100"
                                                    value="{{ $event->left }}" style="border: 0.1px solid grey;" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="color" class="form-label">Font Size</label>
                                                <input type="text" name="font_size" id="font_size" class="form-control"
                                                    value="{{ $event->font_size }}" placeholder="e.g. 42px"
                                                    style="border: 0.1px solid grey;" required>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="color" class="form-label">Text Color Code</label>
                                                <input type="text" name="color" id="color" class="form-control"
                                                    value="{{ $event->color }}" placeholder="e.g. #000000"
                                                    style="border: 0.1px solid grey;" required>

                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="submit" id="generateCardBtn" class="btn btn-success w-100">
                                                    <span id="btnText">Generate Card</span>
                                                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"
                                                        role="status" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </div>


                                        <div></div>


                                        <!-- Newly Generated Card Preview -->
                                        @if (!empty($base64Image))
                                            <div class="mb-4">
                                                {{-- <h5 class="text-dark-1">Generated Card Preview {{ $base64Image }}</h5> --}}
                                                <img src="data:image/png;base64,{{ $base64Image }}" alt="Generated Card"
                                                    style="width:100%; border: 2px solid #ddd; padding: 5px;" />
                                            </div>
                                        @elseif (!empty($event->designCard) && !empty($event->designCard->single_card))
                                            <div class="mb-4">
                                                <img src="{{ asset($event->designCard->single_card) }}" height="300"
                                                    style="width:100%; border: none;">
                                            </div>
                                        @endif


                                        <!-- Input Fields for Customization -->

                                    </form>

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


@section('after_js')
    <script>
        document.getElementById('generateCardForm').addEventListener('submit', function() {
            const btn = document.getElementById('generateCardBtn');
            document.getElementById('btnText').textContent = 'Generating...';
            document.getElementById('btnSpinner').classList.remove('d-none');
            btn.disabled = true;
        });
    </script>
@endsection
