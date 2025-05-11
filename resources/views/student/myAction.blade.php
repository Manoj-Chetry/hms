<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{URL::asset('css/student/myActions.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Student</h2>
                <nav>
                    <a href="{{route('student.dashboard')}}">Home</a>
                    <a href="#" onclick="toggleComplaint()">My Complaints</a>
                    <a href="#" onclick="toggleLeave()">My Leaves</a>
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, Student</div>
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

                    {{-- Actions Table--}}
                    <h2>My Actions</h2>

                    <div class="complaints-table-container" id="complaint">
                        <h2>My Complaints</h2>
                        <table class="complaints-table">
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Issue</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $index => $complaint)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $complaint->issue_description }}</td>
                                        <td>{{ date('d M Y', strtotime($complaint->created_at)) }}</td>
                                        <td>
                                            <span class="status {{ $complaint->status }}">{{ ucfirst($complaint->status) }}</span>
                                        </td>
                                        <td>{{$complaint->type}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="complaints-table-container hidden" id="leave">
                        <h2>My Leave Requests</h2>
                        <table class="complaints-table">
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Reason</th>
                                    <th>Deperature Date</th>
                                    <th>Arrival Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $index => $leave)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $leave->reason }}</td>
                                        <td>{{ date('d M Y', strtotime($leave->deperature_time)) }}</td>
                                        <td>{{ date('d M Y', strtotime($leave->arrival_time)) }}</td>
                                        <td>
                                            <span class="status {{ $leave->status }}">{{ ucfirst($leave->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </section>
            </main>
        </div>


        <script>
            function toggleComplaint(){
                const form = document.getElementById("complaint");
                const form2 = document.getElementById("leave");
                form.classList.remove("hidden");
                form2.classList.add("hidden");
            }
            function toggleLeave(){
                const form = document.getElementById("complaint");
                const form2 = document.getElementById("leave");
                form.classList.add("hidden");
                form2.classList.remove("hidden");
            }
        </script>
    </body>
</html>