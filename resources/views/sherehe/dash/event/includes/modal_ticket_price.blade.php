<div class="modal fade" id="ticket_price" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10">

                <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                    <div class="shopCheckout-form">
                        <form action="{{ route('dash.event.card_type.update', $event->card_types->id) }}" method="post"
                            id="update_ticket_price" class="contact-form row x-gap-30 y-gap-30">
                            @csrf
                            <input hidden="hidden" name="event_id" value="{{ $event->id }}">
                            <div class="col-12">
                                <h5 class="text-20">Change Ticket Price</h5>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Single</label>
                                <input type="text" value="{{ $event->card_types->single_amount }}"
                                    name="single_amount" placeholder="Mapambo" required>
                                @if ($errors->has('single_amount'))
                                    <p style="color: red">{{ $errors->first('single_amount') }}</p>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Double</label>
                                <input type="text" value="{{ $event->card_types->double_amount }}"
                                    name="double_amount" placeholder="100000" required>
                                @if ($errors->has('double_amount'))
                                    <p style="color: red">{{ $errors->first('double_amount') }}</p>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Regular</label>
                                <input type="text" value="{{ $event->card_types->regular_amount }}"
                                    name="regular_amount" placeholder="Mapambo">
                                @if ($errors->has('regular_amount'))
                                    <p style="color: red">{{ $errors->first('regular_amount') }}</p>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">VIP</label>
                                <input type="text" value="{{ $event->card_types->vip_amount }}" name="vip_amount"
                                    placeholder="100000">
                                @if ($errors->has('vip_amount'))
                                    <p style="color: red">{{ $errors->first('vip_amount') }}</p>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">VVIP</label>
                                <input type="text" value="{{ $event->card_types->vvip_amount }}" name="vvip_amount"
                                    placeholder="100000">
                                @if ($errors->has('vvip_amount'))
                                    <p style="color: red">{{ $errors->first('vvip_amount') }}</p>
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
                        <button type="submit" form="update_ticket_price"
                            class="button -sm -purple-1 text-white sm:w-1/1">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
