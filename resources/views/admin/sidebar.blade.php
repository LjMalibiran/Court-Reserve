<style>
    /* --- SIDEBAR CSS --- */
    :root { 
        --primary-blue: #1557c0;
        --dark-blue: #002277;
    }
    
    .sidebar { width: 250px; background-color: var(--primary-blue); color: white; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; height: 100vh; }
    
    /* Reduced padding here to save space */
    .logo-container { padding: 20px 20px 10px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .logo-container img { max-width: 140px; } 
    
    /* Reduced top margin here */
    .menu-group { margin-top: 10px; padding: 0 15px; }
    .menu-title { font-size: 11px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; padding-left: 10px; }
    
    .nav-menu { list-style: none; padding: 0; margin: 0; }
    /* Reduced bottom margin and padding on links */
    .nav-menu li { margin-bottom: 2px; }
    .nav-menu a { display: flex; align-items: center; padding: 10px 15px; color: white; text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 8px; transition: 0.2s; }
    .nav-menu a i { width: 25px; font-size: 16px; }
    .nav-menu a:hover { background-color: rgba(255,255,255,0.1); }
    
    /* Active Menu Item */
    .nav-menu a.active { background-color: white; color: var(--primary-blue); font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    /* Submenu */
    .submenu { list-style: none; padding-left: 40px; margin-top: 5px; display: none; }
    .has-submenu.open .submenu { display: block; }
    .submenu a { padding: 6px 0; font-size: 13px; font-weight: normal; color: rgba(255,255,255,0.8); background: none !important; box-shadow: none !important; }
    .submenu a:hover { color: white; }
    .submenu a::before { content: "•"; margin-right: 10px; }
    
    .nav-menu .submenu a.active { color: white !important; font-weight: bold; }

    /* Profile & Logout */
    /* Reduced top padding */
    .user-profile-section { margin-top: auto; padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.1); }
    .profile-info { display: flex; align-items: center; gap: 15px; margin-bottom: 10px; }
    .profile-avatar { width: 35px; height: 35px; background-color: white; color: var(--primary-blue); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 16px; font-weight: bold; }
    .profile-name { font-size: 14px; font-weight: bold; }
    .profile-role { font-size: 11px; color: rgba(255,255,255,0.7); }
    
    .btn-logout { width: 100%; display: flex; align-items: center; gap: 10px; background: transparent; border: none; color: white; padding: 8px 0 0 0; cursor: pointer; font-size: 14px; font-weight: 500; transition: 0.2s; }
    .btn-logout:hover { color: #ffcccc; }
</style>

<!-- Sidebar HTML -->
<aside class="sidebar">
    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton Logo" style="filter: brightness(0) invert(1);"> 
    </div>
    
    <div class="menu-group">
        <div class="menu-title">Main</div>
        <ul class="nav-menu">
            <li>
                <a href="{{ url('/admin/dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-cells-large"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/qr-verification') }}" class="{{ request()->is('admin/qr-verification') ? 'active' : '' }}">
                    <i class="fa-solid fa-qrcode"></i> QR Verification
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/reservations') }}" class="{{ request()->is('admin/reservations') ? 'active' : '' }}">
                    <i class="fa-regular fa-calendar-check"></i> Reservations
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/walk-in') }}" class="{{ request()->is('admin/walk-in') ? 'active' : '' }}">
                    <i class="fa-solid fa-shoe-prints"></i> Walk-In
                </a>
            </li>
            
            <!-- Sales Report Dropdown -->
            <li class="has-submenu {{ request()->is('admin/sales-report') || request()->is('admin/sales/*') ? 'open' : '' }}">
                
                <!-- Added logic to keep the parent tab active/white when viewing subtabs -->
                <a href="{{ url('/admin/sales-report') }}" class="{{ request()->is('admin/sales-report') || request()->is('admin/sales/*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-simple"></i> Sales Report 
                    <i class="fa-solid fa-angle-down dropdown-icon" id="salesReportToggle" style="margin-left: auto; width: auto; font-size: 12px; padding: 5px; transition: transform 0.3s ease; {{ request()->is('admin/sales-report') || request()->is('admin/sales/*') ? 'transform: rotate(180deg);' : '' }}"></i>
                </a>
                
                <ul class="submenu">
                    <li><a href="{{ url('/admin/sales/transactions') }}" class="{{ request()->is('admin/sales/transactions') ? 'active' : '' }}">Transactions</a></li>
                    <li><a href="{{ url('/admin/sales/refunds') }}" class="{{ request()->is('admin/sales/refunds') ? 'active' : '' }}">Refunds</a></li>
                </ul>
            </li>

            <!-- Manage Staff (Moved outside of the Sales Report dropdown) -->
            <li>
                <a href="{{ route('admin.staff.create') }}">
                    <i class="fa-solid fa-users-gear"></i> Manage Staff
                </a>
            </li>
        </ul>
    </div>

    <div class="menu-group">
        <div class="menu-title">Support</div>
        <ul class="nav-menu">
            <li>
                <a href="{{ url('/admin/settings') }}" class="{{ request()->is('admin/settings') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Setting
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/help') }}" class="{{ request()->is('admin/help') ? 'active' : '' }}">
                    <i class="fa-regular fa-circle-question"></i> Help
                </a>
            </li>
        </ul>
    </div>

    <div class="user-profile-section">
        <a href="{{ url('/admin/profile') }}" class="profile-info" style="text-decoration: none; color: inherit; display: flex;">
            <div class="profile-avatar"><i class="fa-solid fa-user"></i></div>
            <div>
                <!-- This dynamically grabs the logged-in Admin's name! -->
                <div class="profile-name">{{ Auth::user() ? Auth::user()->name : 'Not Logged In' }}</div>
                <div class="profile-role">Admin</div>
            </div>
        </a>
        
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- Dropdown Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const salesToggle = document.getElementById('salesReportToggle');
        
        if(salesToggle) {
            const submenuParent = salesToggle.closest('.has-submenu');

            salesToggle.addEventListener('click', function(e) {
                e.preventDefault(); 
                e.stopPropagation(); 
                
                submenuParent.classList.toggle('open');
                
                if (submenuParent.classList.contains('open')) {
                    salesToggle.style.transform = 'rotate(180deg)';
                } else {
                    salesToggle.style.transform = 'rotate(0deg)';
                }
            });
        }
    });
</script>