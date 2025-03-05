<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steel Sheet Inventory</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #0d6efd;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .low-stock-alert {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Admin Dashboard</h3>
        <a href="{{ route('home') }}">Dashboard (Home)</a>
        <a href="{{ route('steel-sheets.dashboard') }}">Steel Sheets Dashboard</a>
        <a href="#" data-bs-toggle="modal" data-bs-target="#addSteelSheetModal">Add Steel Sheet</a>
        <a href="#">Company Details</a>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

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
                        <div class="input-group">
                            <select name="type_id" id="type_id" class="form-select" required>
                                <option value="">Select or Add New</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="addNewTypeBtn">Add New</button>
                        </div>
                        <input type="text" id="new_type" class="form-control mt-2 d-none" placeholder="Enter new type">
                    </div>

                    <!-- Size Field -->
                    <div class="mb-3">
                        <label for="size_id" class="form-label">Size</label>
                        <div class="input-group">
                            <select name="size_id" id="size_id" class="form-select" required>
                                <option value="">Select or Add New</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->size }} mm</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="addNewSizeBtn">Add New</button>
                        </div>
                        <input type="text" id="new_size" class="form-control mt-2 d-none" placeholder="Enter new size (e.g., 0.47)">
                    </div>

                    <!-- Color Field -->
                    <div class="mb-3">
                        <label for="color_id" class="form-label">Color</label>
                        <div class="input-group">
                            <select name="color_id" id="color_id" class="form-select" required>
                                <option value="">Select or Add New</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->color }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="addNewColorBtn">Add New</button>
                        </div>
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

    <!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Handle "Add New" button for Type
        $('#addNewTypeBtn').on('click', function () {
            $('#type_id').prop('disabled', true); // Disable the dropdown
            $('#new_type').removeClass('d-none'); // Show the input field
        });

        // Handle "Add New" button for Size
        $('#addNewSizeBtn').on('click', function () {
            $('#size_id').prop('disabled', true);
            $('#new_size').removeClass('d-none');
        });

        // Handle "Add New" button for Color
        $('#addNewColorBtn').on('click', function () {
            $('#color_id').prop('disabled', true);
            $('#new_color').removeClass('d-none');
        });

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

        // Submit form with AJAX if new values are added
        $('#addSteelSheetForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            // Check if new type is being added
            if ($('#new_type').is(':visible')) {
                formData.append('new_type', $('#new_type').val());
            }

            // Check if new size is being added
            if ($('#new_size').is(':visible')) {
                formData.append('new_size', $('#new_size').val());
            }

            // Check if new color is being added
            if ($('#new_color').is(':visible')) {
                formData.append('new_color', $('#new_color').val());
            }

            $.ajax({
                url: "{{ route('steel-sheets.store') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    alert(response.message);
                    location.reload(); // Reload the page after successful submission
                },
                error: function (error) {
                    alert('An error occurred while adding the steel sheet.');
                }
            });
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
</body>
</html>