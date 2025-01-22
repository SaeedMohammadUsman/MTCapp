@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <h1>Profile</h1>
@stop

@section('content')
    <div class="card shadow-lg">
        <!-- Cover Photo Section -->


        <div class="card-body pt-5 mt-3">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-4">
                    <div class="text-left mb-4">
                        <h3 class="font-weight-bold mb-1">{{ auth()->user()->name }}</h3>
                        <span class="badge badge-primary">
                            {{ auth()->user()->getRoleNames()->first() ?? 'Employee' }}
                        </span>
                    </div>

                    <!-- Quick Stats -->

                    <div class="mb-4">
                        <img src="{{ asset('storage/profile.JPG') }}" alt="Profile Image" class="img-fluid rounded"
                            style="width: 100%; height: auto;">
                    </div>

                </div>

                <!-- Right Column -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">Profile Information</h5>
                                {{-- <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button> --}}
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Email Address</label>
                                    <p class="mb-0">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Phone Number</label>
                                    <p class="mb-0">{{ auth()->user()->phone ?? '+93744781903' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Department</label>
                                    <p class="mb-0">{{ auth()->user()->department ?? 'Not set' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Location</label>
                                    <p class="mb-0">{{ auth()->user()->location ?? 'Paghman,Kabul' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#changePasswordModal">
                            <i class="fas fa-key mr-2"></i>Change Password
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add required CSS -->
    <style>
        .profile-cover {
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .stats-box {
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 0.5rem;
        }

        .shadow-lg {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }
    </style>





    <!-- Change Password Modal -->
    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog"
        aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Your Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body">
                        <!-- Current Password Field -->
                        <div class="form-group">
                            <label for="current-password">Current Password</label>
                            <input type="password" class="form-control"
                                id="current-password" name="current_password" required>
                        </div>

                        <!-- New Password Field -->
                        <div class="form-group">
                            <label for="new-password">New Password</label>
                            <input type="password" class="form-control"
                                id="new-password" name="password" required>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label for="confirm-password">Confirm New Password</label>
                            <input type="password" class="form-control"
                                id="confirm-password" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




@stop




@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AdminLTE specific JS scripts to handle modal and other functionalities -->
    <script>
        $(document).ready(function() {
            // Custom JS, if needed for further enhancements
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Okay',
                timer: 3000, // auto-close after 3 seconds
                toast: true, // display as toast notification
                position: 'top-end' // display at top-end of the screen
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Okay',
                timer: 3000, // auto-close after 3 seconds
                toast: true, // display as toast notification
                position: 'top-end' // display at top-end of the screen
            });
        </script>
    @endif
@stop
