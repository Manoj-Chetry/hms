<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{URL::asset('css/staff/warden/students.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>warden</h2>
                <nav> 
                    <a href="#">Student Details</a>
                    <a href="#">Room Details</a>
                    <a href="#">Manage Roles</a>
                    <a href="#">Manage Complaints</a>
                    <a href="#">Manage Leaves</a>
                    <a href="#">Manage Hostel Change</a>
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, <strong>{{$user->name}}</strong></div>
                    <form action="{{route("staff.logout")}}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </header>

                <!-- Dashboard Content -->
                <section class="content">
                    <h1>Student Details</h1>

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

                    <div class="table-container">
                        <form method="GET" action="{{ route('staff.students.index') }}">
                            <input type="text" name="search" placeholder="Search students..." value="{{ request('search') }}">
                            <button type="submit">Search</button>
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Roll Number</th>
                                    <th>Department</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Seat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($students->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Students In Your Hostel ----</h4></td></tr>
                                @else
                                    @foreach ($students as $student)
                                        <tr>
                                            <td>{{$student->name}}</td>
                                            <td>{{$student->roll_number}}</td>
                                            <td>{{$student->department}}</td>
                                            <td>{{$student->email}}</td>
                                            <td>{{$student->phone}}</td>
                                            <td>{{$student->seat}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>

    
                </section>
            </main>
        </div>
    </body>
</html>