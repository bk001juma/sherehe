<div class="sidebar -dashboard">

    <div class="sidebar__item {{ Route::is('dashboard') ? '-is-active -dark-bg-dark-2' : null }}">
        <a href="{{ route('dashboard') }}" class="d-flex items-center text-17 lh-1 fw-500 ">
            <i class="fa fa-home text-20 mr-15 dashboard-icon"></i>
            Dashboard
        </a>
    </div>

    @if (Auth::user()->hasRole('admin'))
        <div class="sidebar__item {{ Route::is('dash.events.all') ? '-is-active -dark-bg-dark-2' : null }}">
            <a href="{{ route('dash.events.all') }}" class="d-flex items-center text-17 lh-1 fw-500 ">
                <i class="fa fa-calendar text-20 mr-15"></i>
                All Events
            </a>
        </div>
    @endif

    <div
        class="sidebar__item {{ Route::is('my_classes', 'dash.events', 'dash.event') ? '-is-active -dark-bg-dark-2' : null }}">
        <a href="{{ route('dash.events') }}" class="d-flex items-center text-17 lh-1 fw-500 ">
            <i class="fa fa-user text-20 mr-15"></i>
            My Events
        </a>
    </div>

    @if (Auth::user()->hasRole('admin'))
        <div class="sidebar__item {{ Route::is('transactions.index') ? '-is-active -dark-bg-dark-2' : null }}">
            <a href="{{ route('transactions.index') }}" class="d-flex items-center text-17 lh-1 fw-500 ">
                <i class="fa fa-credit-card text-20 mr-15"></i>
                Transactions
            </a>
        </div>
    @endif

    @if (Auth::user()->hasRole('admin'))
        <div class="sidebar__item {{ Route::is('users.index') ? '-is-active -dark-bg-dark-2' : null }}">
            <a href="{{ route('users.index') }}" class="d-flex items-center text-17 lh-1 fw-500 ">
                <i class="fa fa-user text-20 mr-15"></i>
                Users
            </a>
        </div>
    @endif


    <div class="sidebar__item ">
        <a class="d-flex items-center text-17 lh-1 fw-500 " href="{{ route('logout') }}"
            onclick="event.preventDefault();  document.getElementById('logout-form').submit();"> <i
                class="fa fa-sign-out text-20 mr-15"></i> {{ __('Logout') }}</a>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf</form>
</div>
