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

@if (session('warning'))
    <script>
        Swal.fire({
            title: 'Warning!',
            text: "{{ session('warning') }}",
            icon: 'warning',
            confirmButtonText: 'Okay',
            timer: 3000, // auto-close after 3 seconds
            toast: true, // display as toast notification
            position: 'top-end' // display at top-end of the screen
        });
    </script>
@endif

@if (session('info'))
    <script>
        Swal.fire({
            title: 'Info!',
            text: "{{ session('info') }}",
            icon: 'info',
            confirmButtonText: 'Okay',
            timer: 3000, // auto-close after 3 seconds
            toast: true, // display as toast notification
            position: 'top-end' // display at top-end of the screen
        });
    </script>
@endif



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script>
    function confirmDelete(event,formId) {
        event.preventDefault(); // Prevent the form from being submitted immediately

        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                document.getElementById(formId).submit();
            }
        });
    }
</script> --}}
<script>
    function confirmDelete(event, formId) {
        event.preventDefault(); // Prevent the form from being submitted immediately

        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                document.getElementById(formId).submit();
            }
        });
    }
</script>
