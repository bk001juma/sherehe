@foreach ($event->pledges as $pledge_edit)
    <div class="modal fade" id="pledge_edit_{{ $pledge_edit->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10">

                    <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                        <div class="shopCheckout-form">
                            <form action="{{ route('dash.event.pledge.update', $pledge_edit->id) }}" method="post"
                                id="update_item_{{ $pledge_edit->id }}" class="contact-form row x-gap-30 y-gap-30">
                                @csrf
                                <input hidden="hidden" name="id" value="{{ $pledge_edit->id }}">
                                <div class="col-12">
                                    <h5 class="text-20">Edit Pledge</h5>
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Full Name</label>
                                    <input type="text" value="{{ $pledge_edit->full_name }}" name="full_name"
                                        placeholder="MC Cheni" required>
                                    @if ($errors->has('full_name'))
                                        <p style="color: red">{{ $errors->first('full_name') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone Number</label>
                                    <input type="text" value="{{ $pledge_edit->phone }}" name="phone"
                                        placeholder="0786..." required>
                                    @if ($errors->has('phone'))
                                        <p style="color: red">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Amount</label>
                                    <input type="text" value="{{ $pledge_edit->amount }}" name="amount"
                                        placeholder="100000" required>
                                    @if ($errors->has('amount'))
                                        <p style="color: red">{{ $errors->first('amount') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Table Number</label>
                                    <input type="text" value="{{ $pledge_edit->table_number }}" name="table_number"
                                        placeholder="Table Number">

                                </div>
                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Select Pledge Category</label>
                                    <select name="event_attendees_category_id">
                                        <option value="">Select Category</option>
                                        @foreach ($event_attendees_categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $pledge_edit->event_attendees_category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('event_attendees_category_id'))
                                        <p style="color: red">{{ $errors->first('event_attendees_category_id') }}</p>
                                    @endif
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
                            <button type="submit" form="update_item_{{ $pledge_edit->id }}"
                                class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
