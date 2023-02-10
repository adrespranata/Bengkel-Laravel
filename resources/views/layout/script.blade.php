@extends('layout.main')
@extends('layout.menu')

@section('script')
<!-- Sweet-Alert  -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.26/dist/sweetalert2.min.js"></script>
<!-- jQuery  -->
<script src="{{ URL::to('/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::to('/assets/js/metismenu.min.js') }}"></script>
<script src="{{ URL::to('/assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ URL::to('/assets/js/waves.min.js') }}"></script>
<script src="{{ URL::to('/assets/js/toast.js') }}"></script>

<!-- App js -->
<script src="{{ URL::to('assets/js/app.js') }}"></script>

<!-- Chart js -->

<script src="{{ URL::to('/assets/js/Chart.js') }}"></script>
<script src="{{ URL::to('/assets/js/Chart.min.js') }}"></script>
<script src="{{ URL::to('/assets/js/Chart.bundle.js') }}"></script>
<script src="{{ URL::to('/assets/js/Chart.bundle.min.js') }}"></script>

<!-- Required datatable js -->
<script src="{{ URL::to('/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ URL::to('/assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<!-- Autonumeric -->
<script src="{{ URL::to('/assets/plugins/autoNumeric.js') }}"></script>

<!-- Buttons examples -->
<script src="{{ URL::to('/assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
<!--Summernote js-->
<script src="{{ URL::to('/assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- Datatable init js -->
<script src="{{ URL::to('/assets/pages/datatables.init.js') }}"></script>
<script>
     $('#logout').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah anda yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('login/logout') }}",
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Anda berhasil logout!",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1250
                        }).then(function() {
                            window.location = "{{ url('/login') }}";
                        });
                    }
                });
            }
        })
    })
</script>
@endSection()