<div class="modal fade" id="add_item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" >

          <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
            <div class="shopCheckout-form">
                  <form action="{{route('dash.event.add_item')}}" method="post" id="create_lesson" class="contact-form row x-gap-30 y-gap-30">
                      @csrf
                      <input hidden="hidden" name="event_id" value="{{$event->id}}">
                      <div class="col-12">
                          <h5 class="text-20">Add Item</h5>
                      </div>

                      <div class="col-md-12">
                          <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Item Type *</label>
                          <select name="item_type_id" class="selectize wide js-selectize" style="display: none;" required>
                              @foreach($item_types as $item_typ)
                                  <option value="{{$item_typ->id}}">{{$item_typ->name}}</option>
                              @endforeach
                          </select>
                          @if($errors->has('item_type_id'))<p style="color: red">{{ $errors->first('item_type_id') }}</p>@endif
                      </div>

                      <div class="col-md-12">
                          <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Item Name</label>
                          <input type="text" value="{{old('name')}}" name="name" placeholder="Mapambo" required>
                          @if($errors->has('name'))<p style="color: red">{{ $errors->first('name') }}</p>@endif
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
      <div class="modal-footer">
        <div class="row y-gap-20 justify-between pt-30">
            <div class="col-auto sm:w-1/1">
              <button class="button -sm -outline-purple-1 text-purple-1 sm:w-1/1" data-dismiss="modal">Cancel</button>
            </div>

            <div class="col-auto sm:w-1/1">
              <button type="submit" form="create_lesson" class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Save</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
