@php
    $eventId = $event->id;
@endphp

@if ($user->hasRole('admin'))
    <div class="col-auto sm:w-1/1">
        <a class="button -sm" style="background-color: orange; color: white;"
            href="{{ route('dash.event.delete.all.pledges', [$eventId]) }}" id="invitationButton"
            onclick="return confirmDelete()">
            Delete All Pledges
        </a>
    </div>
@endif
