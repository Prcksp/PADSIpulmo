@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <style>
        .password-field-container {
            position: relative;
        }
        .password-field-container input {
            padding-right: 30px;
        }
        .password-field-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif
        @if(session('message'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data RPPH</h4>
                            <!-- Always show the Add RPPH button -->
                            <div class="card-header-action">
                                <a href="{{ url('rpph/create') }}" class="btn btn-icon btn-primary">
                                    <i class="fas fa-plus"></i> Add RPPH
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped text-center" id="datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cetak</th> <!-- Add Cetak column -->
                                            <th class="text-center">No.</th>

                                            @foreach ($fields as $field => $label)
                                                <th>{{ $label }}</th>
                                            @endforeach
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($rpph as $item)
                                            <tr>
                                                <td>
                                                    <a href="{{ url('rpph/'.$item->id.'/cetak') }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </td>
                                                <td class="text-center">{{ $no++ }}</td>

                                                @foreach ($fields as $field => $label)
                                                    <td>{{ Str::limit($item->$field, 10, '...') }}</td>
                                                @endforeach
                                                <td>
                                                    <!-- Always show the Edit button -->
                                                    <a href="{{ url('rpph/'.$item->id.'/edit') }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <!-- Always show the Delete button -->
                                                    <form action="{{ url('rpph/'.$item->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- JS Libraries -->
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
@endsection
