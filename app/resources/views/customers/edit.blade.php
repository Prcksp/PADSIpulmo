@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- The form uses the PUT method for update -->
                            <form method="POST" action="{{ route('customers.update', $customer->id_customer) }}">
                                @csrf
                                @method('PUT') <!-- This is needed for the update request -->

                                <div class="form-group">
                                    <label for="nama_customer">Nama Customer</label>
                                    <input type="text" name="nama_customer" class="form-control" value="{{ old('nama_customer', $customer->nama_customer) }}">
                                    @error('nama_customer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alamat_customer">Alamat Customer</label>
                                    <input type="text" name="alamat_customer" class="form-control" value="{{ old('alamat_customer', $customer->alamat_customer) }}">
                                    @error('alamat_customer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="no_telepon_customer">No. Telepon</label>
                                    <input type="text" name="no_telepon_customer" class="form-control" value="{{ old('no_telepon_customer', $customer->no_telepon_customer) }}">
                                    @error('no_telepon_customer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_lahir_customer">Tanggal Lahir</label>
                                    <input 
                                        type="date" 
                                        name="tanggal_lahir_customer" 
                                        class="form-control" 
                                        value="{{ old('tanggal_lahir_customer', optional($customer->tanggal_lahir_customer)->format('Y-m-d')) }}"
                                    >
                                    @error('tanggal_lahir_customer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="form-group">
                                    <label for="email_customer">Email</label>
                                    <input type="email" name="email_customer" class="form-control" value="{{ old('email_customer', $customer->email_customer) }}">
                                    @error('email_customer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- <div class="form-group">
                                    <label for="jumlah_poin">Jumlah Poin</label>
                                    <input type="number" name="jumlah_poin" class="form-control" value="{{ old('jumlah_poin', $customer->jumlah_poin) }}" step="1" min="0" disabled>
                                    @error('jumlah_poin')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div> -->

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update Customer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
