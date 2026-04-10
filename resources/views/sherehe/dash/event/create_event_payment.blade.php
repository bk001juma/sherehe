@extends('layouts.dash')

@section('template_title')
    Create Event
@endsection

@section("page_css")
    <meta http-equiv="cache-control" content="no-cache" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

@endsection
@section('content')
    <div class="dashboard__content bg-light-4 pt-0">

        <section class="page-header -type-1 p-5">
            <div class="container ">
                <div class="page-header__content">
                    <div class="row justify-center text-center">
                        <div class="col-auto">
                            <div data-anim="slide-up delay-1">

                                <h1 class="page-header__title">Event Details</h1>

                            </div>

                            <div data-anim="slide-up delay-2">

                                <p class="page-header__text">We’re on a mission to deliver the best event planning at a reasonable price.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="layout-pt-md layout-pb-lg">
            <div class="container">
                <div class="row y-gap-50">
                    <div class="col-lg-8 bg-white -dark-bg-dark-1 shadow-4 h-100">
                        <div class="shopCheckout-form ">
                            <form action="https://creativelayers.net/themes/educrat-html/post" class="contact-form row x-gap-30 y-gap-30">

                                <div class="col-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event name</label>
                                    <input type="text" name="event_name" placeholder="Harusi ya Joseph">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Select Package</label>
                                    <select class="selectize wide js-selectize">
                                        @foreach($packages as $package)
                                            <option {{$selected_package == $package->id ? 'selected' : null}} value="{{$package->id}}">{{$package->name}} <span style="color: red">{{number_format($package->price)}}</span> TZS</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Type</label>
                                    <select class="selectize wide js-selectize" required onchange="family(value)">
                                        <option>Select event type</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-12" id="family_name"  style="display:none;">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10" >Family name</label>
                                    <input type="text" name="address" placeholder="Familia ya Anthony Lusekela">
                                </div>

                                <div class="col-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Description</label>
                                    <input type="text" name="description" placeholder="Harusi ya Juma na Annunciata">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Location</label>
                                    <input type="text" name="location" placeholder="Mwananyamala DSM">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone</label>
                                    <input type="text" name="contact_phone_1" placeholder="0785008133">
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Date</label>
                                    <input type="text"  name="event_date" id="event_date" placeholder="Phone *">
                                </div>

                                <div class="col-6">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Event Image</label><br>
                                    <input type="file" name="file" placeholder="Event Image">
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="">
                            <div class="pt-30 pb-15 bg-white -dark-bg-dark-1 shadow-4 h-100 rounded-8 ">
                                <h5 class="px-30 text-20 fw-500">
                                    Your event
                                </h5>

                                <div class="d-flex justify-between px-30 mt-25">
                                    <div class="py-15 fw-500 text-dark-1">Product</div>
                                    <div class="py-15 fw-500 text-dark-1">Subtotal</div>
                                </div>

                                <div class="d-flex justify-between border-top-dark px-30">
                                    <div class="py-15 text-grey">SMS x 100</div>
{{--                                    <div class="py-15 text-grey">$59.00</div>--}}
                                </div>

                                <div class="d-flex justify-between px-30">
                                    <div class="py-15 text-grey">Waalikwa x 100</div>
{{--                                    <div class="py-15 text-grey">$67.00</div>--}}
                                </div>

                                <div class="d-flex justify-between border-top-dark px-30">
                      <div class="py-15 fw-500 text-dark-1">Total</div>
                      <div class="py-15 fw-500 text-dark-1">$9,218.00</div>
                    </div>
                  </div>
                            </div>

                            <div class="py-30 px-30 bg-white -dark-bg-dark-1 shadow-4 h-100 rounded-8 ">
                                <h5 class="text-20 fw-500">
                                    Payment
                                </h5>

                                <div class="mt-30">
                                    <div class="form-radio d-flex items-center">
                                        <div class="radio">
                                            <input type="radio" name="radio" checked="checked">
                                            <div class="radio__mark">
                                                <div class="radio__icon"></div>
                                            </div>
                                        </div>
                                        <h5 class="ml-15 text-15 lh-1 fw-500 text-dark-1">Direct bank transfer</h5>
                                    </div>
                                    <p class="ml-25 pl-5 mt-25">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                                </div>

                                <div class="mt-30">
                                    <div class="form-radio d-flex items-center">
                                        <div class="radio">
                                            <input type="radio" name="radio" checked="checked">
                                            <div class="radio__mark">
                                                <div class="radio__icon"></div>
                                            </div>
                                        </div>
                                        <h5 class="ml-15 text-15 lh-1 text-dark-1">Check payments</h5>
                                    </div>
                                </div>

                                <div class="mt-30">
                                    <div class="form-radio d-flex items-center">
                                        <div class="radio">
                                            <input type="radio" name="radio" checked="checked">
                                            <div class="radio__mark">
                                                <div class="radio__icon"></div>
                                            </div>
                                        </div>
                                        <h5 class="ml-15 text-15 lh-1 text-dark-1">Cash on delivery</h5>
                                    </div>
                                </div>

                                <div class="mt-30">
                                    <div class="form-radio d-flex items-center">
                                        <div class="radio">
                                            <input type="radio" name="radio" checked="checked">
                                            <div class="radio__mark">
                                                <div class="radio__icon"></div>
                                            </div>
                                        </div>
                                        <h5 class="ml-15 text-15 lh-1 text-dark-1">PayPal</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-30 ">
                                <button class="button -md btn-primary -accent col-12 -uppercase text-white">Place order</button>
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
        var x = document.getElementById('family_name')
        if(val === '1'){
            x.style.display = 'none';
        }else {
            x.style.display = 'block';
        }
    }

    $(function () {
        $("#checkin").datepicker();
        $("#event_date").datepicker();
    });
</script>
@endsection

