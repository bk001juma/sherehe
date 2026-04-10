@php
    $eventId = $event->id;
@endphp

<a href="{{ route('dash.event', ['id' => $eventId]) }}"
    class="text-light-1 lh-12 tabs__button js-tabs-button {{ request()->routeIs('dash.event') ? 'is-active' : '' }}"
    data-tab-target=".-tab-item-1" type="button">
    Details and Budget
</a>

<a href="{{ route('dash.event.items', ['id' => $eventId]) }}"
    class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.items') ? 'is-active' : '' }}"
    data-tab-target=".-tab-item-2" type="button">
    Items
</a>

<a href="{{ route('dash.event.pledger.categories', ['id' => $eventId]) }}"
    class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.pledger.categories') ? 'is-active' : '' }}"
    data-tab-target=".-tab-item-8" type="button">
    Pledger Categories
</a>
@if ($event->card_and_ticket_id == 2)
    <a href="{{ route('dash.event.pledges', ['id' => $eventId]) }}"
        class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.pledges') ? 'is-active' : '' }}"
        data-tab-target=".-tab-item-3" type="button">
        Pledges
    </a>
@endif

@if ($event->card_and_ticket_id == 3)
    <a href="{{ route('dash.event.pledges.name', ['id' => $eventId]) }}"
        class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.pledges.name') ? 'is-active' : '' }}"
        data-tab-target=".-tab-item-13" type="button">
        Pledges (Name)
    </a>
@endif

@if ($event->card_and_ticket_id == 4)
    <a href="{{ route('dash.event.pledges.link', ['id' => $eventId]) }}"
        class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.pledges.link') ? 'is-active' : '' }}"
        data-tab-target=".-tab-item-10" type="button">
        Pledges (Link)
    </a>
@endif

@if ($event->cardAndTicket && $event->cardAndTicket->type == 'card')
    <a href="{{ route('dash.event.cards', ['id' => $eventId]) }}"
        class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.cards') ? 'is-active' : '' }}"
        data-tab-target=".-tab-item-5" type="button" id="cardsButton">
        Cards
    </a>
@endif


@if ($event->card_and_ticket_id == 1)
    <a href="{{ route('dash.event.paid.tickets', ['id' => $eventId]) }}"
        class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.paid.tickets') ? 'is-active' : '' }}"
        data-tab-target=".-tab-item-12" type="button">
        Paid Tickets
    </a>
@endif

@if ($event->cardAndTicket && $event->cardAndTicket->type == 'ticket')
    <a href="{{ route('dash.event.tickets', ['id' => $eventId]) }}"
        class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.tickets') ? 'is-active' : '' }}"
        data-tab-target=".-tab-item-11" type="button">
        Tickets
    </a>
@endif

<a href="{{ route('dash.event.sms.notifications', ['id' => $eventId]) }}"
    class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.sms.notifications') ? 'is-active' : '' }}"
    data-tab-target=".-tab-item-6" type="button" id="smsNotificationButton">
    SMS Notifications
</a>

<a href="{{ route('dash.event.whatsapp.notifications', ['id' => $eventId]) }}"
    class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.whatsapp.notifications') ? 'is-active' : '' }}"
    data-tab-target=".-tab-item-7" type="button" id="whatNotificationButton">
    WhatsApp Notifications
</a>

@if ($user->hasRole('admin'))

    @if ($event->card_and_ticket_id == 3)
        <a href="{{ route('card.name.position', ['id' => $eventId]) }}"
            class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('card.name.position') ? 'is-active' : '' }}"
            data-tab-target=".-tab-item-13" type="button">
            Card Setup
        </a>
    @endif

    @if ($event->card_and_ticket_id == 4)
        <a href="{{ route('card.link.position', ['id' => $eventId]) }}"
            class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('card.link.position') ? 'is-active' : '' }}"
            data-tab-target=".-tab-item-10" type="button">
            Card Setup
        </a>
    @endif
    @if ($event->card_and_ticket_id == 1)
        <a href="{{ route('ticket.name.position', ['id' => $eventId]) }}"
            class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('ticket.name.position') ? 'is-active' : '' }}"
            data-tab-target=".-tab-item-12" type="button">
            Ticket Setup
        </a>
    @endif
@endif

<a href="{{ route('dash.event.report', ['id' => $eventId]) }}"
    class="text-light-1 lh-12 tabs__button js-tabs-button ml-30 {{ request()->routeIs('dash.event.report') ? 'is-active' : '' }}"
    data-tab-target=".-tab-item-9" type="button">
    Report
</a>
