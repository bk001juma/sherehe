@extends('layouts.dash')

@section('template_title')
    Users Management
@endsection



@section('content')
    <div class="dashboard__content bg-light-4" style="background-color: #f4f6f6 ">
        <div class="row y-gap-20 justify-between pt-30">
            <div class="col-auto sm:w-1/1">
                <h1 class="text-30 lh-12 fw-700">Users Management</h1>
                <div class="mt-10"><i class="icon icon-clock"></i> <strong> Users Management</strong>
                    {{ date('D d M Y') }}
                </div>
            </div>



            @include('sherehe.dash.includes.alerts')

        </div>


        <div class="row y-gap-30">
            <div class="col-12">

                @include('sherehe.dash.event.tabs.alerts')

                {{-- Include Top side Tab --}}

                <div class="rounded-16 bg-white -dark-bg-dark-1 shadow-4 h-100">

                    <div class="row y-gap-30 mb-10 d-flex" style="gap: 15px; flex-wrap: nowrap;">


                        <div class="content" id="contentAllPaidTicket">
                            <div class="table-responsive users-table">
                                <table id="myTable" class="table table-bordered table-striped">

                                    <thead class="thead" style="background-color: #d3ccbc;">
                                        <tr>
                                            <th style="color: white;">No.</th>
                                            <th style="color: white;">Name</th>
                                            <th style="color: white;">Phone</th>
                                            <th style="color: white;">Role</th>
                                            <th style="color: white;">Created At</th>
                                            <th style="color: white;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name ?? '-' }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>
                                                    @if ($user->roles->isNotEmpty())
                                                        {{ $user->roles->pluck('name')->join(', ') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y h:i A') }}
                                                </td>

                                                <td>
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                        data-target="#modal-{{ $user->id }}">
                                                        Edit
                                                    </button>

                                                </td>
                                            </tr>


                                            <!-- Modal ya kila user -->
                                            <div class="modal fade" id="modal-{{ $user->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form action="{{ route('users.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Update User</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                {{-- Name --}}
                                                                <div class="col-md-12 mb-2">
                                                                    <label for="name-{{ $user->id }}">Full Name</label>
                                                                    <input type="text" name="name"
                                                                        id="name-{{ $user->id }}"
                                                                        value="{{ $user->name }}" class="form-control"
                                                                        style="border:none; box-shadow:inset 0 0 0 1px #ccc; border-radius:6px; padding:8px 12px; outline:none;">
                                                                </div>

                                                                {{-- Phone --}}
                                                                <div class="col-md-12 mb-2">
                                                                    <label for="phone-{{ $user->id }}">Phone</label>
                                                                    <input type="text" name="phone"
                                                                        id="phone-{{ $user->id }}"
                                                                        value="{{ $user->phone }}" class="form-control"
                                                                        style="border:none; box-shadow:inset 0 0 0 1px #ccc; border-radius:6px; padding:8px 12px; outline:none;">
                                                                </div>

                                                                {{-- Role --}}
                                                                <div class="col-md-12 mb-2">
                                                                    <label for="role-{{ $user->id }}">Role</label>
                                                                    <select name="role" id="role-{{ $user->id }}"
                                                                        class="form-control">
                                                                        <option value="">-- Select Role --</option>
                                                                        @foreach ($roles as $role)
                                                                            <option value="{{ $role->id }}"
                                                                                @if ($user->roles->pluck('id')->contains($role->id)) selected @endif>
                                                                                {{ $role->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                {{-- Status --}}
                                                                <div class="col-md-12">
                                                                    <label for="status-{{ $user->id }}">User
                                                                        Status</label>
                                                                    <select name="status" id="status-{{ $user->id }}"
                                                                        class="form-control status-select"
                                                                        data-username="{{ $user->name }}"
                                                                        data-phone="{{ $user->phone }}" required>
                                                                        <option value="1" selected>Active</option>
                                                                        <option value="0">Inactive(Delete User)
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>

    </div>






    @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

        <script>
            function redirectMe(to_here) {
                window.location = to_here;
            }
        </script>
        <script>
            // $(document).ready(function() {

            $(document).ready(function() {
                $('#myTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                });
            });
        </script>

        <script>
            $(document).on('change', '.status-select', function() {
                let value = $(this).val();
                let username = $(this).data('username');
                let phone = $(this).data('phone');

                if (value === "0") {
                    alert("⚠️ You have selected to permanently delete this user:\n\n" +
                        username + " (" + phone + ")\n\n" +
                        "Once you click Save, this user will be permanently removed and cannot be restored!");
                }

            });
        </script>
    @endpush
@endsection
