<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: white;
            padding: 2rem 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 1rem;
            text-align: center;
            color: #111827;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
        }

        input[type="email"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .btn {
            background: #2563eb;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            width: 100%;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #1e40af;
        }

        .error, .status {
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .error {
            color: #dc2626;
        }

        .status {
            color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Forgot Password</h2>

        @if (session('status'))
            <div class="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('email'))
            <div class="error">{{ $errors->first('email') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label for="email">Enter your email address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required>

            <button type="submit" class="btn">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
