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
                <a href="#" onclick="toggleProfile()">
                    <h2>Student <br> 
                        @if($isMessConveynor)
                        Mess Conveynor
                        @endif   
                    </h2>
                </a>
                <nav>
                    @if ($isMessConveynor)
                    <p>--Mess Conveynor Section--</p>
                    <a href="#" onclick="toggleExpense()">Monthly Expense</a>
                    <a href="{{route('mess.general')}}">General Fee List</a>
                    <a href="{{route('mess.pending')}}">Pending Dues</a>
                    <a href="#" onclick="toggleCollect()">Collect Fee</a>
                    <a href="#">Calculatte Dues</a>

                    <p>--General Student Section--</p>
                    @endif
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
                    <form action="{{ route('student.logout') }}" method="POST">
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

                    <div class="profile-container" id="profile">
                        <div class="profile-card">
                            <div class="profile-header">
                                <h2 class="student-name">{{$user->name}}</h2>
                                <p class="roll-number">Roll Number: {{$user->roll_number}}</p>
                            </div>
                            <div class="edit-button-wrapper">
                                <a href="{{ route('student.profile.edit', '') }}" class="edit-profile-btn">Edit Profile</a>
                            </div>
                            <div class="profile-details">
                                <h3>Contact Details</h3>
                                <ul>
                                    <li><strong>Email:</strong> {{$user->email}}</li>
                                    <li><strong>Phone:</strong> {{$user->phone}}</li>
                                    <li><strong>Department:</strong> {{$user->department}}</li>
                                    <li><strong>Room:</strong> {{$user->seat}}</li>
                                </ul>

                                <h3>Guardian Details</h3>
                                <ul>
                                    <li><strong>Name:</strong> Mr. Richard Doe</li>
                                    <li><strong>Relation:</strong> Father</li>
                                    <li><strong>Contact:</strong> +91 9123456780</li>
                                    <li><strong>Email:</strong> richard.doe@example.com</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($isMessConveynor)
                        <div id="monthlyExpense" class="upload-form hidden">
                            <h2>Enter Monthly Mess Expense</h2>
                            <form method="post" action="{{route('mess.expense')}}">
                                @csrf
                                <label for="totalExpense">Total Monthly Expense (₹):</label>
                                <input type="number" name="expense" id="totalExpense" class="form-control" required>

                                <label for="startDuration">Start Date of Fee Collection:</label>
                                <input type="date" name="startDate" id="startDuration" class="form-control" required>

                                <label for="endDuration">End Date of Fee Collection:</label>
                                <input type="date" name="endDate" id="endDuration" class="form-control" required>

                                <div class="btn-container">
                                    <button type="submit" class="upload-btn">Submit</button>
                                </div>
                            </form>
                        </div>

                        <div id="collectFee" class="upload-form hidden">
                            <h2>Collect Mess Fee</h2>
                            <form id="collectFeeForm" onsubmit="">
                                <label for="paymentReferenceId">Payment Reference ID:</label>
                                <input type="text" id="paymentReferenceId" class="form-control" required>

                                <label for="studentName">Student Name:</label>
                                <input type="text" id="studentName" class="form-control" required>

                                <label for="rollNo">Roll Number:</label>
                                <input type="text" id="rollNo" class="form-control" required>

                                <label for="amountPaid">Amount Paid (₹):</label>
                                <input type="number" id="amountPaid" class="form-control" required>

                                <button type="submit" class="upload-btn">Submit Payment</button>
                            </form>
                        </div>

                    <div class="calculate">
                        <form method="POST" class="form" action="{{ route('mess_dues.calculate') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="expense_id">Select Mess Expense Record:</label>
                                <select name="expense_id" id="expense_id" required>
                                    @foreach ($messExpenses as $expense)
                                        <option value="{{ $expense->id }}">
                                            {{ $expense->starting_date }} to {{ $expense->end_date }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="calc" type="submit">
                                Calculate Mess Dues
                            </button>
                        </form>
                    </div>
                    
                    @endif
                

                    <div id="complaint" class="upload-form hidden">
                        <h2>Lodge a Complaint</h2>
                        <form action="{{route('student.makeComplaint')}}" method="POST">
                            @csrf
                            <label for="issue_description">Issue Description</label>
                            <textarea id="issue_description" name="issue_description" required></textarea>
                    
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Lodge Complaint</button>
                                <button type="button" class="cancel-btn" onclick="handleCancel(this)">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <div id="ragging" class="upload-form hidden">
                        <h2>Ragging Complaint</h2>
                        <form action="{{route('student.makeRaggingComplaint')}}" method="POST">
                            @csrf
                            <label for="issue_description">Issue Description</label>
                            <textarea id="issue_description" name="issue_description" required></textarea>
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Lodge Complaint</button>
                                <button type="button" class="cancel-btn" onclick="handleCancel(this)">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <div id="hostel" class="upload-form hidden">
                        <h2>Hostel Change Request</h2>
                        <form action="{{route('student.hostelChange')}}" method="POST">
                            @csrf
                    
                            <label for="destination_hostel">Which Hostel You Want To Go: </label>
                            <select id="destination_hostel" name="destination_hostel" required>
                                @foreach ($hostels as $h)
                                    <option value="{{$h->id}}">{{$h->name}}</option>
                                @endforeach
                            </select>
                    
                            <div class="btn-container">
                                <button type="submit" class="upload-btn">Apply</button>
                                <button type="button" class="cancel-btn" onclick="handleCancel(this)">Cancel</button>
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
                                <button type="button" class="cancel-btn" onclick="handleCancel(this)">Cancel</button>
                            </div>
                        </form>
                    </div>
                    

    
                </section>
            </main>
        </div>
    
        <script>
                function hideAllSections() {
                    const sections = [
                        'profile', 'complaint', 'ragging', 'leave', 'hostel',
                        'monthlyExpense', 'collectFee', 'calculate'
                    ];
                    sections.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) {
                            el.classList.add('hidden');
                            console.log(`Hiding ${id}`);
                        } else {
                            console.warn(`Element with id="${id}" not found`);
                        }
                    });
                }


                function toggleProfile() {
                    hideAllSections();
                    document.getElementById('profile').classList.remove('hidden');
                }

                function toggleComplaint() {
                    hideAllSections();
                    document.getElementById('complaint').classList.remove('hidden');
                }

                function toggleRagging() {
                    hideAllSections();
                    document.getElementById('ragging').classList.remove('hidden');
                }

                function toggleLeave() {
                    hideAllSections();
                    document.getElementById('leave').classList.remove('hidden');
                }

                function toggleHostel() {
                    hideAllSections();
                    document.getElementById('hostel').classList.remove('hidden');
                }

                function toggleExpense() {
                    hideAllSections();
                    document.getElementById('monthlyExpense').classList.remove('hidden');
                }

                function toggleCollect() {
                    hideAllSections();
                    document.getElementById('collectFee').classList.remove('hidden');
                }

                function handleCancel(button) {
                    const form = button.closest('.upload-form');
                    if (form) form.classList.add('hidden');
                    document.getElementById('profile').classList.remove('hidden');
                }
        </script>
    </body>
</html>