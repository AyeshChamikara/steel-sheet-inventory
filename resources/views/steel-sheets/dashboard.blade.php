@extends('layouts.admin')

@section('content')
    <h1>Steel Sheets Dashboard</h1>

    <!-- Filter Form -->
    <form action="{{ route('steel-sheets.dashboard') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="type_id" class="form-label">Filter by Type</label>
                <select name="type_id" id="type_id" class="form-select">
                    <option value="">All Types</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="size_id" class="form-label">Filter by Size</label>
                <select name="size_id" id="size_id" class="form-select">
                    <option value="">All Sizes</option> <!-- Sends an empty value for "All Sizes" -->
                    @foreach ($sizes as $size)
                        <option value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'selected' : '' }}>
                            {{ $size->size }} mm
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </form>

    <!-- Display Dashboards for Each Type -->
    @if ($steelSheets->isEmpty())
        <p>No steel sheets available in the inventory.</p>
    @else
        @foreach ($steelSheets as $typeId => $sheets)
            <?php $type = \App\Models\SteelSheetType::find($typeId); ?>
            @if ($type)
                <div class="card mb-4">
                    <div class="card-header">{{ $type->name }} Dashboard</div>
                    <div class="card-body">
                        <!-- Group Sheets by Size -->
                        @foreach ($sheets->groupBy('size_id') as $sizeId => $sizeSheets)
                            <?php $size = \App\Models\SteelSheetSize::find($sizeId); ?>
                            @if ($size)
                                <div class="card mb-3">
                                    <div class="card-header">{{ $size->size }} mm</div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Color</th>
                                                <th>Total Count</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sizeSheets as $sheet)
                                                <tr>
                                                    <td>{{ $sheet->color->color }}</td>
                                                    <td>{{ $sheet->total_count }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success" data-action="add" data-id="{{ $sheet->id }}" data-toggle="modal" data-target="#updateQuantityModal">Add</button>
                                                        <button class="btn btn-sm btn-danger" data-action="subtract" data-id="{{ $sheet->id }}" data-toggle="modal" data-target="#updateQuantityModal">Subtract</button>
                                                    </td>
                                                    <td>
                                                        <!-- Delete Button -->
                                                        <form action="{{ route('steel-sheets.destroy', $sheet->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this steel sheet?')">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
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
                                <option value="">Select or Add New</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary mt-2" id="addNewTypeBtn">Add New</button>
                            <input type="text" id="new_type" class="form-control mt-2 d-none" placeholder="Enter new type">
                        </div>

                        <!-- Size Field -->
                        <div class="mb-3">
                            <label for="size_id" class="form-label">Size</label>
                            <select name="size_id" id="size_id" class="form-select" required>
                                <option value="">Select or Add New</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->size }} mm</option>
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
                                @foreach ($colors as $color) <!-- Ensure $colors is passed from the controller -->
                                    <option value="{{ $color->id }}">{{ $color->color }}</option>
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

    <!-- JavaScript for Modal -->
    <script>
        $(document).ready(function () {
    // Show the modal when "Add" or "Subtract" buttons are clicked
        $('.btn').on('click', function () {
            let action = $(this).data('action'); // 'add' or 'subtract'
            let sheetId = $(this).data('id'); // ID of the steel sheet

            if (!action || !sheetId) {
                alert('Invalid button configuration.');
                return;
            }

            // Set the hidden fields in the modal
            $('#sheetId').val(sheetId);
            $('#actionType').val(action);

            // Show the modal
            $('#updateQuantityModal').modal('show');
        });

        // Handle form submission for the modal
        $('#updateQuantityForm').on('submit', function (e) {
            e.preventDefault();

            let sheetId = $('#sheetId').val();
            let action = $('#actionType').val();
            let quantity = $('#quantity').val();

            if (!sheetId || !action || !quantity) {
                alert('Please provide all required inputs.');
                return;
            }

            $.ajax({
                url: "/transactions/" + action + "/" + sheetId,
                method: 'POST',
                data: { quantity: quantity, _token: '{{ csrf_token() }}' }, // Include CSRF token
                success: function (response) {
                    if (response.message) {
                        alert(response.message); // Show success message
                    } else {
                        alert('Unknown response from server.');
                    }

                    $('#updateQuantityModal').modal('hide'); // Hide the modal
                    location.reload(); // Refresh the page to reflect changes
                },
                error: function (error) {
                    console.error(error.responseJSON); // Log the error for debugging

                    if (error.responseJSON && error.responseJSON.error) {
                        alert(error.responseJSON.error); // Show error message
                    } else if (error.responseJSON && error.responseJSON.details) {
                        alert('Error details: ' + error.responseJSON.details); // Show detailed error
                    } else {
                        alert('An error occurred while updating the inventory.');
                    }
                }
            });
        });
    });
    </script>
@endsection