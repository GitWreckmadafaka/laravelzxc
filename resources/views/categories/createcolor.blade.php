<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Colors</h1>

        <!-- Button to trigger modal for adding a new color -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addColorModal">Add Color</button>
        <a href="/admin/users" class="btn btn-secondary mb-3">Back to Product List</a>

        <!-- Colors Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Color Name</th>
                    <th>Color Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($colors as $color)
                    <tr>
                        <td>{{ $color->name }}</td>
                        <td style="background-color: {{ $color->code }};">{{ $color->code }}</td>
                        <td>
                            <!-- Button to trigger modal for editing color -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#editColorModal" data-id="{{ $color->id }}" data-name="{{ $color->name }}" data-code="{{ $color->code }}">Edit</button>
                            <!-- Button to trigger delete -->
                            <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this color?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal for Adding Color -->
    <div class="modal fade" id="addColorModal" tabindex="-1" role="dialog" aria-labelledby="addColorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addColorModalLabel">Add Color</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.colors.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="colorName">Color Name</label>
                            <input type="text" class="form-control" id="colorName" name="name" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Color</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Color -->
    <div class="modal fade" id="editColorModal" tabindex="-1" role="dialog" aria-labelledby="editColorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editColorModalLabel">Edit Color</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editColorForm" method="POST">
                        @csrf
                        @method('PUT') <!-- This will send the PUT request for updating the color -->
                        <div class="form-group">
                            <label for="editColorName">Color Name</label>
                            <input type="text" class="form-control" id="editColorName" name="name" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Script to load data into the edit modal when the edit button is clicked
        $('#editColorModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var colorId = button.data('id'); // Extract info from data-* attributes
            var colorName = button.data('name');
            var colorCode = button.data('code');

            // Populate the modal form with the existing color data
            var modal = $(this);
            modal.find('#editColorName').val(colorName);
            modal.find('#editColorCode').val(colorCode);
            modal.find('form').attr('action', '/admin/colors/' + colorId); // Dynamically set the action URL
        });
    </script>
</body>
</html>
