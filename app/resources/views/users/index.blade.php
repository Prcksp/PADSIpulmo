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
                            <h4>Data Pengguna</h4>
                            <!-- Always show the Add User button -->
                            <div class="card-header-action">
                                <a href="{{ url('users/create') }}" class="btn btn-icon btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Pengguna
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped text-center" id="datatable">
                                    <thead>
                                        <tr>
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
                                        @foreach ($users as $user)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            @foreach ($fields as $field => $label)
                                                <td>
                                                    @if ($field == 'password')
                                                        <div class="password-field-container">
                                                            <input type="password" id="password-{{ $user->id }}" class="form-control text-center" value="********" disabled>
                                                            {{-- <i class="fas fa-eye toggle-password" data-target="password-{{ $user->id }}"></i> --}}
                                                        </div>
                                                    @else
                                                        {{ $user->$field }}
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td>
                                                <!-- Always show the Edit button -->
                                                <a href="{{ url('users/'.$user->id.'/edit') }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Always show the Delete button -->
                                                <form action="{{ url('users/'.$user->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
    <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePasswords = document.querySelectorAll('.toggle-password');

            togglePasswords.forEach(icon => {
                icon.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (input) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            this.classList.remove('fa-eye');
                            this.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            this.classList.remove('fa-eye-slash');
                            this.classList.add('fa-eye');
                        }
                    } else {
                        console.error('Input element not found:', targetId);
                    }
                });
            });
        });
    </script>
@endsection
