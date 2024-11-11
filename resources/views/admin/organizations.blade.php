@extends('layout')

@section('header')
    Organizations
@endsection

@section('content')
    <div class="dashboard-content">
    <h2>Organizations Management</h2>
    <p>Manage organizations in the system.</p>
    
    <!-- Search Form and Add Organization Button -->
    <div class="row mb-3 align-items-end mt-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('admin.organizations') }}" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" class="form-control" name="search" placeholder="Search by name" value="{{ request('search') }}">
                </div>
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
        <div class="col-md-4 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOrganizationModal">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
    </div>
        
        <!-- Organizations Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Country</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($organizations as $organization)
                    <tr>
                        <th scope="row">{{ $organization->id }}</th>
                        <td>{{ $organization->name }}</td>
                        <td>{{ $organization->adresse }}</td>
                        <td>{{ $organization->country->country }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editOrganizationModal{{ $organization->id }}"><i class="fas fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $organization->id }}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $organizations->links('pagination.custom') }}
        </div>

        <!-- Add Organization Modal -->
        <div class="modal fade" id="addOrganizationModal" tabindex="-1" role="dialog" aria-labelledby="addOrganizationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrganizationModalLabel">Add Organization</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add Organization Form -->
                        <form id="addOrganizationForm" action="{{ route('organization.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Organization Name:</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="adresse">Organization Address:</label>
                                <input type="text" class="form-control" id="adresse" name="adresse">
                            </div>
                            <div class="form-group">
                                <label for="country_id">Select Country:</label>
                                <select class="form-control" id="country_id" name="country_id">
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" onclick="validateAddOrganizationForm()" class="btn btn-primary">Add Organization</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Organization Modals -->
        @foreach($organizations as $organization)
            <div class="modal fade" id="editOrganizationModal{{ $organization->id }}" tabindex="-1" role="dialog" aria-labelledby="editOrganizationModalLabel{{ $organization->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editOrganizationModalLabel{{ $organization->id }}">Edit Organization</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Edit Organization Form -->
                            <form id="editOrganizationForm{{ $organization->id }}" action="{{ route('organization.update', $organization->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Organization Name:</label>
                                    <input type="text" class="form-control" id="name{{ $organization->id }}" name="name" value="{{ $organization->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="adresse">Organization Address:</label>
                                    <input type="text" class="form-control" id="adresse{{ $organization->id }}" name="adresse" value="{{ $organization->adresse }}">
                                </div>
                                <div class="form-group">
                                    <label for="country_id">Select Country:</label>
                                    <select class="form-control" id="country_id{{ $organization->id }}" name="country_id">
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ $organization->country_id == $country->id ? 'selected' : '' }}>{{ $country->country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" onclick="validateEditOrganizationForm({{ $organization->id }})" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

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
                    Are you sure you want to delete this Organization?
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
        function validateAddOrganizationForm() {
            var name = document.getElementById('name').value;
            var adresse = document.getElementById('adresse').value;
            var country_id = document.getElementById('country_id').value;

            if (name === '' || adresse === '' || country_id === '') {
                toastr.info('Please fill in all fields.');
                return false;
            }

            document.getElementById('addOrganizationForm').submit();
        }

        function validateEditOrganizationForm(orgId) {
            var name = document.getElementById('name' + orgId).value;
            var adresse = document.getElementById('adresse' + orgId).value;
            var country_id = document.getElementById('country_id' + orgId).value;

            if (name === '' || adresse === '' || country_id === '') {
                toastr.info('Please fill in all fields.');
                return false;
            }

            document.getElementById('editOrganizationForm' + orgId).submit();
        }

        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var orgId = $(this).data('id');
                $('#deleteForm').attr('action', '{{ route("organization.delete", ["id" => ":id"]) }}'.replace(':id', orgId));
                $('#deleteConfirmationModal').modal('show');
            });
        });
    </script>
@endsection
