@extends('layouts.dash')

{{-- @section('template_title')
    {{ $event_notification->event->event_name }} Order
@endsection --}}

@section('content')
    <div class="dashboard__content bg-light-4 pt-5" style="background-color: #f2f4f4">

        <section class="page-header -type-1 p-5">
            <div class="container">
                <div class="page-header__content">
                    <div class="row justify-center text-center">
                        <div class="col-auto">

                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="layout-pt-md layout-pb-lg bg-white -dark-bg-dark-1">

            <div class="container">
                <div class="row no-gutters justify-content-center">
                    <div class="col-xl-8 col-lg-9 col-md-11">


                        <!-- resources/views/includes/alerts.blade.php -->
                        @include('sherehe.dash.includes.alerts')


                        <form action="{{ route('dash.notification.whassap.send.share.news', $event->id) }}" method="post"
                            id="notify_sms" class="contact-form row x-gap-30 y-gap-30" enctype="multipart/form-data">
                            @csrf
                            <input hidden="hidden" name="event_id" value="{{ $event->id }}">


                            <div class="col-md-6">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Group</label>
                                <select name="group" class="selectize wide js-selectize" style="display: none;" required>
                                    <option value="all_pledges">All Pledges
                                        ({{ number_format($event->pledges->count()) }})</option>
                                    <option value="partial_pledges">Partially Paid
                                        ({{ number_format($event->prtial_paid_pledges->count()) }})</option>
                                    <option value="null_pledges">Not Paid
                                        ({{ number_format($event->not_paid_pledges->count()) }})</option>
                                    <option value="complete_pledges">Fully Paid
                                        ({{ number_format($event->complete_paid_pledges->count()) }})</option>
                                    <option value="incomplete_pledges">Incomplete
                                        ({{ number_format($event->incomplete_paid_pledges->count()) }})</option>
                                </select>
                                @if ($errors->has('item_type_id'))
                                    <p style="color: red">{{ $errors->first('item_type_id') }}</p>
                                @endif
                            </div>


                            <div class="col-md-6">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Pledges Category</label>
                                <select id="event_attendees_category_id" name="event_attendees_category_id"
                                    class="selectize wide js-selectize" style="display: none;">
                                    <option value="" selected>-- Select Category --</option>
                                    @foreach ($event_attendees_categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('event_attendees_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('event_attendees_category_id'))
                                    <p style="color: red">{{ $errors->first('event_attendees_category_id') }}</p>
                                @endif
                            </div>


                            <div class="col-md-6">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Type of Message</label>
                                <select class="selectize wide js-selectize" name="message_type" id="message_type">
                                    <option value="">
                                        --{{ $event->language == 'sw' ? 'Chagua Aina ya Ujumbe' : 'Select Message Type' }}
                                        --
                                    </option>
                                    {{-- <option value="reminder">Mchango Ukumbusho</option> --}}
                                    <option value="important_notice">
                                        {{ $event->language == 'sw' ? 'Taarifa Muhimu' : 'Important Notice' }}</option>
                                    <option value="ujumbe_wa_shukrani">
                                        {{ $event->language == 'sw' ? 'Ujumbe wa Shukrani' : 'Thank You Message' }}
                                    </option>
                                    <option value="kukumbusha_siku_ya_tukio">
                                        {{ $event->language == 'sw' ? 'Kukumbusha Siku ya Tukio' : 'Event Reminder' }}
                                    </option>
                                    @php
                                        $eventDate = \Carbon\Carbon::parse($event->event_date);
                                        $today = \Carbon\Carbon::today();
                                        $daysRemain = max(0, $today->diffInDays($eventDate, false));
                                    @endphp

                                    <option value="days_count">
                                        {{ $event->language == 'sw'
                                            ? "Siku $daysRemain zimebaki - " . $eventDate->format('d F Y')
                                            : "$daysRemain days remaining - " . $eventDate->format('d F Y') }}
                                    </option>

                                    <option value="mchango_ukumbusho">
                                        {{ $event->language == 'sw' ? 'Ukumbusho wa Mchango' : 'Contribution Reminder' }}
                                    </option>

                                    <option value="taarifa_ya_sherehe_mchango">
                                        {{ $event->language == 'sw' ? 'Taarifa ya Sherehe Mchango' : 'Event Contribution Notice' }}
                                    </option>


                                </select>
                            </div>


                            <div class="col-md-6">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Image</label>
                                <br>
                                <input type="file" value="{{ old('image') }}" name="image">
                                @if ($errors->has('image'))
                                    <p style="color: red">{{ $errors->first('image') }}</p>
                                @endif
                            </div>

                        </form>


                        <div class="shopCompleted-info">
                            <div class="row no-gutters y-gap-32">
                                <div class="col-md-2 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle text-center">Sender name</div>
                                        <div class="title text-purple-1 mt-5 text-center">
                                            WhatsApp</div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle text-center">Group</div>
                                        <div class="title text-purple-1 mt-5 uppercase text-center">
                                            0
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle text-center">Pledgers</div>
                                        <div class="title text-purple-1 mt-5 text-center">
                                            0</div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle text-center">SMS Count</div>
                                        <div class="title text-purple-1 mt-5 text-center">
                                            0</div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle text-center">Balance Before</div>
                                        <div class="title text-purple-1 mt-5 text-center">
                                            0</div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle text-center">Balance After</div>
                                        <div class="title text-purple-1 mt-5 text-center">
                                            0</div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="row y-gap-20 justify-between pt-30">
                            <div class="col-auto sm:w-1/1">
                                {{-- <a href="#" class="button -sm -black text-blue-1"
                                    style="width: 200px;text-align: center;">
                                    Recharge SMS
                                </a> --}}
                            </div>

                            <div class="col-auto sm:w-1/1">
                                <button type="submit" form="notify_sms" id="sendButton"
                                    style="width: 200px;text-align: center;"
                                    class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1"
                                    onclick="handleClick(event)">Send</button>
                                {{-- @endif --}}

                            </div>
                        </div>

                    </div>
                </div>
            </div>





        </section>

    </div>

    <script>
        function sample_dc2() {

            var x = document.getElementById('sample1');
            var y = document.getElementById('source1');

            text = y.value;

            const name = /@name/i
            const amount = /@amount/i
            const paid = /@paid/i
            const balance = /@balance/i
            const phone = /@phone/i
            const date = /@date/i

            new_text = text.replace(name, "Mr. Jumaa Anhonr");
            new_text = new_text.replace(amount, "20,000 TZS");
            new_text = new_text.replace(paid, "15,000 TZS");
            new_text = new_text.replace(balance, "5,000 TZS");
            new_text = new_text.replace(phone, "0786 147 878");
            new_text = new_text.replace(date, "15 May 2024");

            x.innerText = new_text;

            var smsText = document.getElementById('source1').value;
            var charCount = smsText.length;

            var smsLimit = 160;
            var smsCount = Math.ceil(charCount / smsLimit);
            document.getElementById('char_count').innerText = charCount;
            document.getElementById('sms_count').innerText = smsCount;

        }

        function handleClick(event) {
            event.preventDefault();
            const isConfirmed = confirm('Are you sure you want to send this notification?');
            if (isConfirmed) { // Disable the send button to prevent multiple submissions
                const sendButton = document.getElementById('sendButton');
                sendButton.disabled = true;
                sendButton.innerText = 'Sending...';
                // Submit the form
                document.getElementById('notify_sms').submit(); // No need to manually reload the page
            }
        }
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageType = document.getElementById('message_type');
            const languageWrapper = document.getElementById('language_wrapper');

            messageType.addEventListener('change', function() {
                if (this.value === 'ujumbe_wa_shukrani') {
                    languageWrapper.classList.remove('d-none');
                } else {
                    languageWrapper.classList.add('d-none');
                }
            });
        });
    </script> --}}
@endsection
