<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Batangas Badminton Court Reserve</title>
    <!-- Using FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --sidebar-bg: #1c52b8;
            --main-bg: #f5f6fa;
            --text-dark: #1a202c;
            --text-light: #718096;
            --border-color: #e2e8f0;
            --primary-btn: #0044ff;
            --sidebar-text: #ffffff;
            --card-radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: var(--main-bg);
            color: var(--text-dark);
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            padding: 20px 0;
        }

        .logo-container {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo-container h2 {
            font-size: 14px;
            font-weight: 500;
        }
        
        .logo-container h2 strong {
            font-size: 24px;
            font-weight: 800;
            display: block;
            margin: -2px 0;
        }

        .logo-container h2 span {
            font-size: 12px;
            font-weight: 400;
            display: block;
            margin-top: 2px;
        }

        .nav-section {
            margin-bottom: 30px;
        }

        .nav-label {
            font-size: 12px;
            padding: 0 20px;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.7);
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
        }

        .nav-menu li a i {
            width: 24px;
            margin-right: 10px;
            font-size: 16px;
        }

        .nav-menu li.active a, .nav-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-profile-sidebar {
            margin-top: auto;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-profile-sidebar .avatar-mini {
            width: 36px;
            height: 36px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sidebar-bg);
        }

        .user-details .name {
            font-size: 14px;
            font-weight: 600;
            display: block;
        }

        .user-details .role {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
        }

        .logout-btn {
            padding: 0 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 30px 40px;
            overflow-y: auto;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .top-header h1 {
            color: #1a365d;
            font-size: 28px;
            font-weight: 700;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
            color: var(--text-dark);
            font-size: 14px;
        }

        .header-right i {
            font-size: 20px;
            color: var(--text-dark);
        }

        /* Card Styles */
        .card {
            background: #ffffff;
            border-radius: var(--card-radius);
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .card h3 {
            color: #1a365d;
            font-size: 18px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* Cashier Information Layout */
        .cashier-info-grid {
            display: flex;
            gap: 40px;
        }

        .profile-photo-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            width: 150px;
        }

        .avatar-large {
            width: 120px;
            height: 120px;
            background-color: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .avatar-large::before {
            content: "\f007";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 60px;
            color: #1c52b8;
            position: absolute;
            bottom: -10px;
        }
        
        .avatar-bg-circle {
            width: 100%;
            height: 100%;
            background-color: #dbeafe;
            border-radius: 50%;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #1c52b8;
            color: #1c52b8;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
        }

        .form-col {
            flex: 1;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 14px;
            color: var(--text-light);
        }

        .form-control {
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-btn);
        }

        /* Change Password Layout */
        .password-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .btn-primary {
            background-color: var(--primary-btn);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-container">
            <h2>Batangas<br><strong>Badminton</strong><br><span>Court Reserve</span></h2>
        </div>

        <div class="nav-section">
            <div class="nav-label">Main</div>
            <ul class="nav-menu">
                <li><a href="#"><i class="fa-solid fa-border-all"></i> Dashboard</a></li>
                <li><a href="#"><i class="fa-solid fa-qrcode"></i> QR Verification</a></li>
                <li><a href="#"><i class="fa-regular fa-calendar-check"></i> Reservations</a></li>
                <li><a href="#"><i class="fa-solid fa-person-walking"></i> Walk-In</a></li>
                <li><a href="#"><i class="fa-solid fa-chart-simple"></i> Sales Report <i class="fa-solid fa-chevron-down" style="margin-left:auto; font-size: 12px;"></i></a></li>
            </ul>
        </div>

        <div class="nav-section">
            <div class="nav-label">Support</div>
            <ul class="nav-menu">
                <li><a href="#"><i class="fa-solid fa-gear"></i> Setting</a></li>
                <li><a href="#"><i class="fa-regular fa-circle-question"></i> Help</a></li>
            </ul>
        </div>

        <div class="user-profile-sidebar">
            <div class="avatar-mini">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="user-details">
                <span class="name">Lj Malibiran</span>
                <span class="role">Admin</span>
            </div>
        </div>
        
        <a href="#" class="logout-btn">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-header">
            <h1>Profile</h1>
            <div class="header-right">
                <span class="date">Wednesday, February 25, 2026</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <!-- Cashier Information Card -->
        <div class="card">
            <h3>Cashier Information</h3>
            <div class="cashier-info-grid">
                <div class="profile-photo-col">
                    <div class="avatar-large">
                        <div class="avatar-bg-circle"></div>
                    </div>
                    <button class="btn-outline">Change Photo</button>
                </div>
                
                <div class="form-col">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card">
            <h3>Change Password</h3>
            <div class="password-grid">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control">
                </div>
            </div>
            <button class="btn-primary">Update Password</button>
        </div>
    </main>

</body>
</html>