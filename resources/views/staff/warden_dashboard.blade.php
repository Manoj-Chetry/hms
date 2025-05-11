<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/staff/warden.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>warden</h2>
                <nav> 
                    <a href="{{route('staff.warden.student_details')}}">Student Details</a>
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
                    <h1>{{$hostel->name}}</h1>

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
    
                    <div class="details-bar">
                        <div class="detail-item">
                            <span class="label">Total Rooms</span>
                            <span class="value">{{ $rooms->count() }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Total Seats</span>
                            <span class="value">{{ $seats->count() }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Empty Seats</span>
                            <span class="value">{{ $seats->where('occupied',false)->count() }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Occupied Seats</span>
                            <span class="value">{{ $seats->where('occupied',true)->count() }}</span>
                        </div>
                    </div>

                    <div class="roles-info">
                        <div class="role-item">
                            <span class="label">Prefect</span>
                            <span class="value">Prefect</span>
                        </div>
                        <div class="role-item">
                            <span class="label">Assistant Prefect</span>
                            <span class="value">Abc</span>
                        </div>
                        <div class="role-item">
                            <span class="label">Mess Conveynor</span>
                            <span class="value">Abc</span>
                        </div>
                        <div class="role-item">
                            <span class="label">Mess Manager</span>
                            <span class="value">Abc</span>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    
        
    </body>
    