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
                        <div class="card-header">
                            <h4>Edit RPPH</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ url('rpph/'.$rpph->id) }}">
                                @csrf
                                @method('PUT')

                                @foreach ($fields as $field => $label)
                                    <div class="form-group">
                                        <label>{{ $label }}</label>
                                        @if (in_array($field,  ['tujuan_kegiatan', 'capaian_pembelajaran', 'alat_dan_bahan', 'kegiatan','assemen_atau_penilaian']))
                                            <textarea name="{{ $field }}" class="form-control">{{ old($field, $rpph->$field) }}</textarea>
                                        @else
                                            <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field, $rpph->$field) }}">
                                        @endif
                                        @error($field)
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
