<div class="modal fade" id="add_pledge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" style="height: 450px">

                <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                    <div class="shopCheckout-form">
                        <form action="{{ route('dash.event.add_pledge') }}" enctype="multipart/form-data" method="post"
                            id="add_event_pledge" class="contact-form row x-gap-30 y-gap-30">
                            @csrf
                            <input type="hidden" name="tab" value="-tab-item-3">
                            <input hidden="hidden" name="event_id" value="{{ $event->id }}">
                            <div class="col-12">
                                <h5 class="text-20">Add Pledge</h5>
                            </div>

                            <div id="one">
                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Full Name</label>
                                    <input type="text" value="{{ old('full_name') }}" name="full_name"
                                        placeholder="Mr. John Joseph">
                                    @if ($errors->has('full_name'))
                                        <p style="color: red">{{ $errors->first('full_name') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone Number</label>
                                    <input type="text" value="{{ old('phone') }}" name="phone"
                                        placeholder="0712000000">
                                    @if ($errors->has('phone'))
                                        <p style="color: red">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Amount</label>
                                    <input type="text" value="{{ old('amount') }}" name="amount"
                                        placeholder="100000">
                                    @if ($errors->has('amount'))
                                        <p style="color: red">{{ $errors->first('amount') }}</p>
                                    @endif
                                </div>
                                 <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Table Number</label>
                                    <input type="text" value="{{ old('table_number') }}" name="table_number"
                                        placeholder="Enter Table Number">

                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Select Pledge Category</label>
                                    <select name="event_attendees_category_id">
                                        <option value="">-- Select Category --</option>
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

                            </div>

                            <div id="two" style="display: none">
                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Excel File</label><br>
                                    <input type="file" value="{{ old('file') }}" name="file">
                                    @if ($errors->has('file'))
                                        <p style="color: red">{{ $errors->first('file') }}</p>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row y-gap-20 justify-between pt-30">
                    <div class="col-auto sm:w-1/1">
                        <button onclick="fileSet()" class="button -sm -outline-purple-1 text-orange-1 sm:w-1/1"
                            id="file_button"><i class="icon icon-document"></i>Excel Import</button>
                    </div>

                    <div class="col-auto sm:w-1/1">
                        <button class="button -sm -outline-purple-1 text-purple-1 sm:w-1/1"
                            data-dismiss="modal">Cancel</button>
                    </div>

                    <div class="col-auto sm:w-1/1">
                        <button type="submit" form="add_event_pledge"
                            class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
