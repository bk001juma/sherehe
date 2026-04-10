@foreach($event->items as $ed_item)
    <div class="modal fade" id="delete_item_{{$ed_item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content row justify-center text-center">
            <div class="bg-white shadow-2 rounded-8 border-light py-10 px-10" style="max-height: 350px">

              <div class="courses-single-info__content scroll-bar-1 pt-30 pb-20 px-20">

                <div class="col-auto">
                  <div data-anim="slide-up delay-1" class="is-in-view text-red-1 mt-30">
                      <i class="fa fa-exclamation-triangle text-60 ml-10"></i>
                      <h3>Are you sure you want to delete</h3>
                      <h1 class="page-header__title text-dark-5">{{$ed_item->name}}?</h1>
                  </div>

                  <div data-anim="slide-up delay-2" class="is-in-view">
                      <br>
                    <p class="page-header__text">This process is irreversible and all associated data will be lost.</p>

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
                  <a href="{{route('dash.event.item_destroy',[$ed_item->id])}}" class="button -sm -red-1 text-white sm:w-1/1">Yes Delete</a>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
@endforeach
