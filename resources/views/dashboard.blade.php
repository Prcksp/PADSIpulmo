@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">

@endsection
@section('content')
<div class="container">
   
</div>

@endsection
@section('script')
<!-- Include DataTables JS -->
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#kalenderPendidikanTable').DataTable();
        $('#implementasiTable').DataTable();
        $('#evaluasiResultsTable').DataTable();
        $('#matpelAktifTable').DataTable();
    });
</script>
@endsection
