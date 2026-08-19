<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court Reserve | Batangas Badminton Center</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div style="display: flex; min-height: 100vh;">
        
        <!-- 1. This summons your custom sidebar -->
        @include('admin.sidebar')
        
        <!-- 2. This is where your Dashboard or QR Verification content loads -->
        <main style="flex: 1; padding: 40px; background-color: #f4f7f6;">
            @yield('content')
        </main>
        
    </div>
</body>
</html>