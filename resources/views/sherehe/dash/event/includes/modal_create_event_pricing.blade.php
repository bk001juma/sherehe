<div class="modal fade" id="create_event_pricing" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10">
                <div class="container">
                    <div class="col-12">
                        <h2 class="text-20 text-center">Packages</h2>
                    </div>
                    <div class="row y-gap-5 justify-between pt-60 lg:pt-40">
                        @foreach ($packages as $package)
                            <div class="col-lg-4 col-md-6">
                                <div class="priceCard -type-1 rounded-16 bg-white shadow-2">
                                    <div class="priceCard__content py-5 px-8 text-center">
                                        <div class="priceCard__type text-18 lh-11 fw-500 text-dark-1"
                                            style="color: #003366">{{ $package->name }}</div>
                                        <div class="priceCard__price text-45 lh-11 fw-700 text-dark-1 mt-15">
                                            <span style="font-size: 0.3em">Tsh</span>
                                            {{ number_format($package->price) }}
                                        </div>

                                        <div class="text-left y-gap-15 mt-35">
                                            <div>
                                                <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                We provide budget dashboard for self management of the event.
                                            </div>

                                            <div>
                                                <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                SMS Reminders during fund raising for the event.
                                            </div>

                                            <div>
                                                <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                Designing & Distribution of E - Cards.
                                            </div>

                                            @if (!empty($package->optional_field_1))
                                                <div>
                                                    <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                    {!! $package->optional_field_1 !!}
                                                </div>
                                            @endif

                                            @if (!empty($package->optional_field_2))
                                                <div>
                                                    <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                    {{ $package->optional_field_2 }}
                                                </div>
                                            @endif

                                            @if (!empty($package->optional_field_3))
                                                <div>
                                                    <i class="text-purple-1 pr-8" data-feather="check"></i>
                                                    {{ $package->optional_field_3 }}
                                                </div>
                                            @endif


                                        </div>

                                        <div class="d-inline-block mt-30">
                                            <button class="button -sm -dark-5 text-blue-1"
                                                onclick="window.location.href='{{ route('dash.create_event', [$package->id]) }}'; $('#create_event_pricing').modal('hide');">
                                                Proceed
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row y-gap-20 justify-between pt-30">
                    <div class="col-auto sm:w-1/1">
                        <button class="button -sm -outline-purple-1 text-purple-1 sm:w-1/1"
                            data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
