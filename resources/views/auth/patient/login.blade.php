<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Log In | MediCore Laboratory System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme for labs and healthcare." />
    <meta name="author" content="Zoyothemes" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <style>
        body {
            background: linear-gradient(135deg, #e6f7fa 0%, #dde8ef 100%);
            min-height: 100vh;
        }
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            gap: 3rem;
        }
        .login-card {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 10px 32px rgba(44, 62, 80, 0.11);
            padding: 3rem 2.5rem;
            max-width: 420px;
            width: 100%;
        }
        .brand-logo {
            height: 55px;
            margin-bottom: 1.2rem;
        }
        .auth-title-section h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #234276;
        }
        .auth-title-section p {
            color: #54718b;
            margin-bottom: 0.5rem;
        }
        .form-control, .btn {
            border-radius: 0.7rem !important;
        }
        .btn-primary {
            background: linear-gradient(90deg, #3367d6 0%, #47c0f5 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #47c0f5 0%, #3367d6 100%);
        }
        .role-btns .btn {
            background: #e5e5f7;
            color: #234276;
            border: none;
            transition: background 0.2s;
        }
        .role-btns .btn:hover {
            background: #234276;
            color: #fff;
        }
        .side-image-container {
            position: relative;
            max-width: 450px;
            width: 100%;
        }
        .side-image {
            width: 100%;
            border-radius: 2rem;
            box-shadow: 0 12px 32px rgba(44, 62, 80, 0.13);
            object-fit: cover;
            min-height: 480px;
            background: #234276;
        }
        .image-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border-radius: 2rem;
            background: linear-gradient(110deg, rgba(33,62,129,0.65) 25%, rgba(0,0,0,0.20) 100%);
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-end;
            padding: 2.5rem;
        }
        .image-overlay h2 {
            color: #fff;
            font-size: 2.1rem;
            font-weight: bold;
            margin-bottom: 0.4rem;
            letter-spacing: 1.2px;
        }
        .image-overlay p {
            color: #e7f6fa;
            font-size: 1.1rem;
            font-weight: 400;
        }
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                gap: 2rem;
                padding: 2rem 0;
            }
            .side-image-container {
                max-width: 98vw;
            }
            .login-card {
                max-width: 95vw;
            }
        }
    </style>
</head>
<body>
    <div class="position-absolute top-0 end-0 p-3">
        <a href="{{ route('login') }}" class="btn btn-light btn-lg text-dark border shadow-sm">
            Login as Staff
        </a>
    </div>
    <div class="login-container">
        <div class="login-card">
            <div class="text-center">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="MediCore Logo" class="brand-logo" />
            </div>
            <div class="auth-title-section text-center mb-3">
                <h3>Welcome back</h3>
                <p>Sign in to continue to MediCore</p>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

    <form id="loginForm" action="{{ route('patient.login.post') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="login_id" class="form-label">User ID or Phone</label>
        <input class="form-control" type="text" id="login_id" name="login_id" required placeholder="Enter User ID or Phone" value="{{ old('login_id') }}">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <input class="form-control" type="password" name="password" id="password" required placeholder="Enter your password">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
        </div>
    </div>
    <div class="d-grid mb-2">
        <button class="btn btn-primary btn-lg" type="submit">Log In</button>
    </div>
</form>


        </div>
        <div class="side-image-container">
            <!-- Use your uploaded image or SVG here -->
            <img src="{{ asset('assets/images/auth-images.png') }}" class="side-image" alt="MediCore Lab" />
            <div class="image-overlay">
                <h2>MediCore Laboratory</h2>
                <p>Advanced Diagnostic Testing.<br>Secure. Trusted. Accurate.<br>
                <span style="opacity: 0.75; font-size: 0.97em;">Empowering Healthcare With Innovation</span>
                </p>
            </div>
        </div>
    </div>
    <script>
        function fillLogin(email, password) {
            document.getElementById('emailaddress').value = email;
            document.getElementById('password').value = password;
            document.getElementById('loginForm').submit();
        }
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordInput = document.getElementById('password');
            var eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
