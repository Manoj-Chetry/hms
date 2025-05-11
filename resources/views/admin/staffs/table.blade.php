
<style>
    .add-btn {
        background-color: #17a2b8;
        color: #fff;
        padding: 10px 16px;
        font-size: 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .add-btn:hover {
        background-color: #138496;
    }

    /* Table */

    .staff-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: 'Arial', sans-serif;
    }

    .staff-table thead {
        background-color: #007bff;
        color: #ffffff;
        font-weight: bold;
    }

    .staff-table th, .staff-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #f2f2f2;
    }

    .staff-table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .staff-table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    .staff-table tbody tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .action-buttons button {
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .edit-btn {
        background-color: #28a745;
        color: #fff;
    }

    .edit-btn:hover {
        background-color: #218838;
    }

    .delete-btn {
        background-color: #dc3545;
        color: #fff;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

   /* Style for Modal */
#editModal, #addModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
    display: none; /* Hidden by default */
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.modal-content h3 {
    margin-bottom: 15px;
}

.modal-content label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.modal-content input {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.modal-content button {
    padding: 8px 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.modal-content button[type="submit"] {
    background-color: #007bff;
    color: white;
}

.modal-content button[type="button"] {
    background-color: #6c757d;
    color: white;
    margin-left: 10px;
}

#addForm select,
#addForm>.hostelField>select {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

</style>


<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <button class="add-btn" type="button" onclick="openAddModal()">Add New Staff</button>
</div>
{{-- Add modal --}}
<div id="addModal" style="display: none;">
    <div class="modal-content">
        <h3>Add Staff</h3>
        <form id="editForm" method="POST" action="{{route("admin.staff.add")}}">
            @csrf
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="role">Role:</label>
            <select name="role" id="role" required onchange="toggleFieldVisibility()">
                <option value="" disabled selected>Select Role</option>
                <option value="dsw">DSW</option>
                <option value="caretaker">Caretaker</option>
                <option value="warden">Warden</option>
                <option value="attender">Attender</option>
                <option value="hod">HOD</option>
            </select><br><br>

            <div id="departmentField" style="display: none;">
                <label for="department_id">Department ID:</label>
                <input type="number" name="department_id" id="department_id">
            </div>
    
            <div id="hostelField" style="display: none;">
                <label for="hostel_id">Hostel:</label>
                <select name="hostel_id" id="hostel_id" required>
                    <option value="Null" disabled selected>Select Hostel</option>
                    @foreach($hostels as $hostel)
                        <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit">Add Staff</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>


<table class="staff-table">
    <thead>
        <tr>
            <th>Staff ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Department</th>
            <th>Hostel</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($staffs as $staff)
            @php
                $hostelName = $hostels->firstWhere('id', $staff->hostel_id)->name ?? 'N/A';
            @endphp
            <tr>
                <td>{{ $staff->id }}</td>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->email }}</td>
                <td>{{ $staff->role }}</td>
                <td>{{ $staff->department_id ?? 'N/A' }}</td>
                <td>{{ $hostelName }}</td>
                <td class="action-buttons">
                    <button class="edit-btn" type="button" onclick="openEditModal()">Edit</button>
                    <form action="{{ route('admin.staff.delete', $staff->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
    
</table>

<!-- Modal -->
<div id="editModal" style="display: none;">
    <div class="modal-content">
        <h3>Edit Staff</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="staff_id">
            <label for="edit_name">Name:</label>
            <input type="text" name="name" id="edit_name" required>

            <label for="edit_email">Email:</label>
            <input type="email" name="email" id="edit_email" required>

            <label for="edit_role">Role:</label>
            <input type="text" name="role" id="edit_role" required>

            <label for="edit_department_id">Department ID:</label>
            <input type="text" name="department_id" id="edit_department_id">

            <label for="edit_hostel_id">Hostel ID:</label>
            <input type="text" name="hostel_id" id="edit_hostel_id">

            <button type="submit">Update</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>


