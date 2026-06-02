<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #fa709a;
            line-height: 1;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 32px;
            color: #2d3748;
            margin-bottom: 16px;
        }
        p {
            font-size: 18px;
            color: #718096;
            margin-bottom: 32px;
            line-height: 1.6;
        }
        .btn {
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            background: #fa709a;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn svg {
            width: 20px;
            height: 20px;
        }
        .btn:hover {
            background: #e85d87;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(250, 112, 154, 0.3);
        }
        .icon {
            margin-bottom: 20px;
        }
        .icon svg {
            width: 80px;
            height: 80px;
            color: #fa709a;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div class="error-code">500</div>
        <h1>Terjadi Kesalahan Server</h1>
        <p>Maaf, terjadi kesalahan pada server. Tim kami telah diberitahu dan sedang memperbaikinya. Silakan coba lagi nanti.</p>
        <a href="/" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
