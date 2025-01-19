@extends('adminlte::page')

@section('title', 'Manage Users')

@section('content_header')
    <h1>Manage Users</h1>
@stop

@section('content')
    {{-- Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('users.index') }}" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email"
                        value="{{ request('search') }}">
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="role" class="form-control">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="status" class="form-control">
                        <option value="">All Users</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm ml-2">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Add User Button --}}
    <div class="card mt-3">
        <div class="card-body">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userModal" id="createUserBtn">
                Add User
            </button>
        </div>
    </div>



    {{-- Users Table --}}
    <table class="table table-sm table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                {{-- <th>Password</th> --}}
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                    {{-- <td>{{ $user->password }}</td> --}}
                    {{-- <td>
                        <button class="btn btn-sm btn-info editUser Btn" data-id="{{ $user->id }}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteUser Btn" data-id="{{ $user->id }}">Delete</button>
                    </td> --}}
                    <td>
                        @if ($user->trashed())
                        <button class="btn btn-success btn-sm restoreUser" data-id="{{ $user->id }}">Restore</button>
                    @else
                            {{-- Edit and Delete buttons for active users --}}
                            <button class="btn btn-sm btn-info editUser Btn" data-id="{{ $user->id }}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteUser Btn" data-id="{{ $user->id }}">Delete</button>
                        @endif
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $users->links() }}

    {{-- User Modal --}}
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="userForm">
                @csrf
                <input type="hidden" id="userId" name="user_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Add/Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role_id" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // CSRF Token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Create or Edit User
        $('#userForm').on('submit', function (e) {
            e.preventDefault();
            const url = $('#userId').val() ? `/users/${$('#userId').val()}` : `/users`;
            const method = $('#userId').val() ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function (response) {
                    $('#userModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors).flat().join('\n');

                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
            });
        });

        // Edit User
        $('.editUser').click(function () {
            const id = $(this).data('id');
            $.get(`/users/${id}/edit`, function (user) {
                $('#userId').val(user.id);
                $('#name').val(user.name);
                $('#email').val(user.email);
                $('#role').val(user.roles[0]?.id);
                $('#userModal').modal('show');
            });
        });

        // Delete User
        $('.deleteUser').click(function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/users/${id}`,
                        method: 'DELETE',
                        success: function (response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        },
                    });
                }
            });
        });
        
// Restore User
// Replace or update the restore user script
$('.restoreUser').click(function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Restore User',
        text: "Are you sure you want to restore this user?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/users/${id}/restore`,
                type: 'POST',
                success: function(response) {
                    Swal.fire(
                        'Restored!',
                        response.success,
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong!',
                        'error'
                    );
                }
            });
        }
    });
});
    </script>
  
@stop