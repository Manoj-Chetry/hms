<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/staff/caretaker.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Caretaker: {{$hostel->name}}</h2>
                <nav>
                    <a href="{{route('staff.caretaker.dashboard')}}">Home</a>
                    <a href="{{route("staff.caretaker.student_details")}}">Student Details</a>
                    <a href="{{route('staff.caretaker.room_details')}}">Room Details</a>
                    <a href="{{route('staff.caretaker.view.general_complaints')}}">General Complaints</a>
                    {{-- <a href="{{route('staff.caretaker.view.ragging_complaints')}}">Ragging Complaints</a> --}}
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, Caretaker <strong>{{ $hostel->name }}</strong></div>
                    <form action="{{route("staff.logout")}}" method="POST">
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

                    <div class="roles-info">
                        @foreach ($roles as $role)
                            <div class="role-item">
                                <span class="label">{{$role->role}}</span>
                                <span class="value">{{$role->name}}</span>
                            </div>
                        @endforeach
                    </div>

    
                </section>
            </main>
        </div>
    
        
    </body>
    