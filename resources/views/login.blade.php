<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KB Piling Materials and Asset Tracker - Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Banner Header Bahagian Atas */
        .top-banner {
            width: 100%;
            max-width: 1100px;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .top-banner h1 {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }

        .top-banner p {
            font-size: 1.1rem;
            color: #e0e0e0;
            font-weight: 300;
            letter-spacing: 2px;
        }

        /* Container Kad Login */
        .login-container {
            margin-top: 50px;
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 35px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            text-align: center;
        }

        /* Kotak Tajuk Berwarna Merah/Jambu */
        .title-box {
            background: linear-gradient(135deg, #e91e63, #d81b60);
            color: white;
            padding: 18px 15px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.4;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(233, 30, 99, 0.3);
        }

        /* Form Controls */
        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #e91e63;
            outline: none;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.15);
        }

        /* Butang Login Biru */
        .btn-login {
            width: 100%;
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 13px;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.1s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #2563eb;
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        /* Error Alert */
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 15px;
            border-left: 4px solid #ef4444;
            text-align: left;
        }
    </style>
</head>
<body>

    <!-- Header Banner Utama -->
    <div class="top-banner">
        <h1>KB PILING MATERIALS AND ASSET TRACKER</h1>
        <p>INVENTORY & LOGISTICS MANAGEMENT SYSTEM</p>
    </div>

    <!-- Kad Borang Log Masuk -->
    <div class="login-container">
        <!-- Kotak Tajuk Merah -->
        <div class="title-box">
            KB PILING MATERIALS & ASSET TRACKER
        </div>

        <!-- Mesej Ralat Jika Ada -->
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Borang Log Masuk -->
        <form action="/login" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">USER EMAIL</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: admin@test.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">
                LOGIN
            </button>
        </form>
    </div>

</body>
</html>