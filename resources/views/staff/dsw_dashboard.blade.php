<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <link rel="stylesheet" href="{{URL::asset("css/staff/dsw.css")}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>DSW</h2>
                <nav>
                    
                    <a href="#" onclick="openEditModal()">Add Hostel</a>
                    <a href="#" onclick="toggleView()">Hostel Info</a>
                    <a href="#" onclick="toggleChange()">Hostel Change Request</a>
                </nav>
            </aside>
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="greeting">Welcome, DSW</div>
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


                    <div id="view-hostel" class="view-hostel hidden">
                        <h2>Hostels</h2>
                        <div class="hostel-card-grid">
                            @foreach ($hostels as $hostel)
                                <a href="{{route('staff.dsw.hostel',$hostel->name)}}" class="hostel-card">
                                    <div class="hostel-card-content">
                                        @if ($hostel->logo)
                                            <img src="{{ asset('storage/' . $hostel->logo) }}" alt="{{ $hostel->name }} Logo" class="hostel-logo">
                                        @else
                                            <img src="{{ asset('images/default-hostel-icon.png') }}" alt="Default Icon" class="hostel-logo">
                                        @endif
                                        <h3 class="hostel-name">{{ $hostel->name }}</h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

    
                </section>

                <div class="table-container" id="hos">
                    <h2>Hostel Change Requests</h2>
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
                                @if ($hostelChange->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Hostel Change Requests ----</h4></td></tr>
                                @else
                                    @foreach ($hostelChange as $i=>$l)
                                        <tr>
                                            <td>{{$l->student->roll_number}}</td>
                                            <td>{{$l->student->hostel->name}}</td>
                                            <td>{{$l->destinationHostel->name}}</td>
                                            <td>{{$l->status}}</td>
                                            <td>@if($l->new_seat_id){{$l->new_seat_id}}@else{{'N/A'}}@endif</td>
                                            <td>
                                                @if($l->new_seat_id!=Null)
                                                    <form action="{{route('staff.dsw.hostel.approve', $l->id)}}" method="post">
                                                        @csrf
                                                        <button type="submit" class="action-btn approve">Approve</button>
                                                    </form>
                                                @else
                                                    <form action="{{route('staff.dsw.hostel.forward', $l->id)}}" method="post">
                                                        @csrf
                                                        <button type="submit" class="action-btn approve">Forward</button>
                                                    </form>
                                                @endif

                                                <form action="{{route('staff.dsw.hostel.reject', $l->id)}}" method="post">
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

                <div id="editModal" style="display: none;">
                    <div class="modal-content">
                        <h3>Add Hostel</h3>
                        <form id="editForm" method="POST" action="{{route("staff.dsw.addHostel")}}">
                            @csrf
                            <label for="edit_name">Hostel Name:</label>
                            <input type="text" name="name" id="edit_name" required>

                            <label for="floors">Floors:</label>
                            <input type="number" name="floors" id="floors" >

                            <label for="gender">Gender: </label>
                            <select name="gender" id="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select><br><br>
                
                            <button type="submit">Add</button>
                            <button type="button" onclick="closeModal()">Cancel</button>
                        </form>
                    </div>
                </div>


                
            </main>
        </div>
    

        <script>
            function openEditModal() {
                
                const modal = document.getElementById('editModal');
                modal.style.display = 'flex'; // Show the modal

                // Set the form's action to the staff's update URL
                document.getElementById('editForm').action = `/admin/staff/${staff.id}`;
            }

            // Close the modal
            function closeModal() {
                const modal = document.getElementById('editModal');
                modal.style.display = 'none'; // Hide the modal
            }


            function toggleView(){
                const form = document.getElementById("view-hostel");
                form.classList.toggle("hidden");
            }

            function toggleChange(){
                const h = document.getElementById('hos');
                h.classList.toggle('hidden');
            }

        </script>
        
    </body>
    