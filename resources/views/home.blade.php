@extends('layouts.admin')

@section('content')
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Total Sheets in Inventory</div>
                <div class="card-body">
                    <h2>{{ $totalSheets }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Low Stock Alerts</div>
                <div class="card-body">
                    @if ($lowStockSheets->isEmpty())
                        <p>No low stock items.</p>
                    @else
                        <ul>
                            @foreach ($lowStockSheets as $sheet)
                                <li class="low-stock-alert">
                                    {{ $sheet->type->name }} - {{ $sheet->size->size }}mm - {{ $sheet->color->color }}
                                    (Count: {{ $sheet->total_count }})
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection