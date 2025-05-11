<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{URL::asset('css/student/dashboard.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Student</h2>
                <nav>
                    
                    <a href="#" onclick="toggleComplaint()">Lodge General Complaint</a>
                    <a href="#" onclick="toggleRagging()">Lodge Ragging Complaint</a>
                    <a href="#" onclick="toggleHostel()">Hostel Change Request</a>
                    <a href="#" onclick="toggleLeave()">Apply Leave</a>
                    <a href="{{route('student.myActions')}}">My Actions</a>
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

                

                    <div id="complaint" class="upload-form hidden">
                        <h2>Lodge a Complaint</h2>
                        <form action="{{route('student.makeComplaint')}}" method="POST">
                            @csrf
                            <label for="issue_description">Issue Description</label>
                            <textarea id="issue_description" name="issue_description" required></textarea>
                    
                            <label for="created_at">Date</label>
                            <input type="date" id="created_at" name="created_at" required>
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Lodge Complaint</button>
                                <button  class="cancel-btn">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <div id="ragging" class="upload-form hidden">
                        <h2>Ragging Complaint</h2>
                        <form action="{{route('student.makeRaggingComplaint')}}" method="POST">
                            @csrf
                            <label for="issue_description">Issue Description</label>
                            <textarea id="issue_description" name="issue_description" required></textarea>
                    
                            <label for="created_at">Date</label>
                            <input type="date" id="created_at" name="created_at" required>
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Lodge Complaint</button>
                                <button  class="cancel-btn">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <div id="hostel" class="upload-form hidden">
                        <h2>Hostel Change Request</h2>
                        <form action="{{route('student.hostelChange')}}" method="POST">
                            @csrf
                            <label for="reason">Reason</label>
                            <textarea id="reason" name="reason" required></textarea>
                    
                            <label for="destination_hostel">Which Hostel You Want To Go: </label>
                            <select id="destination_hostel" name="destination_hostel" required>
                                @foreach ($hostels as $h)
                                    <option value="{{$h->id}}">{{$h->name}}</option>
                                @endforeach
                            </select>
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Apply</button>
                                <button  class="cancel-btn">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <div id="leave" class="upload-form hidden">
                        <h2>Leave Request</h2>
                        <form action="{{route("student.applyLeave")}}" method="POST">
                            @csrf
                            <label for="reason">Reason</label>
                            <textarea id="reason" name="reason" required></textarea>
                    
                            <label for="deperature_time">Deperature Time</label>
                            <input type="date" id="deperature_time" name="deperature_time" required>

                            <label for="arrival_time">Arrival Time</label>
                            <input type="date" id="arrival_time" name="arrival_time" required>
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Apply Leave</button>
                                <button  class="cancel-btn">Cancel</button>
                            </div>
                        </form>
                    </div>
                    

    
                </section>
            </main>
        </div>
    
        <script>
            
            function toggleComplaint(){
                const complaint = document.getElementById("complaint");
                const ragging = document.getElementById("ragging");
                const leave = document.getElementById("leave");
                const hostel = document.getElementById("hostel");
                complaint.classList.remove("hidden");
                ragging.classList.add("hidden");
                leave.classList.add("hidden");
                hostel.classList.add("hidden");
            }

            function toggleRagging(){
                const complaint = document.getElementById("complaint");
                const ragging = document.getElementById("ragging");
                const leave = document.getElementById("leave");
                const hostel = document.getElementById("hostel");
                ragging.classList.remove("hidden");
                complaint.classList.add("hidden");
                leave.classList.add("hidden");
                hostel.classList.add("hidden");
            }

            function toggleLeave(){
                const complaint = document.getElementById("complaint");
                const ragging = document.getElementById("ragging");
                const leave = document.getElementById("leave");
                const hostel = document.getElementById("hostel");
                leave.classList.remove("hidden");
                ragging.classList.add("hidden");
                complaint.classList.add("hidden");
                hostel.classList.add("hidden");
            }

            function toggleHostel(){
                const complaint = document.getElementById("complaint");
                const ragging = document.getElementById("ragging");
                const leave = document.getElementById("leave");
                const hostel = document.getElementById("hostel");
                hostel.classList.remove("hidden");
                ragging.classList.add("hidden");
                complaint.classList.add("hidden");
                leave.classList.add("hidden");
            }

        </script>
    </body>
    