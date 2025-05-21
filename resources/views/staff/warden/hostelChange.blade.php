<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/staff/warden/hostelchange.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Warden</h2>
                <nav> 
                    <a href="{{route('staff.warden.dashboard')}}">Home</a>
                    <a href="#" onclick="toggleChangeOut()">Hostel Change Request: Out</a>
                    <a href="#" onclick="toggleChangeIn()">Hostel Change Request: In</a>
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

                    <div class="table-container" id="hosou">
                    <h2>Hostel Change Requests: Out</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Roll Number</th>
                                    <th>Current Hostel</th>
                                    <th>Requested Hostel</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($hostelChangeOut->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Hostel Change Requests ----</h4></td></tr>
                                @else
                                    @foreach ($hostelChangeOut as $i=>$l)
                                        <tr>
                                            <td>{{$l->student->roll_number}}</td>
                                            <td>{{$l->student->hostel->name}}</td>
                                            <td>{{$l->destinationHostel->name}}</td>
                                            <td>{{$l->status}}</td>
                                            <td>
                                                <form action="{{route('staff.warden.hostel.approve', $l->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="action-btn approve">Approve</button>
                                                </form>

                                                <form action="{{route('staff.warden.hostel.reject', $l->id)}}" method="post">
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

                    <div class="table-container hidden" id="hosin">
                    <h2>Hostel Change Requests: In</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Roll Number</th>
                                    <th>Current Hostel</th>
                                    <th>Requested Hostel</th>
                                    <th>Status</th>
                                    <th>New Seat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($hostelChangeIn->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Hostel Change Requests ----</h4></td></tr>
                                @else
                                    @foreach ($hostelChangeIn as $i=>$l)
                                        <tr>
                                            <td>{{$l->student->roll_number}}</td>
                                            <td>{{$l->student->hostel->name}}</td>
                                            <td>{{$l->destinationHostel->name}}</td>
                                            <td>{{$l->status}}</td>
                                            <td>@if($l->new_seat_id){{$l->new_seat_id}}@else{{'N/A'}}@endif</td>
                                            <td>
                                                <form action="{{route('staff.warden.hostelIn.approve', $l->id)}}" method="post">
                                                    @csrf
                                                    <select name="seat" required>
                                                        <option value="">Select Seat</option>
                                                        @foreach ($vacant as $v)
                                                            <option value="{{$v->id}}">{{$v->seat}}</option>
                                                        @endforeach
                                                    </select><br><br>
                                                    <button type="submit" class="action-btn approve">Approve</button>
                                                </form><br>

                                                <form action="{{route('staff.warden.hostel.reject', $l->id)}}" method="post">
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
        <script>
            function toggleChangeOut(){
                const hosou = document.getElementById('hosou');
                const hosin = document.getElementById('hosin');
                hosou.classList.remove('hidden');
                hosin.classList.add('hidden');
            }
            function toggleChangeIn(){
                const hosin = document.getElementById('hosin');
                const hosou = document.getElementById('hosou');
                hosin.classList.remove('hidden');
                hosou.classList.add('hidden');
            }
        </script>
    </body>
</html>