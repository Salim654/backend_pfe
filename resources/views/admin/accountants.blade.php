@extends('layout')

@section('header')
    Accountants
@endsection

@section('content')
    <div class="dashboard-content">
        <h2>Accountant Management</h2>
        <p>Manage accountants in the system.</p>

        <!-- Search Form and Add Account Button -->
        <div class="row mb-3 align-items-end mt-4">
            <div class="col-md-8">
                <form method="GET" action="{{ route('accountants.index') }}" class="form-inline">
                    <div class="form-group mr-2">
                        <input type="text" class="form-control" name="search" placeholder="Search by by name or email" value="{{ request('search') }}">
                    </div>
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAccountantModal">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>
        <!-- Accountants Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Address</th>
                    <th scope="col">Organizations</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accountants as $accountant)
                    <tr>
                        <th scope="row">{{ $accountant->id }}</th>
                        <td>{{ $accountant->name }}</td>
                        <td>{{ $accountant->phone }}</td>
                        <td>{{ $accountant->email }}</td>
                        <td>{{ $accountant->adresse }}</td>
                        <td>
                            @foreach($accountant->organizations as $organization)
                                {{ $organization->name }}<br>
                            @endforeach
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editAccountantModal{{ $accountant->id }}"><i class="fas fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $accountant->id }}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $accountants->links() }}
        </div>

        <!-- Add Accountant Modal -->
        <div class="modal fade" id="addAccountantModal" tabindex="-1" role="dialog" aria-labelledby="addAccountantModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAccountantModalLabel">Add Accountant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add Accountant Form -->
                        <form action="{{ route('accountants.store') }}" method="POST" id="addAccountantForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="adresse">Address:</label>
                                <input type="text" class="form-control" id="adresse" name="adresse">
                            </div>
                            <div class="form-group">
                                <label for="organizations">Organizations:</label>
                                <select multiple class="form-control" id="organizations" name="organizations[]">
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="validateAddAccountantForm()">Add Accountant</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Accountant Modals -->
        @foreach($accountants as $accountant)
            <div class="modal fade" id="editAccountantModal{{ $accountant->id }}" tabindex="-1" role="dialog" aria-labelledby="editAccountantModalLabel{{ $accountant->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAccountantModalLabel{{ $accountant->id }}">Edit Accountant</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Edit Accountant Form -->
                            <form action="{{ route('accountants.update', $accountant->id) }}" method="POST" id="editAccountantForm{{ $accountant->id }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name{{ $accountant->id }}">Name:</label>
                                    <input type="text" class="form-control" id="name{{ $accountant->id }}" name="name" value="{{ $accountant->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="phone{{ $accountant->id }}">Phone:</label>
                                    <input type="text" class="form-control" id="phone{{ $accountant->id }}" name="phone" value="{{ $accountant->phone }}">
                                </div>
                                <div class="form-group">
                                    <label for="email{{ $accountant->id }}">Email:</label>
                                    <input type="email" class="form-control" id="email{{ $accountant->id }}" name="email" value="{{ $accountant->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="adresse{{ $accountant->id }}">Address:</label>
                                    <input type="text" class="form-control" id="adresse{{ $accountant->id }}" name="adresse" value="{{ $accountant->adresse }}">
                                </div>
                                <div class="form-group">
                                    <label for="organizations{{ $accountant->id }}">Organizations:</label>
                                    <select multiple class="form-control" id="organizations{{ $accountant->id }}" name="organizations[]">
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization->id }}" {{ in_array($organization->id, $accountant->organizations->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $organization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="validateEditAccountantForm({{ $accountant->id }})">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this accountant?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form id="deleteForm" action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if (Session::has('message'))
        <script>
            toastr.success("{{ Session::get('message') }}");
        </script>
    @endif
    <script>
        function validateAddAccountantForm() {
            var name = document.getElementById('name').value;
            var phone = document.getElementById('phone').value;
            var email = document.getElementById('email').value;
            var adresse = document.getElementById('adresse').value;
            var organizations = document.getElementById('organizations').selectedOptions;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (name === '' || phone === '' || email === '' || adresse === '' || organizations.length === 0) {
                toastr.info('Please fill in all fields.');
                return false;
            }

            if (isNaN(phone)) {
                toastr.info('Phone must be a number.');
                return false;
            }
            if (!emailRegex.test(email)) {
                toastr.info('Please enter a valid email address.');
                return false;
            }
            document.getElementById('addAccountantForm').submit();
        }

        function validateEditAccountantForm(accountantId) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var name = document.getElementById('name' + accountantId).value;
            var phone = document.getElementById('phone' + accountantId).value;
            var email = document.getElementById('email' + accountantId).value;
            var adresse = document.getElementById('adresse' + accountantId).value;
            var organizations = document.getElementById('organizations' + accountantId).selectedOptions;

            if (name === '' || phone === '' || email === '' || adresse === '' || organizations.length === 0) {
                toastr.info('Please fill in all fields.');
                return false;
            }
            if (isNaN(phone)) {
                toastr.info('Phone must be a number.');
                return false;
            }
            if (!emailRegex.test(email)) {
                toastr.info('Please enter a valid email address.');
                return false;
            }

            document.getElementById('editAccountantForm' + accountantId).submit();
        }

        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var accountantId = $(this).data('id');
                $('#deleteForm').attr('action', '{{ route("accountants.destroy", ":id") }}'.replace(':id', accountantId));
                $('#deleteConfirmationModal').modal('show');
            });
        });
    </script>
@endsection
