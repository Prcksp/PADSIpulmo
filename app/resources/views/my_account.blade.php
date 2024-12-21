@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/jquery-selectric/selectric.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
        </div>

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
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="card">
                        <form method="post" action="{{ url('/my-account') }}" class="needs-validation" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Perbaharui Profil</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" value="{{ $user['user_id'] }}">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $user['name']) }}" autocomplete="name" autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user['email']) }}" autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Nomor Telepon</label>
                                    <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $user['phone_number']) }}" autocomplete="phone_number">
                                    @error('phone_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-icon icon-left btn-primary"><i class="fas fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-6">
                    <div class="card">
                        <form method="post" action="{{ url('/my-account/update-password') }}" class="needs-validation" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Ganti Kata Sandi</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" value="{{ $user['user_id'] }}">
                                {{-- <div class="form-group">
                                    <label for="current-password">Kata Sandi Lama</label>
                                    <input id="current-password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autofocus>
                                    @error('current_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="new-password" class="d-block">Kata Sandi Baru</label>
                                    <input id="new-password" type="password" class="form-control pwstrength @error('new_password') is-invalid @enderror" data-indicator="pwindicator" name="new_password">
                                    <div id="pwindicator" class="pwindicator">
                                        <div class="bar"></div>
                                        <div class="label"></div>
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="new-password-confirm" class="d-block">Konfirmasi Kata Sandi Baru</label>
                                    <input id="new-password-confirm" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation">
                                    @error('new_password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-icon icon-left btn-primary"><i class="fas fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- JS Libraries -->
    <script src="{{ asset('assets/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/js/page/auth-register.js') }}"></script>
@endsection
