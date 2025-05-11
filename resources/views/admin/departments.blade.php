<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/admin/departments.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Admin</h2>
                <nav>
                    <a href="{{route('admin.dashboard')}}">Home</a>
                    <a href="#" onclick="toggleTable()">Departments</a>
                    <a href="#" onclick="toggleForm()">Add New Department</a>
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
                    <h1>Departments</h1>
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
                        
                    <div id="add" class="upload-form hidden">
                        <h2>Add New Department</h2>
                        <form action="{{route('admin.department.add')}}" method="POST">
                            @csrf
                            <label for="name">Department Name</label>
                            <input type="text" id="name" name="name" required>
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Add Department</button>
                                <button  class="cancel-btn">Cancel</button>
                            </div>
                        </form>
                    </div>  

                    <table id="table" class="staff-table">
                        <thead>
                            <tr>
                                <th>Department ID</th>
                                <th>Department Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{$department->id}}</td>
                                    <td>{{$department->name}}</td>
                                    <td class="action-buttons">
                                        <button class="edit-btn" type="button" onclick="">Edit</button>
                                        <form action="{{route('admin.department.delete',$department->id)}}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
    
                </section>
            </main>
        </div>
    
        <script>
            function toggleForm() {
                const form = document.getElementById("add");
                const table = document.getElementById("table");
                form.classList.remove("hidden");
                table.classList.add("hidden");
            }

            function toggleTable() {
                const form = document.getElementById("add");
                const table = document.getElementById("table");
                table.classList.remove("hidden");
                form.classList.add("hidden");
            }
            
        </script>
        @stack('scripts')

    </body>

</html>
    