@extends('layouts.admin')

@section('content')
    <h1>{{ $type->name }} Dashboard</h1>

    <!-- Add Steel Sheet Button -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSteelSheetModal">
        Add Steel Sheet
    </button>

    <!-- Individual Size Sections -->
    @if ($steelSheetsBySize->isEmpty())
        <p>No steel sheets available for {{ $type->name }}.</p>
    @else
        @foreach ($steelSheetsBySize as $sizeId => $sheets)
            <?php $size = \App\Models\SteelSheetSize::find($sizeId); ?>
            <div class="card mb-4">
                <div class="card-header">{{ $size->size }} mm</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Total Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sheets as $sheet)
                                <tr>
                                    <td>{{ $sheet->color->color }}</td>
                                    <td>{{ $sheet->total_count }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" data-action="add" data-id="{{ $sheet->id }}">Add</button>
                                        <button class="btn btn-sm btn-danger" data-action="subtract" data-id="{{ $sheet->id }}">Subtract</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Add Steel Sheet Modal -->
    <div class="modal fade" id="addSteelSheetModal" tabindex="-1" aria-labelledby="addSteelSheetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSteelSheetModalLabel">Add Steel Sheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('steel-sheets.store') }}" method="POST" id="addSteelSheetForm">
                    @csrf
                    <div class="modal-body">
                        <!-- Type Field -->
                        <div class="mb-3">
                            <label for="type_id" class="form-label">Type</label>
                            <select name="type_id" id="type_id" class="form-select" required>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}" {{ $t->id == $type->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Size Field -->
                        <div class="mb-3">
                            <label for="size_id" class="form-label">Size</label>
                            <select name="size_id" id="size_id" class="form-select" required>
                                <option value="">Select or Add New</option>
                                @foreach ($sizes as $s)
                                    <option value="{{ $s->id }}">{{ $s->size }} mm</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary mt-2" id="addNewSizeBtn">Add New</button>
                            <input type="text" id="new_size" class="form-control mt-2 d-none" placeholder="Enter new size (e.g., 0.47)">
                        </div>

                        <!-- Color Field -->
                        <div class="mb-3">
                            <label for="color_id" class="form-label">Color</label>
                            <select name="color_id" id="color_id" class="form-select" required>
                                <option value="">Select or Add New</option>
                                @foreach ($colors as $c)
                                    <option value="{{ $c->id }}">{{ $c->color }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary mt-2" id="addNewColorBtn">Add New</button>
                            <input type="text" id="new_color" class="form-control mt-2 d-none" placeholder="Enter new color">
                        </div>

                        <!-- Total Count Field -->
                        <div class="mb-3">
                            <label for="total_count" class="form-label">Total Count</label>
                            <input type="number" name="total_count" id="total_count" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Subtract Quantity Modal -->
<div class="modal fade" id="updateQuantityModal" tabindex="-1" aria-labelledby="updateQuantityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateQuantityModalLabel">Update Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateQuantityForm">
                <div class="modal-body">
                    <input type="hidden" id="sheetId" name="sheet_id">
                    <input type="hidden" id="actionType" name="action_type">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Enter Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection