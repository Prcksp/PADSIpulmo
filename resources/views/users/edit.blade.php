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
                            <form method="POST" action="{{ url('users/'.$user->id) }}">
                                @csrf
                                @method('PUT')

                                @foreach ($fields as $field => $label)
                                    <div class="form-group">
                                        <label>{{ $label }}</label>
                                        @if ($field === 'role')
                                            <select name="{{ $field }}" class="form-control">
                                                @foreach ($roleOptions as $value => $text)
                                                    <option value="{{ $value }}" {{ old($field, $user->$field) === $value ? 'selected' : '' }}>
                                                        {{ $text }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($field === 'password')
                                            <input type="password" name="{{ $field }}" class="form-control" required>
                                        @else
                                            <input type="{{ $field === 'textarea' ? 'textarea' : 'text' }}" name="{{ $field }}" class="form-control" value="{{ old($field, $user->$field) }}">
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
