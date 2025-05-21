<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{URL::asset('css/student/mess/general.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
    
            <!-- Main Content -->
            <main class="main">
                <!-- Top Bar -->
                <header class="topbar">
                    <div class="calculate">
                        <form method="get" class="form" action="{{ route('mess.general') }}">
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
                                Select
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('student.dashboard') }}" method="get">
                        <button type="submit" class="logout-btn">Back</button>
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
                </section>

                <div class="table-container" id="pending">
                    <h2>General List</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Roll Number</th>
                                    <th>Present Days</th>
                                    <th>Absent Days</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($gen->isEmpty())
                                    <tr><td colspan="100%" style="text-align: center"><h4>---- No Dues ----</h4></td></tr>
                                @else
                                    @foreach ($gen as $index=>$g)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{$g->student_id}}</td>
                                            <td>{{$g->present_days}}</td>
                                            <td>{{$g->absent_days}}</td>
                                            <td>{{$g->amount}}</td>
                                            <td>
                                                @if ($g->paid)
                                                    <span style="color: green;">&#10004;</span> {{-- green tick --}}
                                                @else
                                                    <span style="color: red;">&#10008;</span> {{-- red cross --}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>

            </main>
        </div>
    </body>
</html>