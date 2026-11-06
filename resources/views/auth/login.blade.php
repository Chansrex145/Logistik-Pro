<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Transportasi</title>
    <!-- SB Admin 2 -->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; font-family: 'Nunito', sans-serif; background-color: #ffffff; }
        .split-container { display: flex; flex-wrap: wrap; height: 100vh; }
        .left-side {
            flex: 1 1 50%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            position: relative;
            border-right: 1px solid #e3e6f0;
        }
        .left-side img {
            width: 100%;
            max-width: 90%;
            height: 100%;
            max-height: 65vh;
            object-fit: cover; /* Cover is better for real photos */
            border-radius: 12px; /* Add a nice border radius */
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .left-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 5px;
            line-height: 1.2;
        }
        .left-subtitle {
            font-size: 1.1rem;
            color: #444;
            font-weight: 700;
            letter-spacing: 2px;
        }
        .right-side {
            flex: 1 1 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            color: #ffffff;
        }
        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }
        .avatar-icon {
            font-size: 5rem;
            color: #ffffff;
            margin-bottom: 1rem;
        }
        .login-title {
            font-size: 2rem;
            font-weight: 800;
            color: #ffffff;
        }
        .login-subtitle {
            color: #eaecf4;
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
        }
        .custom-btn {
            background-color: #ffffff;
            color: #4e73df;
            border-radius: 6px;
            padding: 12px;
            font-weight: 700;
            transition: all 0.2s;
            border: none;
        }
        .custom-btn:hover {
            background-color: #eaecf4;
            color: #224abe;
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon-wrapper input {
            padding-right: 40px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.2);
            height: 48px;
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        .input-icon-wrapper input::placeholder {
            color: #d1d3e2;
        }
        .input-icon-wrapper input:focus {
            box-shadow: none;
            border-color: #ffffff;
            background-color: rgba(255,255,255,0.2);
            color: #fff;
        }
        .input-icon-wrapper .icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #eaecf4;
        }
        .form-label {
            font-weight: 700;
            color: #ffffff;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }
        @media (max-width: 768px) {
            .left-side { display: none; }
            .right-side { flex: 1 1 100%; }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Left Side (Image & Title) -->
        <div class="left-side">
            <div>
                <div class="left-title">SISTEM MANAJEMEN<br>TRANSPORTASI</div>
                <div class="left-subtitle">PT SUNAN GROUP</div>
            </div>
            <img src="{{ asset('images/login_bg.jpeg') }}" alt="Logistics Truck Illustration">
        </div>

        <!-- Right Side (Login Form) -->
        <div class="right-side">
            <div class="login-wrapper text-center">
                <i class="fas fa-user-circle avatar-icon"></i>
                <h1 class="login-title">Login</h1>
                <p class="login-subtitle">Silakan masuk ke akun Anda</p>

                @if(session('error'))
                    <div class="alert alert-danger text-left">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="/login" class="text-left">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label class="form-label">Email</label>
                        <div class="input-icon-wrapper">
                            <input type="email" class="form-control" name="email" placeholder="Masukkan email Anda" required>
                            <i class="far fa-envelope icon"></i>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-icon-wrapper">
                            <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi Anda" required>
                            <i class="far fa-eye-slash icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn custom-btn btn-block mt-4">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>