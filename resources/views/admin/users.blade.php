@extends('layout')

@section('header')
Financial managers
@endsection

@section('content')
<div class="dashboard-content">
    <h2>Financial managers Management</h2>
    <p>Manage financial managers in the system.</p>

        <!-- Search Form and Add Fman Button -->
        <div class="row mb-3 align-items-end mt-4">
            <div class="col-md-8">
                <form method="GET" action="{{ route('admin.users') }}" class="form-inline">
                    <div class="form-group mr-2">
                        <input type="text" class="form-control" name="search" placeholder="Search by Tax Id or email" value="{{ request('search') }}">
                    </div>
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>
    <!-- Users Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Tax Identification</th>
                <th scope="col">Phone</th>
                <th scope="col">Address</th>
                <th scope="col">Email</th>
                <th scope="col">Organization</th> <!-- Add Organization column -->
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->taxidentification }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->adresse }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->organization->name }}</td> <!-- Display Organization name -->
                    <td>
                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editUserModal{{ $user->id }}"><i class="fas fa-edit"></i></a>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}"><i class="fas fa-trash-alt"></i></button>
                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#changePasswordModal{{ $user->id }}"><i class="fas fa-lock"></i></button>
                    </td>                    
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $users->links('pagination.custom') }}
    </div>


        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add User Form -->
                        <form action="{{ route('user.store') }}" method="POST" id="addUserForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="adresse">Address:</label>
                                <input type="text" class="form-control" id="adresse" name="adresse">
                            </div>
                            <div class="form-group">
                                <label for="taxidentification">Tax Identification:</label>
                                <input type="text" class="form-control" id="taxidentification" name="taxidentification">
                            </div>
                            <div class="form-group">
                                <label for="organization_id">Organization:</label>
                                <select class="form-control" id="organization_id" name="organization_id">
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="validateAddUserForm()">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modals -->
        @foreach($users as $user)
        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Edit User Form -->
                            <form action="{{ route('user.update', $user->id) }}" method="POST" id="editUserForm{{ $user->id }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name{{ $user->id }}">Name:</label>
                                    <input type="text" class="form-control" id="name{{ $user->id }}" name="name" value="{{ $user->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email{{ $user->id }}">Email:</label>
                                    <input type="email" class="form-control" id="email{{ $user->id }}" name="email" value="{{ $user->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="phone{{ $user->id }}">Phone:</label>
                                    <input type="text" class="form-control" id="phone{{ $user->id }}" name="phone" value="{{ $user->phone }}">
                                </div>
                                <div class="form-group">
                                    <label for="adresse{{ $user->id }}">Address:</label>
                                    <input type="text" class="form-control" id="adresse{{ $user->id }}" name="adresse" value="{{ $user->adresse }}">
                                </div>
                                <div class="form-group">
                                    <label for="taxidentification{{ $user->id }}">Tax Identification:</label>
                                    <input type="text" class="form-control" id="taxidentification{{ $user->id }}" name="taxidentification" value="{{ $user->taxidentification }}">
                                </div>
                                <div class="form-group">
                                    <label for="organization_id{{ $user->id }}">Organization:</label>
                                    <select class="form-control" id="organization_id{{ $user->id }}" name="organization_id">
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization->id }}" {{ $user->organization_id == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="validateEditUserForm({{ $user->id }})">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!--change mdp -->
        @foreach($users as $user)
            <div class="modal fade" id="changePasswordModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel{{ $user->id }}">Change Password for {{ $user->taxidentification }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('user.change-password', $user->id) }}" method="POST" id="changePasswordForm{{ $user->id }}">
                                @csrf
                                <div class="form-group">
                                    <label for="password">New Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="validateChangePasswordForm({{ $user->id }})">Change Password</button>
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
                        Are you sure you want to delete this User?
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
    toastr.success("{{ Session::get('message')}}");
    </script>
    @endif
    <script>
        function validateAddUserForm() {
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var phone = document.getElementById('phone').value;
            var adresse = document.getElementById('adresse').value;
            var taxidentification = document.getElementById('taxidentification').value;
            var organization_id = document.getElementById('organization_id').value;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (name === '' || email === '' || password === '' || phone === '' || adresse === '' || taxidentification === '' || organization_id === '') {
                toastr.info('Please fill in all fields.');
                return false;
            }

            if (isNaN(phone)) {
                toastr.info('Phone must be a number.');
                return false;
            }
            if (password.length < 6) {
                toastr.info('Password must be at least 6 characters long.');
                return false;
            }
            if (!emailRegex.test(email)) {
                toastr.info('Please enter a valid email address.');
                return false;
            }
            document.getElementById('addUserForm').submit();
        }
        function validateChangePasswordForm(userId) {
    var password = document.querySelector('#changePasswordForm' + userId + ' input[name="password"]').value;
    var confirmPassword = document.querySelector('#changePasswordForm' + userId + ' input[name="confirm_password"]').value;

    if (password === '' || confirmPassword === '') {
        toastr.info('Please fill in all fields.');
        return false;
    }

    if (password !== confirmPassword) {
        toastr.info('Passwords do not match.');
        return false;
    }

    document.getElementById('changePasswordForm' + userId).submit();
}


        function validateEditUserForm(userId) {
            var name = document.getElementById('name' + userId).value;
            var email = document.getElementById('email' + userId).value;
            var phone = document.getElementById('phone' + userId).value;
            var adresse = document.getElementById('adresse' + userId).value;
            var taxidentification = document.getElementById('taxidentification' + userId).value;
            var organization_id = document.getElementById('organization_id' + userId).value;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (name === '' || email === '' || phone === '' || adresse === '' || taxidentification === '' || organization_id === '') {
                toastr.info('Please fill in all fields.');
                return false;
            }
            if (!emailRegex.test(email)) {
                toastr.info('Please enter a valid email address.');
                return false;
            }
            document.getElementById('editUserForm' + userId).submit();
        }

        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var userId = $(this).data('id');
                $('#deleteForm').attr('action', '{{ route("user.delete", ["id" => ":id"]) }}'.replace(':id', userId));
                $('#deleteConfirmationModal').modal('show');
            });
        });
        
    </script>
@endsection
