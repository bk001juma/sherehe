<div class="modal fade" id="buy_whatsapp_sms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10">

                {{-- <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20"> --}}
                <div class="shopCheckout-form">
                    <form method="post" action="{{ route('whatsapp.purchase') }}" id="purchase_whatsapp_sms"
                        class="contact-form row x-gap-30 y-gap-30">
                        @csrf
                        <input hidden="hidden" name="event_id" value="{{ $event->id }}">
                        <input hidden="hidden" name="price_per_sms" value="35">
                        <div class="col-12">
                            <h5 class="text-20">Buy WhatsApp SMS</h5>
                            <p>35 TZS per SMS</p>
                        </div>

                        <div class="col-md-12">
                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">SMS Quantity</label>
                            <input type="number" id="sms_count" min="50" value="{{ old('sms_count') }}"
                                name="sms_count" oninput="updateTotalAmount1(this.value)" placeholder="" required>
                            @if ($errors->has('sms_count'))
                                <p style="color: red">{{ $errors->first('sms_count') }}</p>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label for="" class="text-16 1h-1 fw-500 text-dark-1 mb-10">Total Amount</label>
                            <input type="text" id="total_amount1" readonly value="0 TZS" name="total_amount">

                        </div>

                        {{--                      <div class="col-md-12"> --}}
                        {{--                          <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Amount</label> --}}
                        {{--                          <input type="text" value="{{old('amount')}}" name="amount" placeholder="100000" required> --}}
                        {{--                          @if ($errors->has('amount'))<p style="color: red">{{ $errors->first('amount') }}</p>@endif --}}
                        {{--                      </div> --}}
                    </form>
                </div>
                {{-- </div> --}}
            </div>
            <div class="modal-footer">
                <div class="row y-gap-20 justify-between pt-30">
                    <div class="col-auto sm:w-1/1">
                        <button class="button -sm -outline-purple-1 text-purple-1 sm:w-1/1"
                            data-dismiss="modal">Cancel</button>
                    </div>

                    <div class="col-auto sm:w-1/1">
                        <button type="submit" form="purchase_whatsapp_sms"
                            class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTotalAmount1(value1) {

        var quantity = value1;
        var pricePerSms = 35;
        var totalAmount = 0;

        totalAmount = quantity * pricePerSms;

        document.getElementById('total_amount1').value = totalAmount + ' TZS';
    }
</script>
