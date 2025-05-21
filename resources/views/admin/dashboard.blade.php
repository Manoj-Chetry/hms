<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/admin/dashboard.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Admin</h2>
                <nav>
                    <a href="#" onclick="toggleStaff()">Users</a>
                    <a href="#" onclick="toggleForm()">Upload Student Info</a>
                    <a href="{{route('admin.departments.all')}}">Departments</a>
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, Admin</div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </header>
    
                <!-- Dashboard Content -->
                <section class="content">

                    @if(session('success'))
                        <p style="color:green">{{ session('success') }}</p>
                    @endif
            
                    @if($errors->any())
                        <ul style="color:red">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
    
    
                    <!-- Upload Student Info Form -->
                    <div id="uploadForm" class="upload-form hidden">
                        <h2>Upload Student Info</h2>
                        <form action="{{route('admin.student_upload')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="student_csv">Select CSV File:</label><br>
                            <input type="file" id="student_csv" name="student_csv" accept=".csv" required><br><br>
                            <button type="submit" class="upload-btn">Upload</button>
                        </form>
                    </div>

                    <div id="staffview" class="upload-form hidden">
                        
                    </div>
                </section>
            </main>
        </div>
    
        <script>
            function toggleForm() {
                const form = document.getElementById("uploadForm");
                form.classList.toggle("hidden");
            }
            function toggleStaff() {
                const staffView = document.getElementById("staffview");
                staffView.classList.toggle("hidden");  // Toggle visibility of staff view

                if (!staffView.innerHTML.trim()) {  // If content is empty, load staff data
                    fetch("{{ route('admin.staffs.all') }}")
                        .then(response => response.text())  // Get response text (staff table)
                        .then(data => {
                            staffView.innerHTML = `<h2>Staff Details</h2>` + data;  // Add the table
                        })
                        .catch(error => {
                            console.error("Failed to load staff details:", error);
                            staffView.innerHTML = "<p style='color:red'>Error loading staff details.</p>";
                        });
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                // Check if the showStaff session exists
                const showStaffSection = @json(session('showStaff', false));

                if (showStaffSection) {
                    // If true, toggle the staff section to show
                    toggleStaff();
                }
            });


            // Open the Edit Modal form with staff data
            function openEditModal(staff) {

                if(!staff){
                    console.log("staff not defined");
                    return;
                }
                const modal = document.getElementById('editModal');
                modal.style.display = 'flex'; // Show the modal

                // Populate the modal form with staff data
                document.getElementById('staff_id').value = staff.id;
                document.getElementById('edit_name').value = staff.name;
                document.getElementById('edit_email').value = staff.email;
                document.getElementById('edit_role').value = staff.role;
                document.getElementById('edit_department_id').value = staff.department_id ?? '';
                document.getElementById('edit_hostel_id').value = staff.hostel_id ?? '';

                // Set the form's action to the staff's update URL
                document.getElementById('editForm').action = `/admin/staff/${staff.id}`;
            }

            // Close the modal
            function closeModal() {
                const modal = document.getElementById('editModal');
                modal.style.display = 'none'; // Hide the modal
            }



            function openAddModal() {
                const modal = document.getElementById('addModal');
                modal.style.display = 'flex'; // Show the modal

            }
            function closeModal() {
                const modal = document.getElementById('addModal');
                modal.style.display = 'none'; // Hide the modal
            }




            function toggleFieldVisibility() {
                const role = document.getElementById('role').value;
                const departmentField = document.getElementById('departmentField');
                const hostelField = document.getElementById('hostelField');

                if (role === 'hod') {
                    departmentField.style.display = 'block';
                    hostelField.style.display = 'none';
                } else if (['warden', 'caretaker', 'attender'].includes(role)) {
                    departmentField.style.display = 'none';
                    hostelField.style.display = 'block';
                } else {
                    departmentField.style.display = 'none';
                    hostelField.style.display = 'none';
                }
            }

            
        </script>
        @stack('scripts')

    </body>

</html>
    