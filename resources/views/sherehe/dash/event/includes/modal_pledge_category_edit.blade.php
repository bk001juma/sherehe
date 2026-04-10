@foreach($event->attendeesCategories as $attendeesCategorie_edit)
    <div class="modal fade" id="pledge_category_edit_{{$attendeesCategorie_edit->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" >

                    <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">
                        <div class="shopCheckout-form">
                            <form action="{{route('dash.event.pledge.category.update',$attendeesCategorie_edit->id)}}" method="post" id="update_item_category_p_{{$attendeesCategorie_edit->id}}" class="contact-form row x-gap-30 y-gap-30">
                                @csrf
                                <input hidden="hidden" name="id" value="{{$attendeesCategorie_edit->id}}">
                                <div class="col-12">
                                    <h5 class="text-20">Edit Pledge Category</h5>
                                </div>

                                <div class="col-md-12">
                                    <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Name</label>
                                    <input type="text" value="{{$attendeesCategorie_edit->name}}" name="name" placeholder="Friends" required>
                                    @if($errors->has('name'))<p style="color: red">{{ $errors->first('name') }}</p>@endif
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
                            <button type="submit" form="update_item_category_p_{{$attendeesCategorie_edit->id}}" class="button -sm -purple-1 text-purple-3 mr-5 sm:w-1/1">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
