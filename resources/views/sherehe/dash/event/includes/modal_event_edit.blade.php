@foreach ($events as $event_edit)
    <div class="modal fade" id="edit_event_{{ $event_edit->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10">

                    <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                        <div class="shopCheckout-form">
                            <form method="post" action="{{ route('dash.event.update', $event_edit->id) }}"
                                id="update_event_{{ $event_edit->id }}" enctype="multipart/form-data"
                                class="contact-form row x-gap-30 y-gap-30">
                                @csrf
                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event name</label>
                                    <input type="text" name="event_name" placeholder="Harusi ya Joseph" required
                                        value="{{ $event_edit->event_name }}">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Card And Ticket <span
                                            style="color: red">*</span> </label>
                                    <select class="selectize wide js-selectize" name="card_and_ticket_id" required>
                                        <option value="">Select Card or Ticket</option>
                                        @foreach ($cards_and_tickets as $card_and_ticket)
                                            <option
                                                {{ $event_edit->card_and_ticket_id == $card_and_ticket->id ? 'selected' : null }}
                                                value="{{ $card_and_ticket->id }}">{{ $card_and_ticket->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Package</label>
                                    <input type="text" name="" disabled
                                        value="{{ $event_edit->package->name }} {{ number_format($event_edit->package->price) }} TZS">
                                </div>


                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Type <span
                                            style="color: red">*</span> </label>
                                    <select class="selectize wide js-selectize" name="event_category_id" required
                                        onchange="family(value)">
                                        <option value="">Select event type</option>
                                        @foreach ($categories as $category)
                                            <option
                                                {{ $event_edit->event_category_id == $category->id ? 'selected' : null }}
                                                value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-6" id="family_name">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Family name</label>
                                    <input type="text" name="family_name" placeholder="Familia ya Anthony Lusekela"
                                        value="{{ $event_edit->family_name }}">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">
                                        Language <span style="color: red">*</span>
                                    </label>
                                    <select class="selectize wide js-selectize" name="language" required>
                                        <option value="">Select Language</option>
                                        <option value="sw" {{ $event_edit->language == 'sw' ? 'selected' : '' }}>
                                            Swahili</option>
                                        <option value="en" {{ $event_edit->language == 'en' ? 'selected' : '' }}>
                                            English</option>
                                    </select>
                                </div>



                                <div class="col-sm-2 d-flex align-items-center">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10" id="wausika1">Harusi
                                        ya</label>
                                </div>
                                <div class="col-sm-4" id="wausika2">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Mr Name</label>
                                    <input type="text" name="mr_name" placeholder="Joseph"
                                        value="{{ $event_edit->mr_name ?? '' }}">
                                </div>
                                <div class="col-sm-2 d-flex align-items-center justify-content-center">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10" id="wausika3">na</label>
                                </div>
                                <div class="col-sm-4" id="wausika4">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Mrs Name </label>
                                    <input type="text" name="mrs_name" placeholder="Amina"
                                        value="{{ $event_edit->mrs_name ?? '' }}">
                                </div>

                                <div class="col-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Description</label>
                                    <input type="text" name="description" placeholder="Harusi ya Juma na Annunciata"
                                        value="{{ $event_edit->description }}">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Google Maps Location
                                        link</label>
                                    <input type="text" name="maps_location"
                                        placeholder="eg: https://maps.app.goo.gl/3mPh2aRLyJmnDyKG9?g_st=iw"
                                        value="{{ $event_edit->maps_location }}">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Venue</label>
                                    <input type="text" name="venue" placeholder="eg: Events Hall"
                                        value="{{ $event_edit->venue }}">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Location</label>
                                    <input type="text" name="location" placeholder="Mwananyamala DSM"
                                        value="{{ $event_edit->location }}">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Video Link </label>
                                    <input type="text" name="video_link"
                                        placeholder="eg: Allowed link with MP4, 3gp and 3gpp extension"
                                        value="{{ $event_edit->video_link }}">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">
                                        Media Type
                                    </label>
                                    <select class="selectize wide js-selectize" name="media_type">
                                        <option value="">Select Media Type</option>
                                        <option value="image"
                                            {{ $event_edit->media_type == 'image' ? 'selected' : '' }}>
                                            Image</option>
                                        <option value="video"
                                            {{ $event_edit->media_type == 'video' ? 'selected' : '' }}>
                                            Video</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Payment Numbers </label>
                                    <input type="text" name="payment_numbers" placeholder="Mpesa -0756xxx"
                                        value="{{ $event_edit->payment_numbers }}">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Contribution Deadline </label>
                                    <input type="text" name="contribution_deadline" id="contribution_deadline"
                                        placeholder="" value="{{ $event_edit->contribution_deadline }}">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone <span
                                            style="color: red">*</span> </label>
                                    <input type="text" name="contact_phone_1" placeholder="0785008133" required
                                        value="{{ $event_edit->contact_phone_1 }}">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Date <span
                                            style="color: red">*</span> </label>
                                    <input type="text" name="event_date" id="event_date" placeholder="Phone *"
                                        required value="{{ $event_edit->event_date }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Dress Code</label>
                                    <input type="text" name="dress_code" placeholder="eg: Pink, Grey or Brown"
                                        value="{{ $event_edit->dress_code }}">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Image <span
                                            style="color: red">*</span> </label><br>
                                    <input type="file" name="file" placeholder="Event Image">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Church Name</label>
                                    <input type="text" name="church_name" placeholder="e.g., Roman Catholic"
                                        value="{{ $event_edit->church_name }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Church Time</label>
                                    <input type="text" name="church_time" placeholder="e.g., 7:00 AM"
                                        value="{{ $event_edit->church_time }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">
                                        Event Time <span style="color: red">*</span>
                                    </label>
                                    <input type="text" name="event_time" id="event_time"
                                        placeholder="eg 06:45 Jioni" value="{{ $event_edit->event_time }}">
                                    {{-- <div id="am-pm-output" class="mt-10 text-14 text-dark-2"></div> --}}
                                </div>

                                @if (Auth::user()->hasRole('admin'))
                                    <div class="col-sm-6">
                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">SMS Balance</label>
                                        <input type="text" name="sms_balance" placeholder="e.g., 500"
                                            value="{{ $event_edit->sms_balance }}">
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Assigned User To this
                                            Event</label> <br>
                                        <select name="user_id" class="form-control select2">
                                            <option value="">Select user</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $event_edit->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->phone }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">RSVPs Phone Numbers</label>
                                    <div id="phone-list-{{ $event_edit->id }}">
                                        @foreach ($event_edit->rsvps as $rsvp)
                                            <div class="phone-item">
                                                <input type="text" name="phone_number[{{ $rsvp->id }}]"
                                                    value="{{ $rsvp->phone_number }}" placeholder="eg: 0786147878"
                                                    class="mb-2">
                                                <button type="button" class="remove-phone button -sm mb-2"
                                                    onclick="removePhone(this)"
                                                    style="background-color: red; color:white;">Remove</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="add-phone button -sm btn-blue -dark-5 text-blue-1"
                                        onclick="addPhone({{ $event_edit->id }})">Add Phone</button>
                                </div>



                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row y-gap-20 justify-between pt-30">
                        <div class="col-auto sm:w-1/1">
                            <button class="button -sm -outline-purple-1 text-purple-1 sm:w-1/1"
                                data-dismiss="modal">Cancel</button>
                        </div>

                        <div class="col-auto sm:w-1/1">
                            <button type="submit" form="update_event_{{ $event_edit->id }}"
                                class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach


<!-- Select2 CSS & JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for all modals individually
        @foreach ($events as $event_edit)
            $('#edit_event_{{ $event_edit->id }}').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $(this), // keeps dropdown inside modal
                    width: '100%',
                    placeholder: 'Select user',
                    allowClear: true
                });
            });

            // Optional: destroy select2 when modal is hidden to prevent duplication
            $('#edit_event_{{ $event_edit->id }}').on('hidden.bs.modal', function() {
                $(this).find('.select2').select2('destroy');
            });
        @endforeach
    });
</script>


<script>
    function addPhone(eventId) {
        const phoneList = document.getElementById(`phone-list-${eventId}`);
        if (!phoneList) {
            console.error(`Phone list container not found for event ID: ${eventId}`);
            return;
        }

        const newPhone = document.createElement('div');
        newPhone.classList.add('phone-item');
        newPhone.innerHTML = `
            <input type="text" name="phone_number[]" placeholder="eg: 0786147878" class="mb-2">
            <button type="button" class="remove-phone button -sm mb-2"
                    onclick="removePhone(this)"
                    style="background-color: red; color:white;">Remove</button>
        `;

        phoneList.appendChild(newPhone);
    }

    function removePhone(button) {
        if (button && button.parentElement) {
            button.parentElement.remove();
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
