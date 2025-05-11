<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{URL::asset('css/header.css')}}">
        <link rel="stylesheet" href="
        {{URL::asset('css/welcome.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        
       <header>
        <img src="https://www.tezu.ernet.in/images/tulogo.png" alt="tezu_logo">
        <h1>Hostel Management System</h1>
        <p>Tezpur University</p>
       </header>



       <main>
        <div class="container">
            <h1>Please Login to our website</h1>

            @if(session('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
            @endif

            <div class="button_area">
                <a href="{{route("student.login.form")}}">Student Login</a>
                <a href="{{route("staff.login.form")}}">Staff Login</a>
                <a href="{{route("admin.login.form")}}">Admin Login</a>
            </div>
        </div>
       </main>
    </body>
</html>
