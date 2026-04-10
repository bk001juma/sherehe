@foreach($event->items as $ed_item)
    <div class="modal fade" id="item_pay_{{$ed_item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" >

              <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">

                <div class="col-auto">
                  <div data-anim="slide-up delay-1" class="is-in-view text-red-1 mt-10">
{{--                      <i class="fa fa-exclamation-triangle text-60 ml-10"></i>--}}
{{--                      <h5 class="text-dark-5">Add payment for</h5>--}}
{{--                      <br>--}}
                      <h3 class="page-header__title text-dark-4">{{$ed_item->name}}</h3>
                      <h7 class="text-orange-1">{{$ed_item->item_type->name}}</h7>

                      <p class="text-dark-4">Previous Payments</p>
                      <table class="table table-striped table-sm data-table">
                          <thead class="thead">
                          <tr>
                              <th class="p-2">Amount</th>
                              <th class="p-2">Transaction ID</th>
                              <th class="p-2">Date</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach($ed_item->payments as $pledge_payment)
                              <tr>
                                  <td class="p-2">{{number_format($pledge_payment->amount)}} TZS<br><span style="font-size: 9px; color: orange"> {{$pledge_payment->method}}</span></td>
                                  <td class="p-2">{{$pledge_payment->transaction_id}}</td>
                                  <td class="p-2">{{date('d M Y',strtotime($pledge_payment->created_at))}}</td>
                              </tr>
                          @endforeach

                          </tbody>

                      </table>
                  </div>

                  <div data-anim="slide-up delay-2" class="is-in-view">
                      <h5 class="text-dark-4"><i class="fa fa-plus"></i> Add Payment</h5>
                      <hr>
                      <div class="shopCheckout-form">
                          <form action="{{route('dash.event.add.item.payment')}}" method="post" id="add_payment_{{$ed_item->id}}" class="contact-form row x-gap-30 y-gap-30">
                              @csrf
                              <input hidden="hidden" name="event_item_id" value="{{$ed_item->id}}">

                              <div class="col-md-6">
                                  <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Payment Method</label>
                                  <select name="method" class="selectize wide js-selectize" style="display: none;" required>
                                      <option>Cash</option>
                                      <option>Mobile payment</option>
                                      <option>Bank</option>
                                      <option>Other</option>
                                  </select>
                                  @if($errors->has('method'))<p style="color: red">{{ $errors->first('method') }}</p>@endif
                              </div>

                              <div class="col-md-6">
                                  <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Transaction ID</label>
                                  <input type="text" value="{{old('transaction_id')}}" name="transaction_id" placeholder="MP993838493..." required>
                                  @if($errors->has('transaction_id'))<p style="color: red">{{ $errors->first('transaction_id') }}</p>@endif
                              </div>
                              <div class="col-md-12">
                                  <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Amount</label>
                                  <input type="text" value="{{old('amount')}}" name="amount" placeholder="100000" required>
                                  @if($errors->has('amount'))<p style="color: red">{{ $errors->first('amount') }}</p>@endif
                              </div>
                          </form>
                      </div>

                  </div>
                </div>

              </div>
            </div>
          <div class="modal-footer">
            <div class="row y-gap-20 justify-between pt-30">
                <div class="col-auto sm:w-1/1">
                  <button class="button -sm -outline-purple-1 text-purple-1 sm:w-1/1" data-dismiss="modal">Cancel</button>
                </div>

                <div class="col-auto sm:w-1/1">
                  <button type="submit" form="add_payment_{{$ed_item->id}}" class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Add Payment</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
@endforeach
