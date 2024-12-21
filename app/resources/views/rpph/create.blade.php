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
                        <form method="GET" action="{{ url('rpph/create') }}">
                            <!-- Semester Dropdown -->
                            <div class="form-group">
                                <label>Semester</label>
                                <select id="semester" name="semester" class="form-control" onchange="this.form.submit()">
                                    <option value="">Select Semester</option>
                                    <option value="1" {{ old('semester', $selectedSemester) == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('semester', $selectedSemester) == '2' ? 'selected' : '' }}>2</option>
                                </select>
                                @error('semester')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>

                        <form method="POST" action="{{ url('rpph') }}">
                            @csrf

                            <!-- Theme Dropdown -->
                            <div class="form-group">
                                <label>Theme</label>
                                <select id="theme" name="theme" class="form-control">
                                    <option value="">Select Theme</option>
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme->id }}" {{ old('theme') == $theme->id ? 'selected' : '' }}>
                                            {{ $theme->theme }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('theme')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            @foreach ($fields as $field => $label)
                                <div class="form-group">
                                    <label>{{ $label }}</label>
                                    @if (in_array($field, ['tujuan_kegiatan', 'capaian_pembelajaran', 'alat_dan_bahan', 'kegiatan', 'assemen_atau_penilaian']))
                                        <textarea name="{{ $field }}" class="form-control">{{ old($field) }}</textarea>
                                    @else
                                        <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field) }}">
                                    @endif
                                    @error($field)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
