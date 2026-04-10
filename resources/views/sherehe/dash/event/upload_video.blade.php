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


                        <form action="{{ route('dash.video.upload.send', $event->id) }}" method="post" id="notify_sms"
                            class="contact-form row x-gap-30 y-gap-30" enctype="multipart/form-data">
                            @csrf
                            <input hidden="hidden" name="event_id" value="{{ $event->id }}">

                            <!-- Title / Info -->
                            <div class="col-12 mb-20">
                                <h1 class=" fw-600 text-dark-1">Upload your event video</h1>
                                <p class="text-14 text-dark-2">
                                    Choose a video file (MP4, 3GP, or 3GPP).
                                    Make sure the file is not too large.
                                </p>
                            </div>


                            <div class="col-md-6">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Video</label>
                                <br>
                                <input type="file" name="video" accept=".mp4,.3gp,.3gpp" value="{{ old('video') }}">
                                @if ($errors->has('video'))
                                    <p style="color: red">{{ $errors->first('video') }}</p>
                                @endif
                            </div>

                        </form>





                        <div class="row y-gap-20 justify-between pt-30">
                            <div class="col-auto sm:w-1/1">
                            </div>

                            <div class="col-auto sm:w-1/1">
                                <button type="submit" form="notify_sms" id="sendButton"
                                    style="width: 200px;text-align: center;"
                                    class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1"
                                    onclick="handleClick(event)">Upload</button>
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
