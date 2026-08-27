@extends('layouts.app')

@section('title', 'Profile | Court Reserve')
@section('header_title', 'Profile')

@section('styles')
<style>
    .profile-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 20px; }
    .panel { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; }
    
    .panel h3 { margin-top: 0; color: var(--primary-blue); font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }

    /* Left Sidebar Profile */
    .profile-sidebar { text-align: center; }
    .profile-pic-container { position: relative; width: 150px; height: 150px; margin: 0 auto 20px auto; }
    .profile-pic { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid var(--light-blue); }
    .profile-pic-placeholder { width: 100%; height: 100%; border-radius: 50%; background: var(--primary-blue); color: white; display: flex; justify-content: center; align-items: center; font-size: 60px; font-weight: bold; border: 4px solid var(--light-blue); }
    
    .upload-btn { position: absolute; bottom: 0; right: 10px; background: var(--primary-blue); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.2s; }
    .upload-btn:hover { background: #002299; transform: scale(1.05); }

    .user-name { font-size: 22px; color: var(--text-dark); margin: 0 0 5px 0; font-weight: bold; }
    .user-role { color: var(--text-gray); margin: 0 0 20px 0; font-size: 14px; text-transform: capitalize; }
    
    /* Stats */
    .stats-container { display: flex; justify-content: space-around; border-top: 1px solid #eee; padding-top: 20px; }
    .stat-box h4 { margin: 0; font-size: 20px; color: var(--primary-blue); }
    .stat-box p { margin: 5px 0 0 0; font-size: 12px; color: var(--text-gray); }

    /* Forms */
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; color: var(--text-gray); margin-bottom: 8px; font-weight: 500; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box; outline: none; transition: 0.2s; }
    .form-control:focus { border-color: var(--primary-blue); }
    
    .btn-submit { background: var(--primary-blue); color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.2s; float: right; }
    .btn-submit:hover { background: #002299; }

    /* 2FA Toggle */
    .toggle-container { display: flex; justify-content: space-between; align-items: center; padding: 20px; background: #f9f9f9; border-radius: 8px; border: 1px solid #eee; margin-bottom: 20px; }
    .toggle-info h4 { margin: 0 0 5px 0; color: var(--text-dark); }
    .toggle-info p { margin: 0; font-size: 13px; color: var(--text-gray); }
    
    /* Toggle Switch */
    .switch { position: relative; display: inline-block; width: 50px; height: 26px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: var(--success-green); }
    input:checked + .slider:before { transform: translateX(24px); }

    /* Help Center Unclickable */
    .help-center-btn { display: flex; align-items: center; gap: 15px; padding: 15px 20px; background: #f9f9f9; border-radius: 8px; color: var(--text-dark); text-decoration: none; font-weight: bold; border: 1px solid #eee; margin-top: 20px; pointer-events: none; opacity: 0.6; }
    .help-center-btn i { color: var(--primary-blue); font-size: 20px; }

    /* Alerts */
    .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="profile-grid">
    <!-- Left Sidebar: Overview & Avatar -->
    <div class="panel profile-sidebar" style="display: flex; flex-direction: column;">
        <form id="avatarForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="profile-pic-container">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="profile-pic" alt="Profile">
                @else
                    <div class="profile-pic-placeholder">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <label for="profile_picture" class="upload-btn">
                    <i class="fa-solid fa-camera"></i>
                </label>
                <input type="file" id="profile_picture" name="profile_picture" style="display: none;" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                
                <!-- Hidden inputs to satisfy required validation on update -->
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                <input type="hidden" name="phone_number" value="{{ Auth::user()->phone_number }}">
            </div>
        </form>

        <h2 class="user-name">{{ Auth::user()->name }}</h2>
        <p class="user-role">{{ Auth::user()->role }} Account</p>

        <div class="stats-container">
            <div class="stat-box">
                <h4>{{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'completed')->count() }}</h4>
                <p>Completed</p>
            </div>
            <div class="stat-box">
                <h4>{{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'pending')->count() }}</h4>
                <p>Pending</p>
            </div>
        </div>

        <!-- Spacer to push help center & logout to the bottom -->
        <div style="flex-grow: 1;"></div>

        <a href="#" class="help-center-btn">
            <i class="fa-solid fa-circle-question"></i>
            <div>
                <span style="display: block; font-size: 14px;">Help Center</span>
                <span style="font-size: 11px; color: var(--text-gray); font-weight: normal;">Currently Unavailable</span>
            </div>
        </a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: 15px;">
            @csrf
            <button type="submit" class="btn-outline-red" style="width: 100%;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
        </form>
    </div>

    <!-- Right Side: Forms -->
    <div>
        <!-- Personal Information -->
        <div class="panel" style="margin-bottom: 25px;">
            <h3>Personal Information</h3>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>

                <div class="form-group">
                    <label>Phone Number <span style="font-size: 11px; color: #999;">(Required for 2FA)</span></label>
                    <input type="text" name="phone_number" class="form-control" value="{{ Auth::user()->phone_number }}" placeholder="e.g. 09123456789">
                </div>

                <div style="overflow: hidden;">
                    <button type="submit" class="btn-submit">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- 2FA & Password -->
        <div class="panel">
            <h3>Security Settings</h3>
            
            <div class="toggle-container">
                <div class="toggle-info">
                    <h4>Two-Factor Authentication (2FA)</h4>
                    <p>When enabled, you'll need to verify your identity with an SMS OTP when logging in.</p>
                </div>
                <form action="{{ route('profile.toggle-2fa') }}" method="POST" id="toggle2faForm">
                    @csrf
                    <label class="switch">
                        <input type="checkbox" onchange="document.getElementById('toggle2faForm').submit()" {{ Auth::user()->two_factor_enabled ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </form>
            </div>

            <h4 style="margin: 0 0 15px 0; color: var(--primary-blue);">Change Password</h4>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div style="overflow: hidden;">
                    <button type="submit" class="btn-submit">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection