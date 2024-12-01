@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $pageTitle }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <!-- Card 1: Laporan Berdasarkan Tanggal -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Laporan Berdasarkan Tanggal</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporanpenjualans.generate') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Generate PDF</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card 2: Laporan Bulanan -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Laporan Bulanan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporanpenjualans.generateMonthly') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-control" name="month" required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="number" class="form-control" name="year" min="2000" max="2100" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Generate PDF</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
