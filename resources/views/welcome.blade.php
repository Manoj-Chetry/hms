<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System</title>
    <link rel="stylesheet" href="{{ URL::asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/header.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <img src="https://www.tezu.ernet.in/images/tulogo.png" alt="Tezpur University Logo">
        <h1>Hostel Management System</h1>
        <p>Tezpur University</p>
    </header>

    <main>
        <div class="container">
            <h2>Please login to continue</h2>

            @if(session('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
            @endif

            <div class="button_area">
                <a href="{{ route('student.login.form') }}">Student Login</a>
                <a href="{{ route('staff.login.form') }}">Staff Login</a>
                <a href="{{ route('admin.login.form') }}">Admin Login</a>
            </div>
        </div>
    </main>
</body>
</html>
