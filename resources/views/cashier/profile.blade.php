<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; transition: background-color 0.3s; }
        
        .main-content { flex-grow: 1; overflow-y: auto; padding: 30px; }
        
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        .profile-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            max-width: 1000px;
        }

        .profile-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #e5e7eb;
        }

        .profile-card h3 {
            margin: 0 0 20px 0;
            color: var(--dark-blue);
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
        }

        /* Form Group */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 8px; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px 15px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: var(--text-main); box-sizing: border-box; outline: none; }
        .form-group input:disabled { background: #f8fafc; color: #9ca3af; cursor: not-allowed; }

        /* Toggle Switch */
        .toggle-row { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f1f5f9; }
        .toggle-row:last-child { border-bottom: none; }
        .toggle-label { display: flex; flex-direction: column; }
        .toggle-title { font-size: 15px; font-weight: 600; color: var(--text-main); }
        .toggle-desc { font-size: 12px; color: var(--text-muted); margin-top: 3px; }
        
        .switch { position: relative; display: inline-block; width: 50px; height: 26px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-blue); }
        input:checked + .slider:before { transform: translateX(24px); }

        body.dark-mode .profile-card { border-color: #334155; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        body.dark-mode .form-group input { background: #0f172a; border-color: #334155; color: #f8fafc; }
        body.dark-mode .profile-card h3, body.dark-mode .toggle-row, body.dark-mode form { border-color: #334155 !important; }
    </style>
</head>
<body>

    @include('cashier.sidebar')

    <main class="main-content">
        <header class="top-header">
            <h1>Profile</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                @include('partials.notif-bell')
            </div>
        </header>

        <div class="profile-container">
            
            <!-- Card 1: Profile Details & Picture -->
            <div class="profile-card">
                <h3><i class="fa-regular fa-address-card"></i> Profile Details</h3>
                
                <!-- Session Success Modal Handled Globally -->
                @if($errors->any())
                    <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ url('/cashier/profile/photo') }}" method="POST" enctype="multipart/form-data" style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid #f1f5f9;">
                    @csrf
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 2px solid var(--primary-blue);">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa-solid fa-user" style="font-size: 35px; color: #94a3b8;"></i>
                        @endif
                    </div>
                    <div>
                        <label for="profile_picture" style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 8px; font-weight: 500;">Change Profile Picture</label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="font-size: 12px; margin-bottom: 10px; color: var(--text-main);" required>
                        <button type="submit" style="display: block; background: var(--primary-blue); color: white; border: none; padding: 6px 15px; border-radius: 4px; font-size: 12px; cursor: pointer;">Upload Photo</button>
                    </div>
                </form>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="{{ Auth::user()->name ?? 'Cashier' }}" disabled>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" value="{{ Auth::user()->email ?? 'cashier@batangasbadminton.com' }}" disabled>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="Cashier" disabled>
                </div>
            </div>

            <!-- Card 2: Display Preferences (Moved from Settings) -->
            <div class="profile-card">
                <h3><i class="fa-solid fa-desktop"></i> Display Preferences</h3>
                
                <div class="toggle-row">
                    <div class="toggle-label">
                        <span class="toggle-title">Dark Mode</span>
                        <span class="toggle-desc">Switch the dashboard to a darker color theme</span>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="darkModeToggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

        </div>
    </main>

    <script>
        const toggle = document.getElementById('darkModeToggle');
        
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            toggle.checked = true;
        }

        toggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
    @include('partials.notif-script')
</body>
</html>


