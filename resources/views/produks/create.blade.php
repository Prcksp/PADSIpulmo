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
                            <form method="POST" action="{{ route('produks.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="nama_produk">Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}">
                                    @error('nama_produk')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi_produk">Deskripsi Produk</label>
                                    <textarea name="deskripsi_produk" class="form-control">{{ old('deskripsi_produk') }}</textarea>
                                    @error('deskripsi_produk')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_produk">Jumlah Produk</label>
                                    <input type="number" name="jumlah_produk" class="form-control" value="{{ old('jumlah_produk') }}">
                                    @error('jumlah_produk')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="harga_produk">Harga Produk</label>
                                    <input type="number" name="harga_produk" class="form-control" value="{{ old('harga_produk') }}" step="0.01">
                                    @error('harga_produk')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Create Produk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
