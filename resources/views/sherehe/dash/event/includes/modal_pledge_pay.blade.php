@foreach($event->pledges as $pledge_pay)
    <div class="modal fade" id="pledge_pay_{{$pledge_pay->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="bg-white shadow-2 rounded-8 border-light py-0 px-0" >

              <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">

                <div class="col-auto">
                  <div data-anim="slide-up delay-1" class="is-in-view  mt-10">
{{--                      <i class="fa fa-exclamation-triangle text-60 ml-10"></i>--}}
{{--                      <h5 class="text-dark-5">Add payment for</h5>--}}
{{--                      <br>--}}
                      <h3 class="page-header__title">{{$pledge_pay->full_name}}</h3>
                      <p>Previous Payments</p>
{{--                      <p class="text-dark-5 text-right">Pledged: {{$pledge_pay->amount}} TZS</p>--}}
{{--                      <p class="text-dark-5 text-right">Paid: {{$pledge_pay->paid}} TZS</p>--}}
{{--                      <p class="text-dark-5 text-right">Balance: {{$pledge_pay->amount - $pledge_pay->paid}} TZS</p>--}}
                      <table class="table table-striped table-sm data-table">
                          <thead class="thead">
                          <tr>
                              <th>Amount</th>
                              <th>Transaction ID</th>
                              <th>Date</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach($pledge_pay->payments as $pledge_payment)
                              <tr>
                                  <td class="mb-0">{{number_format($pledge_payment->amount)}} TZS<br><span style="font-size: 9px; color: orange"> {{$pledge_payment->method}}</span></td>
                                  <td>{{$pledge_payment->transaction_id}}</td>
                                  <td>{{date('d M Y',strtotime($pledge_payment->created_at))}}</td>
                              </tr>
                          @endforeach

                          </tbody>

                      </table>
                  </div>

                  <div data-anim="slide-up delay-2" class="is-in-view">
                      <br>
                      <h5 class="text-dark-5 pt-0">Add new payment</h5>
                      <div class="shopCheckout-form">
                          <form action="{{route('dash.event.pledge.pay',$pledge_pay->id)}}" method="post" id="add_pledge_payment_{{$pledge_pay->id}}" class="contact-form row x-gap-30 y-gap-30">
                              @csrf
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
                  <button type="submit" form="add_pledge_payment_{{$pledge_pay->id}}" class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Add Payment</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
@endforeach
