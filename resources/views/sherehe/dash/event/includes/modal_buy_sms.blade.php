<div class="modal fade" id="buy_sms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" style="margin: 20px">

                {{-- <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20"> --}}
                <div class="shopCheckout-form">
                    <form method="post" action="{{ route('sms.purchase') }}" id="purchase_sms"
                        class="contact-form row x-gap-30 y-gap-30">
                        @csrf
                        <input hidden="hidden" name="event_id" value="{{ $event->id }}">
                        <input hidden="hidden" name="price_per_sms" value="30">
                        <div class="col-12">
                            <h5 class="text-20">Buy SMS</h5>
                            <p>30 TZS per SMS</p>
                        </div>

                        <div class="col-md-6">
                            <label for="" class="text-16 1h-1 fw-500 text-dark-1 mb-10">SMS Quantity</label>
                            <input type="text" id="sms_count" min="50" value="{{ old('sms_count') }}"
                                name="sms_count" oninput="updateTotalAmount(this.value)" placeholder="" required>
                            @if ($errors->has('sms_count'))
                                <p style="color: red">{{ $errors->first('sms_count') }}</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="" class="text-16 1h-1 fw-500 text-dark-1 mb-10">Total Amount</label>
                            <input type="text" id="total_amount" readonly value="TZS 0" name="total_amount">

                        </div>

                        <div class="d-flex justify-content-center">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ asset('logo/mpesa.png') }}" alt="Mpesa Logo" class="mx-2"
                                        width="50">
                                </div>
                                <div class="col-md-4">
                                    <img src="{{ asset('logo/tigopesa.png') }}" alt="Tigopesa Logo" class="mx-2"
                                        height="100" width="100">
                                </div>
                                <div class="col-md-4">
                                    <img src="{{ asset('logo/airtelmoney.png') }}" alt="Airtel Money Logo" style="padding-top: 20px"
                                        class="mx-2" height="70" width="70">
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">
                            {{-- <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone to send USSD <small
                                    style="font-size: 11px; color: red">(Namba ya simu yenye pesa Only Voda and
                                    Airtel)</small></label> --}}
                            <input type="text" value="{{ str_replace('255', '0', Auth::user()->phone) }}"
                                name="phone" placeholder="0786147878" required>
                            @if ($errors->has('phone'))
                                <p style="color: red">{{ $errors->first('phone') }}</p>
                            @endif
                        </div>
                        <div class="d-flex justify-content-center">
                            <span style="margin-top: 9px"> Powered By </span><img
                                src="{{ asset('logo/papihumtech.png') }}" alt="papihumtech Logo" class="mx-2"
                                width="100">
                        </div>

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
                        <button type="submit" form="purchase_sms"
                            class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTotalAmount(value1) {

        var quantity = value1;
        var pricePerSms = 30;
        var totalAmount = 0;

        totalAmount = quantity * pricePerSms;
        var formattedAmount = new Intl.NumberFormat('en-US').format(totalAmount);

        document.getElementById('total_amount').value = 'TZS ' + formattedAmount;
    }
</script>
