<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Your OTP expires on {{\Carbon\Carbon::parse($data->otp_expired_at)->format('H:i:s, d F Y')}}</h2>
<p>{{ $data->otp }}</p>
</body>
</html>
