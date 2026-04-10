@extends('layouts.dash')

@section('template_title')
    {{$event->event_name}} Order
@endsection

@section('content')
    <div class="dashboard__content bg-light-4 pt-5">

        <section class="page-header -type-1 p-5">
            <div class="container">
                <div class="page-header__content">
                    <div class="row justify-center text-center">
                        <div class="col-auto">
                            <div data-anim="slide-up delay-1">
                                <h1 class="page-header__title">{{$event->event_name}}</h1>
                            </div>

                            <div data-anim="slide-up delay-2">
                                @if($order->status == 'pending')
                                    <p class="page-header__text">Processing your payment.</p>
                                @else
                                    <p class="page-header__text">WhatsApp SMS paid.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="layout-pt-md layout-pb-lg pt-0 ">
            <div class="container">
                <div class="row no-gutters justify-content-center bg-white -dark-bg-dark-1 shadow-4">
                    <div class="col-xl-8 col-lg-9 col-md-11">
                        @if($order->status == 'pending')
                            <div class="shopCompleted-header pt-5">
                                <div class="d-flex justify-between">
                                    <div class="icon" >
                                        <i class="fa fa-mobile-phone text-white" style="font-size: 40px"></i>
                                    </div>

                                    <div class="lds-ellipsis "><div></div><div></div><div></div><div></div></div>

                                    <div class="icon" onclick="checkPayment()">
                                        <i class="icon icon-save-money text-white" style="font-size: 40px"></i>
                                    </div>
                                </div>

                                <h2 class="title">
                                    Your order is almost complete.<br><h4>Payment Pending</h4>
                                </h2>
                                <img style="width: 60%" src="/lipa.jpg">
                                <div class="subtitle">
                                    {{--                          <p>USSD sent to {{$order->receiver_phone}}. <a @if($order->payment_method == 'mobile') onclick="retryPay()" @else href="{{route('retry_payment',$order->id)}}" @endif  style="color: darkred">Tap Here to Retry</a></p>--}}
                                    {{--                        Confirm payment on your phone to complete.--}}
                                </div>
                            </div>
                        @else
                            <div class="shopCompleted-header">
                                <div class="icon">
                                    <i data-feather="check"></i>
                                </div>
                                <h2 class="title">
                                    Your order is completed!
                                </h2>
                                <div class="subtitle">
                                    Thank you. Your order has been received.
                                </div>
                            </div>
                        @endif


                        <div class="shopCompleted-info">
                            <div class="row no-gutters y-gap-32">
                                <div class="col-md-3 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle">Order No</div>
                                        <div class="title text-purple-1 mt-5">{{$order->id}}</div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle">Date</div>
                                        <div class="title text-purple-1 mt-5">{{date('d M Y',strtotime($order->created_at))}}</div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle">Total</div>
                                        <div class="title text-purple-1 mt-5">{{number_format($order->sms_count)}} SMS</div>
                                        <div class="title text-purple-1 mt-5">{{number_format($order->amount)}} TZS</div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="shopCompleted-info__item">
                                        <div class="subtitle">Payment Method</div>
                                        <div class="title text-purple-1 mt-5">Mobile Lipa namba</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="row y-gap-20 justify-between pt-30 pb-10">
                                <div class="col-auto sm:w-1/1">
                                    <h5>Current Balance  WhatsApp SMS</h5>
                                </div>

                                <div class="col-auto sm:w-1/1">
                                    @if(Auth::user()->hasRole('admin'))
                                        <a href="{{route('sms.purchase.activate',$order->id)}}" class="button -sm -red-1 text-black sm:w-1/1">
                                            Activate
                                        </a>
                                    @endif

                                </div>

                                <div class="col-auto sm:w-1/1">
{{--                                    @if($event_notification->status == 'pending')--}}
                                        <a href="{{route('dash.events')}}" class="button -sm -purple-1 text-white sm:w-1/1">
                                            Got to Event
                                        </a>
{{--                                    @endif--}}

                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </section>

    </div>

    <script>
        function redirectMe(to_here) {
            window.location = to_here;
        }
    </script>
@endsection

@section('page_css')
    <style>
        .lds-ellipsis {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        .lds-ellipsis div {
            position: absolute;
            top: 33px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: darkblue;
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .lds-ellipsis div:nth-child(1) {
            left: 8px;
            animation: lds-ellipsis1 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(2) {
            left: 8px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(3) {
            left: 32px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(4) {
            left: 56px;
            animation: lds-ellipsis3 0.6s infinite;
        }
        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(0);
            }
        }
        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(24px, 0);
            }
        }
    </style>
@endsection



