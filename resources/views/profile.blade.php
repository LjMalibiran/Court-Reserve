<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Court Reserve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary-blue: #0033cc; 
            --bg-gray: #f8f9fa; 
            --text-gray: #777; 
            --text-dark: #333;
            --border-color: #eaeaea;
        }
        
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: var(--bg-gray); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar { width: 250px; background-color: white; border-right: 1px solid #ddd; display: flex; flex-direction: column; flex-shrink: 0; }
        .logo-container { padding: 20px; text-align: center; border-bottom: 1px solid #ddd; }
        .nav-menu { list-style: none; padding: 0; margin: 20px 0; flex-grow: 1; }
        .nav-menu a { display: flex; align-items: center; padding: 15px 30px; color: var(--primary-blue); text-decoration: none; font-size: 16px; font-weight: 500; transition: 0.2s; }
        .nav-menu a i { margin-right: 15px; width: 20px; text-align: center; font-size: 20px; }
        .nav-menu a:hover, .nav-menu a.active { background-color: #e6edff; border-left: 4px solid var(--primary-blue); }

        /* Main Content */
        .main-content { 
            flex-grow: 1; 
            padding: 40px; 
            overflow-y: auto; 
            background-image: url("{{ asset('images/auth-bg.jpg') }}");
            background-size: cover;
            background-position: bottom right;
            background-repeat: no-repeat;
        }
        
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .top-header h1 { color: var(--primary-blue); margin: 0; font-size: 32px; }

        /* Profile Layout Grid */
        .profile-grid { display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 25px; align-items: start; }
        .panel { background: rgba(255, 255, 255, 0.95); border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--border-color); }
        .panel h3 { color: var(--primary-blue); margin-top: 0; font-size: 18px; margin-bottom: 25px; }

        /* Column 1: User Summary */
        .user-summary { text-align: center; }
        .avatar-container { position: relative; width: 140px; height: 140px; margin: 0 auto 20px auto; }
        .avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .camera-btn { position: absolute; bottom: 5px; right: 5px; background: white; color: var(--primary-blue); width: 35px; height: 35px; border-radius: 50%; display: flex; justify-content: center; align-items: center; border: 1px solid var(--border-color); cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: 0.2s; }
        .camera-btn:hover { background: var(--primary-blue); color: white; }
        .user-name { color: var(--primary-blue); font-size: 28px; font-weight: bold; margin: 0 0 5px 0; }
        .user-email { color: #999; font-size: 14px; margin: 0; }

        /* Column 2: Account Info */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: var(--primary-blue); font-weight: 600; font-size: 14px; }
        .input-with-action { position: relative; }
        .input-with-action input { width: 100%; padding: 14px 15px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 15px; box-sizing: border-box; color: var(--text-gray); background-color: #fafafa; transition: 0.3s; }
        
        /* Styles for when input is being edited */
        .input-with-action input:not([readonly]) { background-color: white; border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(0, 51, 204, 0.1); color: var(--text-dark); outline: none; }
        
        .input-with-action .edit-btn { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--primary-blue); font-weight: bold; text-decoration: none; font-size: 14px; cursor: pointer; background: none; border: none; padding: 5px; }

        /* Column 3: Security & Other */
        .action-btn { display: flex; align-items: center; padding: 15px 20px; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 12px; color: var(--primary-blue); text-decoration: none; font-weight: 600; background: white; transition: 0.2s; cursor: pointer; width: 100%; box-sizing: border-box; text-align: left; }
        .action-btn:hover { border-color: var(--primary-blue); background: #f0f4ff; }
        .action-btn i { font-size: 18px; margin-right: 15px; width: 20px; text-align: center; }
        
        @media (max-width: 1200px) {
            .profile-grid { grid-template-columns: 1fr 1fr; }
            .profile-grid > div:nth-child(3) { grid-column: span 2; }
        }
        /* Mobile App Navigation & Layout Override */
        @media (max-width: 768px) {
            body { 
                flex-direction: column; 
                overflow-x: hidden;
                overflow-y: auto;
            }

            /* --- FIX 1: THE WELCOME CARD OVERLAP --- */
            .welcome-card { 
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                gap: 20px !important;
            }
            .sport-buttons {
                width: 100%;
                justify-content: center;
                gap: 15px;
            }
            .sport-btn {
                flex: 1; /* Makes the buttons equal width */
                padding: 15px 10px;
            }

            /* --- FIX 2: THE RESERVATION GRID OVERLAP --- */
            .reservations-grid { 
                display: flex !important;
                flex-direction: column !important;
                gap: 20px !important;
            }
            
            /* Ensure everything respects the boundaries of the phone screen */
            .panel, .card { 
                width: 100% !important; 
                box-sizing: border-box !important; 
                margin: 0 !important; /* Added to keep profile panels from bleeding over */
            }

            /* --- NEW: FIX 3: PROFILE TAB OVERLAP & TEXT BOXES --- */
            .profile-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 20px !important;
            }

            .input-with-action input {
                /* Adds a cushion inside the box so text doesn't hide under the Edit button */
                padding-right: 60px !important; 
            }
            
            /* --- FIX 4: THE BOTTOM NAVBAR --- */
            .sidebar {
                position: fixed !important; 
                bottom: 0 !important; 
                left: 0 !important; 
                width: 100% !important; 
                height: 70px !important;
                flex-direction: row !important; 
                border-right: none !important; 
                border-top: 1px solid #ddd !important;
                z-index: 1000 !important; 
                padding: 0 !important;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.05) !important;
                background-color: white !important;
            }
            
            .logo-container, .logout-container { 
                display: none !important; 
            }
            
            .nav-menu { 
                display: flex !important; 
                flex-direction: row !important; 
                margin: 0 !important; 
                width: 100% !important; 
                justify-content: space-around !important; 
                align-items: center !important; 
            }
            
            .nav-menu a { 
                padding: 10px !important; 
                flex-direction: column !important; 
                font-size: 11px !important; 
                border-left: none !important; 
                color: #777 !important;
            }
            
            .nav-menu a i { 
                margin-right: 0 !important; 
                margin-bottom: 4px !important; 
                font-size: 20px !important; 
            }
            
            .nav-menu a:hover, .nav-menu a.active { 
                border-left: none !important; 
                background: transparent !important; 
                color: var(--primary-blue) !important; 
            }

            /* --- FIX 4: LESSEN THE MARGINS --- */
            .dashboard-container {
                padding: 0 !important; /* Removes the 40px desktop padding */
            }
            
            .top-header {
                padding: 10px 0 20px 0 !important; /* Aligns the header with the new margins */
            }

            .main-content { 
                /* Reduced from 20px to 15px to give cards maximum width */
                padding: 15px !important;
                padding-bottom: 120px !important; 
            }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 160px; height: auto;">
        </div>
        <ul class="nav-menu">
            <li><a href="{{ url('/home') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="{{ route('reservation.index') }}"><i class="fa-regular fa-calendar-plus"></i> Reservation</a></li>
            <li><a href="{{ route('history.index') }}"><i class="fa-solid fa-clock-rotate-left"></i> History</a></li>
            <li><a href="{{ route('profile.index') }}" class="active"><i class="fa-regular fa-user"></i> Profile</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h1>Profile</h1>
            <i class="fa-regular fa-bell" style="font-size: 24px; color: var(--primary-blue); cursor: pointer;" onclick="alert('You have no new notifications.')"></i>
        </header>

        <div class="profile-grid">
            
            <div class="panel user-summary">
                <div class="avatar-container">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest User') }}&background=4285F4&color=fff&size=150" alt="Avatar" class="avatar" id="profileImage">
                    <button class="camera-btn" onclick="document.getElementById('fileInput').click()" title="Change Photo">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                    <input type="file" id="fileInput" style="display: none;" accept="image/*" onchange="alert('In a live app, this would upload your new photo to the database!')">
                </div>
                <h2 class="user-name">{{ auth()->user()->name ?? 'Guest User' }}</h2>
                <p class="user-email">{{ auth()->user()->email ?? 'guest@example.com' }}</p>
            </div>

            <div class="panel">
                <h3>Account Information</h3>
                
                <form action="#" method="POST" id="profileForm">
                    @csrf
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <div class="input-with-action">
                            <input type="text" id="inputName" name="name" value="{{ auth()->user()->name ?? 'Guest User' }}" readonly>
                            <button type="button" class="edit-btn" onclick="toggleEdit('inputName', this)">Edit</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="input-with-action">
                            <input type="email" id="inputEmail" name="email" value="{{ auth()->user()->email ?? 'guest@example.com' }}" readonly>
                            <button type="button" class="edit-btn" onclick="toggleEdit('inputEmail', this)">Edit</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <div class="input-with-action">
                            <input type="text" id="inputPhone" name="phone" value="{{ auth()->user()->phone ?? '09123456789' }}" readonly>
                            <button type="button" class="edit-btn" onclick="toggleEdit('inputPhone', this)">Edit</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="panel" style="background: transparent; border: none; box-shadow: none; padding: 0;">
                
                <div class="panel" style="margin-bottom: 25px;">
                    <h3>Security</h3>
                    <button class="action-btn" onclick="alert('Redirecting to Password Manager...')">
                        <i class="fa-solid fa-shield-halved"></i> Password Manager
                    </button>
                    <button class="action-btn" onclick="alert('Redirecting to Two-Factor Authentication settings...')">
                        <i class="fa-solid fa-mobile-screen"></i> Two-Factor Auth
                    </button>
                </div>

                <div class="panel">
                    <h3>Other</h3>
                    <button class="action-btn" onclick="alert('Opening Help Center module...')">
                        <i class="fa-regular fa-circle-question"></i> Help Center
                    </button>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="action-btn" style="color: #dc3545; border-color: #f8d7da;">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </main>

    <script>
        function toggleEdit(inputId, btnElement) {
            const inputField = document.getElementById(inputId);
            
            // If it is currently locked (readonly), unlock it
            if (inputField.hasAttribute('readonly')) {
                inputField.removeAttribute('readonly');
                inputField.focus();
                
                // Put cursor at the end of the text
                const val = inputField.value;
                inputField.value = '';
                inputField.value = val;
                
                btnElement.innerText = 'Save';
                btnElement.style.color = '#28a745'; // Turn text green to indicate save action
            } 
            // If it is currently unlocked, "Save" it
            else {
                inputField.setAttribute('readonly', 'readonly');
                btnElement.innerText = 'Edit';
                btnElement.style.color = 'var(--primary-blue)'; // Return to normal blue
                
                // Show a confirmation to the user
                alert('Success! In a live database, "' + inputField.value + '" is now saved.');
            }
        }
    </script>
</body>
</html>