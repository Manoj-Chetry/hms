<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/staff/hod/leave.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>hod</h2>
                <nav>
                    
                    <a href="{{route('staff.hod.dashboard')}}">Home</a>
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, hod</div>
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
    
                    <div class="table-container">
                        <form class="search" method="GET" action="{{ route('staff.students.index') }}">
                            <input type="text" name="search" placeholder="Search students..." value="{{ request('search') }}">
                            <button type="submit">Search</button>
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Roll Number</th>
                                    <th>Reason</th>
                                    <th>Deperature Time</th>
                                    <th>Arrival Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($leave->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Leave Requests ----</h4></td></tr>
                                @else
                                    @foreach ($leave as $i=>$l)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$l->student_id}}</td>
                                            <td>{{$l->reason}}</td>
                                            <td>{{$l->deperature_time}}</td>
                                            <td>{{$l->arrival_time}}</td>
                                            <td>
                                                <form action="{{route('staff.hod.leave.approve', $l->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="action-btn">Approve</button>
                                                </form>
                                                <form action="{{route('staff.hod.leave.reject', $l->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="action-btn reject">Reject</button>
                                                </form>
                                            </td>
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
    