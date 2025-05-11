<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/student/dashboard.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/layout.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>attender</h2>
                <nav>
                    
                    <a href="#">Settings</a>
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
                    <h1>Dashboard Overview</h1>
                    <p>This is your atttender dashboard.</p>

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
            </main>
        </div>
    
        
    </body>
    