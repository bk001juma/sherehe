@extends('layouts.dash')



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

            <div class="mb-4">
                <h4 class="text-center">
                    Send Bulk SMS
                </h4>
            </div>
            <div class="mb-3"></div>

            <div class="container">
                <div class="row no-gutters justify-content-center">
                    <div class="col-xl-8 col-lg-9 col-md-11">

                        <!-- resources/views/includes/alerts.blade.php -->
                        @include('sherehe.dash.includes.alerts')


                        <form action="{{ route('dash.notification.sms.send', $event->id) }}" method="post" id="notify_sms"
                            class="contact-form row x-gap-30 y-gap-30">
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
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Sender Name</label>
                                <select onchange="sender_change()" id="sender" name="sender_name"
                                    class="selectize wide js-selectize" style="display: none;" required>
                                    <option>SHEREHE</option>
                                    <option>SEND OFF</option>
                                    <option>HARUSI</option>
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

                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Keys</label>
                                <select class="selectize wide js-selectize" style="display: none;">
                                    <option value="@name" style="color: red;">@name - Pledger name</option>
                                    <option value="@amount" style="color: red;">@amount - Pledge amount</option>
                                    <option value="@paid" style="color: red;">@paid - Paid amount</option>
                                    <option value="@balance" style="color: red;">@balance - Remaining balance</option>
                                    <option value="@phone" style="color: red;">@phone - Pledge phone number</option>
                                    <option value="@date" style="color: red;">@date - Event Date</option>
                                </select>

                            </div>

                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Message</label>
                                <textarea name="sms" id="source" onchange="sample_dc()" onkeypress="sample_dc()" onkeydown="sample_dc()"
                                    onkeyup="sample_dc()"></textarea>
                                <p>
                                    <span id="char_count">0</span>/160 characters |
                                    <span id="sms_count">0</span> SMS
                                </p>
                                <input type="hidden" id="sms_count_input" name="sms_count" value="0">

                                {{--                          <input type="text" value="{{old('amount')}}" name="amount" placeholder="100000" required> --}}
                                @if ($errors->has('amount'))
                                    <p style="color: red">{{ $errors->first('amount') }}</p>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Message Review</label>
                                {{-- <div class="d-flex items-center justify-start">
                                    <div class="lh-11 fw-500 text-dark-5 mr-10" id="sender_text">SHEREHE</div>
                                    <div class="text-14 lh-11 mr-10">3 minutes ago</div>

                                </div> --}}
                                <div class="d-inline-block mt-15 pull-right">
                                    <div class="py-20 px-30 text-purple-1 rounded-8 text-left"
                                        style="background-color: #f2f4f4" id="sample">

                                    </div>
                                    <i class="icon icon-check pull-right"></i>
                                </div>
                            </div>
                        </form>



                        {{-- <div class="shopCompleted-footer bg-light-4 border-light rounded-8">
                            <div class="shopCompleted-footer__wrap">
                                <h5 class="title">
                                    Message details
                                </h5>

                                <div class="item">
                                    <span class="fw-500">Sample message</span>
                                </div>

                                <div class="item">
                                    <div class="col-xl-6">
                                        <div class="d-flex items-center justify-start">
                                            <div class="lh-11 fw-500 text-dark-5 mr-10" id="sender_text">SHEREHE</div>
                                            <div class="text-14 lh-11 mr-10">3 minutes ago</div>

                                        </div>
                                        <div class="d-inline-block mt-15 pull-left">
                                            <div class="py-20 px-30 bg-light-7 -dark-bg-dark-2 text-purple-1 rounded-8 text-left"
                                                id="sample" style="line-height: 20px">
                                                {!! $event_notification->sms_notifications->first()->sms !!}
                                            </div>
                                            <i class="icon icon-check pull-right"></i>
                                        </div>
                                    </div>
                                </div>


                                <div class="item">

                                </div>
                            </div>
                        </div> --}}


                        <div class="shopCompleted-info">
                            <div class="row no-gutters y-gap-32">
                                <div class="col-md-2 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle text-center">Sender name</div>
                                        <div class="title text-purple-1 mt-5 text-center">
                                            0</div>
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
                                {{-- <h5>Current Balance {{ $event_notification->event->sms_balance }} SMS</h5> --}}
                                <a href="#" class="button -sm -black text-blue-1"
                                    style="width: 200px;text-align: center;">
                                    Recharge SMS
                                </a>
                            </div>

                            <div class="col-auto sm:w-1/1">
                                {{-- @if ($event_notification->status == 'pending') --}}
                                {{-- <a href="{{ route('dash.notification.sms.send', $event_notification->id) }}"
                                        class="button -sm -dark-5 text-blue-1 sm:w-1/1" id="sendButton"
                                        style="width: 200px;text-align: center;">
                                        Send
                                    </a> --}}
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
        function sample_dc() {
            var x = document.getElementById('sample');
            var y = document.getElementById('source');

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
            new_text = new_text.replace(phone, "0765 204 506");
            new_text = new_text.replace(date, "15 May 2024");

            x.innerText = new_text;


            var smsText = document.getElementById('source').value;
            var charCount = smsText.length;
            var smsCount = 0;

            if (charCount > 0) {
                if (charCount <= 160) {
                    smsCount = 1;
                } else if (charCount <= 306) {
                    smsCount = 2;
                } else {
                    // For counts above 306, each additional SMS accounts for 153 characters
                    smsCount = 2 + Math.ceil((charCount - 306) / 153);
                }
            }

            document.getElementById('char_count').innerText = charCount;
            document.getElementById('sms_count').innerText = smsCount;

            document.getElementById('sms_count_input').value = smsCount;

        }

        function sender_change() {
            var b = document.getElementById('sender');
            var a = document.getElementById('sender_text');
            // var value = b.value;
            a.innerText = b.options[b.selectedIndex].text;

            console.log(a.innerText);
        }


        function handleClick(event) {
            event.preventDefault();

            const isConfirmed = confirm('Are you sure you want to send this notification?');
            if (isConfirmed) {
                const sendButton = document.getElementById('sendButton');
                sendButton.innerHTML = 'Loading...';
                sendButton.disabled = true;
                sendButton.style.backgroundColor = 'darkgrey';
                document.getElementById('notify_sms').submit();
            }
        }
    </script>

    <script>
        // Clear the textarea on page load or refresh
        window.addEventListener('load', function() {
            document.getElementById('source').value = '';
        });
    </script>
@endsection
