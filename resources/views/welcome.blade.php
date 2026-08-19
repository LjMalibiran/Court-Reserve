<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court Reserve | Batangas Badminton Center</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=1.0">
</head>
<body>
    
    <header class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton Logo" height="50">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </nav>
        <a href="{{ url('/login') }}" style="text-decoration: none;">
            <button class="btn-signin">Sign In</button>
        </a>
    </header>

    <main class="hero-section">
        <div class="hero-content">
            <p class="subtitle">Welcome to</p>
            <h1><span class="text-blue">Batangas Badminton</span><br><span class="text-gray">Court Reserve</span></h1>
            <p class="description">Book your badminton or pickleball court in just a few clicks.</p>
            <a href="{{ url('/login') }}" style="text-decoration: none;">
                <button class="btn-primary">Reserve Now &rarr;</button>
            </a>
        </div>
    </main>

</body>
</html>