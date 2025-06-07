<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            color: #2563eb;
            margin: 20px 0;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
        }
        .warning {
            color: #dc2626;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password Verification</h2>
        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        <p>Berikut adalah kode verifikasi Anda:</p>
        
        <div class="code">{{ $code }}</div>
        
        <p>Kode ini akan kadaluarsa dalam 15 menit.</p>
        
        <p class="warning">Jika Anda tidak meminta reset password, abaikan email ini.</p>
        
        <p>Terima kasih,<br>Tim Cahaya</p>
    </div>
</body>
</html> 