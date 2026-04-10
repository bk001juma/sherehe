@foreach($event->items as $ed_item)
    <div class="modal fade" id="item_edit_{{$ed_item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" >

                    <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                        <div class="shopCheckout-form">
                            <form action="{{route('dash.event.item_update',$ed_item->id)}}" method="post" id="update_item_{{$ed_item->id}}" class="contact-form row x-gap-30 y-gap-30">
                                @csrf
                                <input hidden="hidden" name="id" value="{{$ed_item->id}}">
                                <div class="col-12">
                                    <h5 class="text-20">Edit Event Item</h5>
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Item Type *</label>
                                    <select name="item_type_id" class="selectize wide js-selectize" style="display: none;" required>
                                        @foreach($item_types as $item_typ)
                                            <option {{$ed_item->item_type->id == $item_typ->id ? 'selected': ''}} value="{{$item_typ->id}}">{{$item_typ->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('item_type_id'))<p style="color: red">{{ $errors->first('item_type_id') }}</p>@endif
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Item Name</label>
                                    <input type="text" value="{{$ed_item->name}}" name="name" placeholder="MC Cheni" required>
                                    @if($errors->has('name'))<p style="color: red">{{ $errors->first('name') }}</p>@endif
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Amount</label>
                                    <input type="text" value="{{$ed_item->amount}}" name="amount" placeholder="100000" required>
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
                            <button type="submit" form="update_item_{{$ed_item->id}}" class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
