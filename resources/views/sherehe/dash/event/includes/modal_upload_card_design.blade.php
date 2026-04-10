<div class="modal fade" id="upload_card_design" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" style="height: 450px">
                <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                    <div class="shopCheckout-form">
                        <form action="{{ route('dash.event.card_type.upload_design', $event->id) }}"
                            enctype="multipart/form-data" method="post" id="add_card_design_form"
                            class="contact-form row x-gap-30 y-gap-30">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="col-12">
                                <h5 class="text-20">Upload Your Card Design</h5>
                            </div>

                            <div id="one">
                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Double Card</label>
                                    <br>
                                    <input type="file" value="{{ old('double_card') }}" name="double_card">
                                    @if ($errors->has('double_card'))
                                        <p style="color: red">{{ $errors->first('double_card') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Single Card</label> <br>
                                    <input type="file" value="{{ old('single_card') }}" name="single_card">
                                    @if ($errors->has('single_card'))
                                        <p style="color: red">{{ $errors->first('single_card') }}</p>
                                    @endif
                                </div>

                                {{-- <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">VVIP Card</label>
                                    <br>
                                    <input type="file" value="{{ old('vvip_card') }}" name="vvip_card">
                                    @if ($errors->has('vvip_card'))
                                        <p style="color: red">{{ $errors->first('vvip_card') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">VIP Card</label> <br>
                                    <input type="file" value="{{ old('vip_card') }}" name="vip_card">
                                    @if ($errors->has('vip_card'))
                                        <p style="color: red">{{ $errors->first('vip_card') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Regular Card</label> <br>
                                    <input type="file" value="{{ old('regular_card') }}" name="regular_card">
                                    @if ($errors->has('regular_card'))
                                        <p style="color: red">{{ $errors->first('regular_card') }}</p>
                                    @endif
                                </div> --}}




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
                        <button type="submit" form="add_card_design_form"
                            class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
