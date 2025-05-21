<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{URL::asset('css/staff/warden/complaint.css')}}">
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
                    <a href="#" onclick="openPending()">Pending Complaints</a>
                    <a href="#" onclick="openResolved()">Resolved Complaints</a>
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, Warden</div>
                    <form action="{{route("staff.logout")}}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </header>
    
                <!-- Dashboard Content -->
                <section class="content">
                    <h1>General Complaints</h1>

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

                    <div class="table-container" id="pending">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Roll Number</th>
                                    <th>Issue Description</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($complaints->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Complaints In Your Hostel ----</h4></td></tr>
                                @else
                                    @foreach ($complaints as $index=>$complaint)
                                        <tr onclick="rowclick({{$complaint}})">
                                            <td>{{$index+1}}</td>
                                            <td>{{$complaint->student_id}}</td>
                                            <td>{{ \Illuminate\Support\Str::words($complaint->issue_description, 5, '...') }}</td>
                                            <td>{{$complaint->created_at}}</td>
                                            <td class="action-buttons">
                                                <form action="{{ route('staff.warden.complaint.resolve', $complaint->id) }}" method="POST" onclick="event.stopPropagation();">
                                                    @csrf
                                                    <button type="submit" class="edit-btn">Mark Resolved</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>

                    <div id="complaintModal" class="modal" style="display:none;">
                        <div class="modal-content">
                            <span class="close" onclick="closeComplaintModal()">&times;</span>
                            <h2>Complaint Details</h2>
                            <p><strong>Student Roll:</strong> <span id="modal-student-id"></span></p>
                            <p><strong>Date:</strong> <span id="modal-date"></span></p>
                            <p><strong>Description:</strong> <span id="modal-description"></span></p>
                        </div>
                    </div>
                    <div class="table-container hidden" id="resolved">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Roll Number</th>
                                    <th>Issue Description</th>
                                    <th>Created At</th>
                                    <th>Resolved At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($resolved->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Resolved Complaints In Your Hostel ----</h4></td></tr>
                                @else
                                    @foreach ($resolved as $index=>$r)
                                        <tr onclick="rowclick({{$r}})">
                                            <td>{{$index+1}}</td>
                                            <td>{{$r->student_id}}</td>
                                            <td>{{ \Illuminate\Support\Str::words($r->issue_description, 5, '...') }}</td>
                                            <td>{{$r->created_at}}</td>
                                            <td>{{$r->resolved_at}}</td>
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
            function rowclick(complaint){
                document.getElementById("modal-student-id").innerText = complaint.student_id;
                document.getElementById("modal-description").innerText = complaint.issue_description;
                document.getElementById("modal-date").innerText = complaint.created_at;

                document.getElementById("complaintModal").style.display = "block";
            }

            function closeComplaintModal() {
                document.getElementById("complaintModal").style.display = "none";
            }

            function openPending(){
                pending = document.getElementById('pending');
                resolved = document.getElementById('resolved');
                pending.classList.remove('hidden');
                resolved.classList.add('hidden');
            }
            function openResolved(){
                pending = document.getElementById('pending');
                resolved = document.getElementById('resolved');
                resolved.classList.remove('hidden');
                pending.classList.add('hidden');
            }
        </script>
    </body>
    