@extends('layout')

@section('header')
    Countries
@endsection

@section('content')
    <div class="dashboard-content">
        <h2> Countries Management</h2>
        <p>Manage Countries in the system.</p>
                    <!-- Add Country Button -->
        <div class="row mb-3 align-items-end mt-4">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCountryModal">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>
            <!-- Countries Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Country</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $country)
                        <tr>
                            <th scope="row">{{ $country->id }}</th>
                            <td>{{ $country->country }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editCountryModal{{ $country->id }}"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $country->id }}"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $countries->links('pagination.custom') }}
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
                            Are you sure you want to delete this country?
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
    
            <!-- Add Country Modal -->
            <div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog" aria-labelledby="addCountryModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCountryModalLabel">Add Country</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Add Country Form -->
                            <form id="addCountryForm" action="{{ route('country.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="country">Country Name:</label>
                                    <input type="text" class="form-control" id="country" name="country">
                                </div>
                                <button type="button" onclick="validateAddCountryForm()" class="btn btn-primary">Add Country</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Edit Country Modals -->
            @foreach($countries as $country)
                <div class="modal fade" id="editCountryModal{{ $country->id }}" tabindex="-1" role="dialog" aria-labelledby="editCountryModalLabel{{ $country->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCountryModalLabel{{ $country->id }}">Edit Country</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Edit Country Form -->
                                <form id="editCountryForm{{ $country->id }}" action="{{ route('country.update', $country->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="country">Country Name:</label>
                                        <input type="text" class="form-control" id="country{{ $country->id }}" name="country" value="{{ $country->country }}">
                                    </div>
                                    <button type="button" onclick="validateEditCountryForm({{ $country->id }})" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
        function validateAddCountryForm() {
            var country = document.getElementById('country').value;

            if (country === '') {
                toastr.info('Please fill in all fields.');
                return false;
            }

            document.getElementById('addCountryForm').submit();
        }

        function validateEditCountryForm(countryId) {
            var country = document.getElementById('country' + countryId).value;

            if (country === '') {
                toastr.info('Please fill in all fields.');
                return false;
            }

            document.getElementById('editCountryForm' + countryId).submit();
        }

        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var countryId = $(this).data('id');
                $('#deleteForm').attr('action', '{{ route("country.delete", ["id" => ":id"]) }}'.replace(':id', countryId));
                $('#deleteConfirmationModal').modal('show');
            });
        });
    </script>
@endsection
