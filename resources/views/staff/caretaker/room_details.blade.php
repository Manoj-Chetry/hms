<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ URL::asset('css/staff/caretaker/room_details.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/layout.css') }}">
    <title>Hostel Management System</title>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Caretaker</h2>
            <nav>
                <a href="{{ route('staff.caretaker.dashboard') }}">Home</a>
                <a href="{{ route('staff.caretaker.student_details') }}">Student Details</a>
                <a href="#">Room Details</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="greeting">Welcome, Caretaker <strong>{{ $hostel->name }}</strong></div>
                <form action="{{ route('staff.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </header>

            <!-- Dashboard Content -->
            <section class="content">
                <h1>Room Details</h1>

                @if(session('success'))
                    <p style="color:green">{!! session('success') !!}</p>
                @endif

                @if($errors->any())
                    <ul style="color:red">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="button-container">
                    <a href="#" class="add-room-btn" onclick="toggleModal(1)">Add Single Room</a>
                    <a href="#" class="add-room-btn" onclick="toggleModal(0)">Add Multiple Rooms</a>
                </div>

                <!-- Add Room Modal -->
                <div id="add-room-modal" class="modal-overlay">
                    <!-- Step 1: Seat Type Selection -->
                    <div id="seat-selection-step" class="modal-content">
                        <h2>Select Room Type</h2>
                        <div class="seat-options">
                            <button onclick="selectSeat(1)">Single Seater</button>
                            <button onclick="selectSeat(2)">Double Seater</button>
                            <button onclick="selectSeat(3)">Triple Seater</button>
                        </div>

                        <div class="custom-seat-group">
                            <label for="custom-seat-count">Custom:</label>
                            <input id="custom-seat-count" placeholder="Seats">
                            <button onclick="selectSeat(document.getElementById('custom-seat-count').value)">Proceed</button>
                        </div>

                        <button class="cancel-btn" onclick="toggleModal()">Cancel</button>
                    </div>

                    <!-- Single Room Form -->
                    <div id="room-form-step-sin" class="modal-content" style="display: none;">
                        <form action="{{route('staff.caretaker.add_single_room')}}" method="POST">
                            @csrf
                            <h2>Add Room</h2>

                            <input type="hidden" name="capacity" id="capacity_single" readonly>

                            <div class="form-group">
                                <label>Room Number:</label>
                                <input type="text" name="room_number" id="room_start" placeholder="e.g., 101" required>
                                <small>Example: Ground floor → 101–199, First floor → 201–299</small>
                            </div>

                            <div class="form-group">
                                <label for="floor">Floor:</label>
                                <select name="floor" id="floor">
                                    @for($i = 0; $i < $hostel->floors; $i++)
                                        <option value="{{ $i }}">{{ $i === 0 ? 'Ground' : $i . ' Floor' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="submit-btn" onclick="validateAndSubmitRoomForm()">Submit</button>
                                <button type="button" class="cancel-btn" onclick="toggleModal()">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Multiple Room Form -->
                    <div id="room-form-step-mul" class="modal-content" style="display: none;">
                        <form action="{{route('staff.caretaker.add_multiple_room')}}" method="POST">
                            @csrf
                            <h2>Add Multiple Rooms</h2>

                            <input type="hidden" name="capacity" id="capacity_multiple" readonly>

                            <div class="form-group">
                                <label>Room Number Range:</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" name="room_start" id="room_start" placeholder="Start (e.g., 101)" required>
                                    <input type="text" name="room_end" id="room_end" placeholder="End (e.g., 110)" required>
                                </div>
                                <small>Example: Ground floor → 101-199, First floor → 201-299</small>
                            </div>

                            <div class="form-group">
                                <label for="floor">Floor:</label>
                                <select name="floor" id="floor">
                                    @for($i = 0; $i < $hostel->floors; $i++)
                                        <option value="{{ $i }}">{{ $i === 0 ? 'Ground' : $i . ' Floor' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="submit-btn" onclick="validateAndSubmitRoomForm()">Submit</button>
                                <button type="button" class="cancel-btn" onclick="toggleModal()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Room Stats -->
                <div class="card-container">
                    <div class="card">
                        <h3>Number of Rooms</h3>
                        <p class="value">{{ $hostel->number_of_rooms }}</p>
                    </div>
                    <div class="card">
                        <h3>Number of Seats</h3>
                        <p class="value">{{ $hostel->number_of_seats }}</p>
                    </div>
                    <div class="card">
                        <h3>Occupied Seats</h3>
                        <p class="value">{{ ($hostel->number_of_seats-$empty_seats->count()) }}</p>
                    </div>
                    <div class="card">
                        <h3>Empty Seats</h3>
                        <p class="value">{{ $empty_seats->count() }}</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        let isSingleRoom = false;
    
        function toggleModal(flag) {
            const modal = document.getElementById('add-room-modal');
            const step1 = document.getElementById('seat-selection-step');
            const step2a = document.getElementById('room-form-step-sin');
            const step2b = document.getElementById('room-form-step-mul');
    
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
            step1.style.display = 'block';
    
            isSingleRoom = flag === 1;
    
            step2a.style.display = 'none';
            step2b.style.display = 'none';
    
            document.getElementById('capacity').value = '';
            document.getElementById('custom-seat-count').value = '';
            document.getElementById('room_start').value = '';
            if (!isSingleRoom) document.getElementById('room_end').value = '';
        }
    
        function selectSeat(seatCount) {
            const step1 = document.getElementById('seat-selection-step');
            const step2a = document.getElementById('room-form-step-sin');
            const step2b = document.getElementById('room-form-step-mul');

            step1.style.display = 'none';

            if (isSingleRoom) {
                step2a.style.display = 'block';
                document.getElementById('capacity_single').value = seatCount;
            } else {
                step2b.style.display = 'block';
                document.getElementById('capacity_multiple').value = seatCount;
            }

            document.getElementById('custom-seat-count').value = '';
        }
    
        function validateAndSubmitRoomForm() {
            let floor, start, end, base, min, max;
    
            if (isSingleRoom) {
                floor = parseInt(document.querySelector('#room-form-step-sin #floor').value);
                start = parseInt(document.querySelector('#room-form-step-sin #room_start').value);
    
                base = (floor + 1) * 100;
                min = base + 1;
                max = base + 99;
    
                if (isNaN(start) || start < min || start > max) {
                    alert(`For floor ${floor === 0 ? 'Ground' : floor}, room number must be between ${min} and ${max}.`);
                    return;
                }
    
                document.querySelector('#room-form-step-sin form').submit();
            } else {
                floor = parseInt(document.querySelector('#room-form-step-mul #floor').value);
                start = parseInt(document.querySelector('#room-form-step-mul #room_start').value);
                end = parseInt(document.querySelector('#room-form-step-mul #room_end').value);
    
                base = (floor + 1) * 100;
                min = base + 1;
                max = base + 99;
    
                if (isNaN(start) || isNaN(end) || start < min || end > max || start > end) {
                    alert(`For floor ${floor === 0 ? 'Ground' : floor}, room numbers must be between ${min} and ${max}, and Start ≤ End.`);
                    return;
                }
    
                document.querySelector('#room-form-step-mul form').submit();
            }
        }
    </script>
    
</body>
</html>
