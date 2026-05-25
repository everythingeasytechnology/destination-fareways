<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Destination Fareways</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6.4 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #07111F 0%, #030811 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Abstract glowing particles in background */
        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(29, 78, 216, 0.15) 0%, transparent 70%);
            top: -10%;
            left: -10%;
            z-index: 0;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            bottom: -10%;
            right: -10%;
            z-index: 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 2.75rem 2.25rem;
            width: 100%;
            max-width: 430px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            z-index: 1;
            position: relative;
        }

        .brand-logo i {
            font-size: 2.25rem;
            color: #F59E0B;
            text-shadow: 0 0 15px rgba(245, 158, 11, 0.4);
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            color: #FFFFFF;
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.01em;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 0.75rem 1.25rem;
            color: #FFFFFF;
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38BDF8;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.25);
            color: #FFFFFF;
        }

        .btn-login {
            background-color: #F59E0B;
            border-color: #F59E0B;
            color: #07111F;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            font-size: 0.88rem;
            letter-spacing: 0.05em;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background-color: #d97706;
            border-color: #d97706;
            color: #07111F;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.35);
        }

        .alert-premium {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            font-size: 0.88rem;
            border-radius: 10px;
        }
        
        .alert-success-premium {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #a7f3d0;
            font-size: 0.88rem;
            border-radius: 10px;
        }

        .copyright-text {
            position: absolute;
            bottom: 20px;
            color: rgba(255, 255, 255, 0.25);
            font-size: 0.78rem;
            z-index: 1;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <div class="brand-logo mb-2">
                <i class="fa-solid fa-plane-departure"></i>
            </div>
            <h1 class="brand-title mb-1">Destination Fareways</h1>
            <p class="text-white-50 small mb-0">Premium Administrative Portal</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-premium py-2 px-3 mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success-premium py-2 px-3 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-premium py-2 px-3 mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="position-relative">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="admin@destinationfareways.com" required autofocus autocomplete="email">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Security Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••••••" required autocomplete="current-password">
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input bg-transparent border-secondary" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-white-50" for="remember">
                        Remember Session
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="fa-solid fa-shield-halved me-2"></i> Authenticate
            </button>
        </form>
    </div>

    <div class="copyright-text text-center">
        <span>&copy; {{ date('Y') }} Destination Fareways. Designed by EverythingEasy.</span>
    </div>

</body>
</html>
