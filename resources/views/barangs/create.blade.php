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
                            <form method="POST" action="{{ route('barangs.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}">
                                    @error('nama_barang')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi_barang">Deskripsi Barang</label>
                                    <textarea name="deskripsi_barang" class="form-control">{{ old('deskripsi_barang') }}</textarea>
                                    @error('deskripsi_barang')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_barang">Jumlah Barang</label>
                                    <input type="number" name="jumlah_barang" class="form-control" value="{{ old('jumlah_barang') }}">
                                    @error('jumlah_barang')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="harga_barang">Harga Barang</label>
                                    <input type="number" name="harga_barang" class="form-control" value="{{ old('harga_barang') }}" step="0.01">
                                    @error('harga_barang')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Create Barang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
