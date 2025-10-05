<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login - Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo.webp') }}">

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- External -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(90deg, #411072ff 50%, #131d88ff 50%);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-image img {
            max-width: 60%;
            max-height: 70%;
            object-fit: contain;
            border-radius: 20px;
            display: flex;
            align-items: left;
            justify-content: left;
            margin-top: 30px;
            margin-left: 300px;
        }

        .login-wrapper {
            display: flex;
            align-items: left;
            justify-content: left;
            margin-left: 40px;
            padding: 20px;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            margin-top: 40px;
            max-width: 430px;
            padding: 20px 20px;
            color: #040a09ff;
            max-height: 413px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header img {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
        }

        .login-header h5 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #080e13ff;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #040410ff;
            box-shadow: 0 0 0 0.2rem rgba(111, 61, 61, 0.25);
        }

        .btn-login {
            background-color: #411072ff;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #131d88ff;
            transform: translateY(-2px);
        }

        .btn-login:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .btn-forgot {
            color: #6f3d3d;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .btn-forgot:hover {
            color: #5d4037;
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            color: #d7ccc8;
            padding: 15px 0;
            font-size: 14px;
        }

        .alert-danger {
            font-size: 14px;
            padding: 8px 12px;
            margin-top: -5px;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating>.form-control {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-floating>label {
            color: #6c757d;
        }

        /* ------------------------
           RESPONSIVE STYLES ADDED
        -------------------------*/
        @media (max-width: 991px) {
            body {
                background: linear-gradient(180deg, #411072ff 50%, #131d88ff 50%);
            }

            .login-wrapper {
                flex-direction: column;
                margin-left: 0;
                align-items: center;
                justify-content: center;
            }

            .login-card {
                max-width: 90%;
                margin: 20px auto;
                max-height: none;
            }

            .login-image {
                margin: 20px auto;
                text-align: center;
            }

            .login-image img {
                max-width: 80%;
                margin: 0 auto;
                display: block;
            }

            .login-image h3,
            .login-image p {
                margin-left: 0 !important;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 15px;
            }

            .login-header h5 {
                font-size: 18px;
            }

            .btn-login {
                font-size: 14px;
                padding: 10px;
            }

            .login-image img {
                max-width: 90%;
            }

            .login-image h3 {
                font-size: 20px;
            }

            .login-image p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <!-- <img src="{{ asset('../assets/assets/images/logo.webp') }}" alt="Logo"> -->
                <h5>Welcome to Library Management System</h5>
                <p class="text-muted">Please sign in to continue</p>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="mb-1">Email address:</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" id="email"
                        placeholder="Enter your email..." required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" style="position: relative;">
                    <label for="password-input" class="mb-1">Password:</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        id="password-input" placeholder="Enter your password..." required style="padding-right: 40px;">
                    <span id="toggle-password" style="
                        position: absolute;
                        top: 65%;
                        right: 10px;
                        transform: translateY(-50%);
                        cursor: pointer;
                        font-size: 18px;
                        color: #6c757d;">
                        👁
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <script>
                    const togglePassword = document.getElementById('toggle-password');
                    const passwordInput = document.getElementById('password-input');

                    togglePassword.addEventListener('click', () => {
                        const type = passwordInput.type === 'password' ? 'text' : 'password';
                        passwordInput.type = type;
                        togglePassword.textContent = type === 'password' ? '👁' : '👁';
                    });
                </script>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-login text-white" id="loginBtn">
                        <span class="loading-spinner" id="loadingSpinner"></span>
                        <span id="loginText">Login</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="login-image">
            <img src='../assets/assets/images/logo.jpg' alt="Login Image">
            <h3 style="margin-left:320px; margin-bottom:10px;  margin-top:10px; ">Library Management System</h3>
            <p style="margin-left:320px;">Members, and More — All in One Place.</p>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Enter your email address and we'll send you a link to reset your password.</p>
                    <form id="forgotPasswordForm">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="resetEmail" placeholder="Enter your email"
                                required>
                            <label for="resetEmail">Email address</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="sendResetLink">
                        <span class="loading-spinner" id="resetSpinner" style="display: none;"></span>
                        Send Reset Link
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function () {
                const loginBtn = $('#loginBtn');
                const loadingSpinner = $('#loadingSpinner');
                const loginText = $('#loginText');

                loginBtn.prop('disabled', true);
                loadingSpinner.show();
                loginText.text('Logging in...');
            });

            $('#sendResetLink').on('click', function () {
                const email = $('#resetEmail').val();
                const resetBtn = $(this);
                const resetSpinner = $('#resetSpinner');

                if (!email) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Please enter your email address.'
                    });
                    return;
                }

                resetBtn.prop('disabled', true);
                resetSpinner.show();
                resetBtn.text('Sending...');

                setTimeout(function () {
                    resetBtn.prop('disabled', false);
                    resetSpinner.hide();
                    resetBtn.text('Send Reset Link');
                    $('#forgotPasswordModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Email Sent!',
                        text: 'If an account with that email exists, we have sent a password reset link.',
                        confirmButtonColor: '#6f3d3d'
                    });
                }, 2000);
            });

            setTimeout(function () {
                $(".alert").fadeOut('slow');
            }, 3000);

            $('#email').focus();
        });
    </script>

</body>

</html>
