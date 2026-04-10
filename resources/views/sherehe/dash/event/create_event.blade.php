@extends('layouts.dash')

@section('template_title')
    Create Event
@endsection

@section('page_css')
    <meta http-equiv="cache-control" content="no-cache" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
@endsection
@section('content')
    <div class="dashboard__content bg-light-4 pt-0">

        <section class="page-header -type-1 p-5">
            <div class="container ">
                <div class="page-header__content">
                    <div class="row justify-center text-center">
                        <div class="col-auto">
                            <div data-anim="slide-up delay-1">
                                <h2 class="">Event Details</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="layout-pt-md layout-pb-lg pt-0">
            <div class="container">
                <div class="row y-gap-50">
                    <p>{{ $errors }}</p>
                    <div class="col-lg-8 bg-white -dark-bg-dark-1 shadow-4 h-100">
                        <div class="shopCheckout-form ">
                            <form method="post" action="{{ route('dash.store_event') }}" id="event_form"
                                enctype="multipart/form-data" class="contact-form row x-gap-30 y-gap-30">
                                @csrf
                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event name<span
                                            style="color: red">*</span></label>
                                    <input type="text" name="event_name" placeholder="Harusi ya Joseph" required>
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Card And Ticket <span
                                            style="color: red">*</span> </label>
                                    <select class="selectize wide js-selectize" name="card_and_ticket_id" required>
                                        <option value="">Select Card or Ticket</option>
                                        @foreach ($cards_and_tickets as $card_and_ticket)
                                            <option value="{{ $card_and_ticket->id }}">{{ $card_and_ticket->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Select Package <span
                                            style="color: red">*</span> </label>
                                    <select class="selectize wide js-selectize" name="event_package_id" required
                                        onchange="changePackage(value)">
                                        @foreach ($packages as $package)
                                            <option {{ $selected_package->id == $package->id ? 'selected' : null }}
                                                value="{{ $package->id }}">{{ $package->name }} <span
                                                    style="color: red">{{ number_format($package->price) }}</span> TZS
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Type <span
                                            style="color: red">*</span> </label>
                                    <select class="selectize wide js-selectize" name="event_category_id" required
                                        onchange="family(value)">
                                        <option value="">Select event type</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-6" id="family_name">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Family name</label>
                                    <input type="text" name="family_name" placeholder="Familia ya Anthony Lusekela">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">
                                        Language <span style="color: red">*</span>
                                    </label>
                                    <select class="selectize wide js-selectize" name="language" required>
                                        <option value="">Select Language</option>
                                        <option value="sw">Swahili</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>


                                <div class="col-sm-2 d-flex align-items-center">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10" id="wausika1"
                                        style="display:none;">Harusi ya</label>
                                </div>
                                <div class="col-sm-4" id="wausika2" style="display:none;">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Mr Name</label>
                                    <input type="text" name="mr_name" placeholder="Joseph">
                                </div>
                                <div class="col-sm-2 d-flex align-items-center justify-content-center">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10" id="wausika3"
                                        style="display:none;">na</label>
                                </div>
                                <div class="col-sm-4" id="wausika4" style="display:none;">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Mrs Name </label>
                                    <input type="text" name="mrs_name" placeholder="Amina">
                                </div>

                                <div class="col-sm-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Description</label>
                                    <input type="text" name="description" placeholder="Harusi ya Juma na Annunciata">
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Google Maps Location
                                        link <span style="color: red">*</span></label>
                                    <input type="text" name="maps_location"
                                        placeholder="eg: https://maps.app.goo.gl/3mPh2aRLyJmnDyKG9?g_st=iw">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Venue <span
                                            style="color: red">*</span></label>
                                    <input type="text" name="venue" placeholder="eg: Events Venue" required>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Location <span
                                            style="color: red">*</span></label>
                                    <input type="text" name="location" placeholder="eg: Mwananyamala DSM" required>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Video Link </label>
                                    <input type="text" name="video_link"
                                        placeholder="eg: Allowed link with MP4, 3gp and 3gpp extension">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">
                                        Media Type
                                    </label>
                                    <select class="selectize wide js-selectize" name="media_type">
                                        <option value="">Select Media Type</option>
                                        <option value="image">Image</option>
                                        <option value="video">Video</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Payment Numbers </label>
                                    <input type="text" name="payment_numbers" placeholder="Mpesa -0756xxx">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Contribution Deadline </label>
                                    <input type="text" name="contribution_deadline" id="contribution_deadline"
                                        placeholder="">
                                </div>


                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone <span
                                            style="color: red">*</span> </label>
                                    <input type="text" name="contact_phone_1" placeholder="0785008133" required>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Date <span
                                            style="color: red">*</span> </label>
                                    <input type="text" name="event_date" id="event_date" placeholder="" required>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Dress Code<span
                                            style="color: red">*</span></label>
                                    <input type="text" name="dress_code" placeholder="eg: Pink, Grey or Brown"
                                        required>
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Image <span
                                            style="color: red">*</span> </label><br>
                                    <input type="file" name="photo_file" placeholder="Event Image" required
                                        accept="image/png, image/jpeg">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Church Name</label>
                                    <input type="text" name="church_name" placeholder="e.g., Roman Catholic">
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Church Time</label>
                                    <input type="text" name="church_time" placeholder="e.g., 7:00 AM">
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">
                                        Event Time <span style="color: red">*</span>
                                    </label>
                                    <input type="text" name="event_time" id="event_time" placeholder="eg 06:45 Jioni"
                                        required>
                                    {{-- <div id="am-pm-output" class="mt-10 text-14 text-dark-2"></div> --}}
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Rsvps Phone <span
                                            style="color: red">*</span></label>
                                    <div id="phone-list">
                                        <div class="phone-item">
                                            <input type="text" name="phone_number[]" placeholder="0786147878"
                                                class="mb-2" required>
                                            <button type="button" class="remove-phone button -sm mb-2"
                                                onclick="removePhone(this)"
                                                style="background-color: red; color:white;">Remove</button>
                                        </div>
                                    </div>
                                    <button type="button" class="add-phone button -sm btn-blue -dark-5 text-blue-1"
                                        onclick="addPhone()">Add Phone</button>
                                </div>




                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4 pt-lg-0">
                        <div class="">
                            <div class="pt-30 pb-15 bg-white -dark-bg-dark-1 shadow-4 h-100 rounded-8 mb-10">
                                <h5 class="px-30 text-20 fw-500">
                                    Your event
                                </h5>

                                <div class="d-flex justify-between px-30 mt-25">
                                    <div class="py-15 fw-500 text-dark-1">Product</div>
                                    <div class="py-15 fw-500 text-dark-1">Subtotal</div>
                                </div>

                                <div class="d-flex justify-between border-top-dark px-30">
                                    <div class="py-15 text-grey">Bulk SMS</div>
                                    <div class="py-15 text-grey" id="sms">
                                        {{ number_format($selected_package->messages) }}</div>
                                </div>

                                <div class="d-flex justify-between px-30">
                                    <div class="py-15 text-grey">E - Cards</div>
                                    <div class="py-15 text-grey" id="digital_cards">
                                        {{ number_format($selected_package->digital_cards) }}</div>
                                </div>

                                {{-- <div class="d-flex justify-between px-30">
                                    <div class="py-15 text-grey">E - Card Attendence *</div>
                                    <div class="py-15 text-grey" id="attendees">
                                        {{ number_format($selected_package->attendees) }} TZS</div>
                                </div> --}}

                                <div class="d-flex justify-between border-top-dark px-30">
                                    <div class="py-15 fw-500 text-dark-1">Total</div>
                                    <div class="py-15 fw-500 text-dark-1" id="price">
                                        {{ number_format($selected_package->price) }} TZS</div>
                                </div>



                            </div>

                            {{--                            <div class="py-30 px-30 bg-white -dark-bg-dark-1 shadow-4 h-100 rounded-8 align-content-center"> --}}
                            {{--                                <form method="post" action="{{route('dash.store_event')}}" id="number_form" enctype="multipart/form-data" class="contact-form row x-gap-30 y-gap-30" style="width: 100%"> --}}
                            {{--                                <h5 class="text-20 fw-500"> --}}
                            {{--                                    Payment --}}
                            {{--                                </h5> --}}

                            {{--                                <img class="align-content-center" src="/images/pay.png" alt="payment"> --}}

                            {{--                                    <div class="col-sm-12"> --}}
                            {{--                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone <span style="color: red">*</span> </label> --}}
                            {{--                                        <input value="{{preg_replace('/^255/', '0',$user->phone)}}" type="text" name="contact_phone_1" placeholder="0785008133" required> --}}
                            {{--                                    </div> --}}
                            {{--                                </form> --}}
                            {{--                            </div> --}}

                            <div class="mt-30 ">
                                <button type="submit" form="event_form"
                                    class="button -md btn-primary -dark-5 -accent col-12 text-blue-1">Create Event</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection


@section('page_js')
    <script>
        function family(val) {
            // var x = document.getElementById('family_name')
            var familyName = document.getElementById('family_name');
            var wausika1 = document.getElementById('wausika1');
            var wausika2 = document.getElementById('wausika2');
            var wausika3 = document.getElementById('wausika3');
            var wausika4 = document.getElementById('wausika4');
            if (val === '1') {
                // x.style.display = 'none';
                familyName.style.display = 'none';
                wausika1.style.display = 'none';
                wausika2.style.display = 'none';
                wausika3.style.display = 'none';
                wausika4.style.display = 'none';
            } else {
                // x.style.display = 'block';
                familyName.style.display = 'block';
                wausika1.style.display = 'block'; // Show Harusi ya
                wausika2.style.display = 'block'; // Show Mr Name
                wausika3.style.display = 'block'; // Show "na"
                wausika4.style.display = 'block'; // Show Mrs Name
            }
        }

        function changePackage(id) {
            @foreach ($packages as $s_package)
                if (id === '{{ $s_package->id }}') {
                    document.getElementById('sms').innerText = "{{ number_format($s_package->messages) }}";
                    // document.getElementById('attendees').innerText = "{{ number_format($s_package->attendees) }}";
                    document.getElementById('digital_cards').innerText =
                        "{{ number_format($s_package->digital_cards) }} TZS";
                    document.getElementById('price').innerText = "{{ number_format($s_package->price) }} TZS";
                }
            @endforeach
        }

        $(function() {
            $("#checkin").datepicker();
            $("#event_date").datepicker();
            $("#contribution_deadline").datepicker();
        });
    </script>
    <script>
        // Function to add a new phone input field
        function addPhone() {
            const phoneList = document.getElementById('phone-list');
            const newPhoneItem = document.createElement('div');
            newPhoneItem.classList.add('phone-item');
            newPhoneItem.innerHTML = `
                    <input type="text" name="phone_number[]" placeholder="0786147878" class="mb-2">
                    <button type="button" class="remove-phone button -sm mb-2" onclick="removePhone(this)" style="background-color: red; color:white;">Remove</button>
                `;
            phoneList.appendChild(newPhoneItem);
        }

        // Function to remove a phone input field
        function removePhone(button) {
            const phoneItem = button.closest('.phone-item');
            const phoneList = document.getElementById('phone-list');

            // Ensure there's at least one phone input
            if (phoneList.children.length > 1) {
                phoneList.removeChild(phoneItem);
            }
        }

        // Initialize Flatpickr with explicit AM/PM handling
        // flatpickr("#event_time", {
        //     enableTime: true,
        //     noCalendar: true,
        //     dateFormat: "h:i K", // 12-hour format with AM/PM
        //     time_24hr: false, // Enable AM/PM
        //     onChange: function(selectedDates, timeStr) {
        //         // Display the selected time with AM/PM
        //         document.getElementById('am-pm-output').textContent = `Selected Time: ${timeStr}`;
        //     },
        // });
    </script>
@endsection
