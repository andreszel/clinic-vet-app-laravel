@section('datatables-css')
<!-- Custom styles for this page -->
<link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('datatables-js')
<!-- Page level plugins -->
<script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
<script type="text/javascript">
    $('.change-status').click(function(event) {
        $(this).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
    });
</script>
@endsection