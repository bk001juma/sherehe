<div class="modal fade" id="notify_pledges" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10">

                <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                    <div class="shopCheckout-form">
                        <form action="{{ route('dash.notification.sms.create', $event->id) }}" method="post"
                            id="notify_sms" class="contact-form row x-gap-30 y-gap-30">
                            @csrf
                            <input hidden="hidden" name="event_id" value="{{ $event->id }}">
                            <div class="col-12">
                                <h5 class="text-20">Notify Pledges</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Group</label>
                                <select name="group" class="selectize wide js-selectize" style="display: none;"
                                    required>
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


                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Sample message</label>
                                <div class="d-flex items-center justify-start">
                                    <div class="lh-11 fw-500 text-dark-5 mr-10" id="sender_text">SHEREHE</div>
                                    <div class="text-14 lh-11 mr-10">3 minutes ago</div>

                                </div>
                                <div class="d-inline-block mt-15 pull-right">
                                    <div class="py-20 px-30 bg-light-7 -dark-bg-dark-2 text-purple-1 rounded-8 text-left"
                                        id="sample">

                                    </div>
                                    <i class="icon icon-check pull-right"></i>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Message</label>
                                <textarea name="sms" id="source" onchange="sample_dc()" onkeypress="sample_dc()" onkeydown="sample_dc()"
                                    onkeyup="sample_dc()"></textarea>
                                <p>
                                    <span id="char_count">0</span>/160 characters |
                                    <span id="sms_count">0</span> SMS
                                </p>
                                {{--                          <input type="text" value="{{old('amount')}}" name="amount" placeholder="100000" required> --}}
                                @if ($errors->has('amount'))
                                    <p style="color: red">{{ $errors->first('amount') }}</p>
                                @endif
                            </div>
                            <div>
                                <p>Key</p>
                                <table>
                                    <tr>
                                        <td><small style="color: red">@name </small></td>
                                        <td>Pledge name</td>
                                    </tr>
                                    <tr>
                                        <td><small style="color: red">@amount </small></td>
                                        <td>Pledge amount</td>
                                    </tr>
                                    <tr>
                                        <td><small style="color: red">@paid </small></td>
                                        <td>Paid amount</td>
                                    </tr>
                                    <tr>
                                        <td><small style="color: red">@balance </small></td>
                                        <td>Remaining balance</td>
                                    </tr>
                                    <tr>
                                        <td><small style="color: red">@phone </small></td>
                                        <td>Pledge phone number</td>
                                    </tr>
                                    <tr>
                                        <td><small style="color: red">@date </small></td>
                                        <td>Event Date</td>
                                    </tr>

                                </table>

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
                        <button type="submit" form="notify_sms"
                            class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    }

    function sender_change() {
        var b = document.getElementById('sender');
        var a = document.getElementById('sender_text');
        // var value = b.value;
        a.innerText = b.options[b.selectedIndex].text;

        console.log(a.innerText);
    }
</script>
