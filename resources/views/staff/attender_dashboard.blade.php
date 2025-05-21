<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/staff/attender.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Attender: {{$hos->name}}</h2>
                <nav>
                    
                    <a href="#" onclick="toggleOut()">Out Record</a>
                    <a href="#" onclick="toggleIn()">In Record</a>
                    <a href="#" onclick="toggleRec()">Out Data</a>
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, attender</div>
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
    
                    <div class="table-container" id="out">
                        <form class="search" method="GET" action="{{ route('staff.students.index') }}">
                            <input type="text" name="search" placeholder="Search students..." value="{{ request('search') }}">
                            <button type="submit">Search</button>
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th>Roll Number</th>
                                    <th>Reason</th>
                                    <th>Deperature Time</th>
                                    <th>Arrival Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($leave->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Leave Requests ----</h4></td></tr>
                                @else
                                    @foreach ($leave as $i=>$l)
                                        <tr>
                                            <td>{{$l->student_id}}</td>
                                            <td>{{$l->reason}}</td>
                                            <td>{{$l->deperature_time}}</td>
                                            <td>{{$l->arrival_time}}</td>
                                            <td>{{$l->status}}</td>
                                            <td>
                                                <form action="{{route('staff.attender.out.entry', $l->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="action-btn">Out Entry</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="table-container hidden" id="inn">
                        <form class="search" method="GET" action="{{ route('staff.students.index') }}">
                            <input type="text" name="search" placeholder="Search students..." value="{{ request('search') }}">
                            <button type="submit">Search</button>
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th>Roll Number</th>
                                    <th>Deperature Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($out->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Leave Requests ----</h4></td></tr>
                                @else
                                    @foreach ($out as $i=>$l)
                                        <tr>
                                            <td>{{$l->student_id}}</td>
                                            <td>{{$l->out_date}}</td>
                                            <td>
                                                <form action="{{route('staff.attender.in.entry', $l->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="action-btn">In Entry</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="table-container hidden" id="rec">
                        <form class="search" method="GET" action="{{ route('staff.students.index') }}">
                            <input type="text" name="search" placeholder="Search students..." value="{{ request('search') }}">
                            <button type="submit">Search</button>
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th>Roll Number</th>
                                    <th>Deperature Time</th>
                                    <th>Arrival Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($rec->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Leave Requests ----</h4></td></tr>
                                @else
                                    @foreach ($rec as $l)
                                        <tr>
                                            <td>{{$l->student_id}}</td>
                                            <td>{{$l->out_date}}</td>
                                            <td>{{$l->in_date}}</td>
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
            function toggleIn(){
                out = document.getElementById('out');
                inn = document.getElementById('inn');
                rec = document.getElementById('rec');
                inn.classList.remove('hidden');
                rec.classList.add('hidden');
                out.classList.add('hidden');
            }
            function toggleOut(){
                out = document.getElementById('out');
                inn = document.getElementById('inn');
                rec = document.getElementById('rec');
                inn.classList.add('hidden');
                rec.classList.add('hidden');
                out.classList.remove('hidden');
            }
            function toggleRec(){
                out = document.getElementById('out');
                inn = document.getElementById('inn');
                rec = document.getElementById('rec');
                rec.classList.remove('hidden');
                inn.classList.add('hidden');
                out.classList.add('hidden');
            }
        </script>
    </body>
    