<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="
        {{URL::asset('css/login.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/header.css')}}">
        <title>Hostel Management System</title>

    </head>
    <body>
        
       <header>
            <img src="https://www.tezu.ernet.in/images/tulogo.png" alt="tezu_logo">
            <h1>Hostel Management System</h1>
            <a href="{{route('welcome')}}">Back</a>
       </header>
       
       <div class="container">
        <div class="login-container">
            <h2>Admin Login</h2>
            <form action="{{route("admin.login.post")}}" method="POST">
                @csrf

                @if($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>
       </div>
</body>
</html>
