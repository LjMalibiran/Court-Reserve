<style>
    /* --- SIDEBAR CSS --- */
    :root { 
        --primary-blue: #1557c0;
        --dark-blue: #002277;
    }
    
    .sidebar { width: 250px; background-color: var(--primary-blue); color: white; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; height: 100vh; }
    .logo-container { padding: 30px 20px 20px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .logo-container img { max-width: 150px; }
    
    .menu-group { margin-top: 20px; padding: 0 15px; }
    .menu-title { font-size: 11px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; padding-left: 10px; }
    .nav-menu { list-style: none; padding: 0; margin: 0; }
    .nav-menu li { margin-bottom: 5px; }
    .nav-menu a { display: flex; align-items: center; padding: 12px 15px; color: white; text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 8px; transition: 0.2s; }
    .nav-menu a i { width: 25px; font-size: 16px; }
    .nav-menu a:hover { background-color: rgba(255,255,255,0.1); }
    
    /* Active Menu Item */
    .nav-menu a.active { background-color: white; color: var(--primary-blue); font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    /* Submenu */
    .submenu { list-style: none; padding-left: 40px; margin-top: 5px; display: none; }
    .has-submenu.open .submenu { display: block; }
    .submenu a { padding: 8px 0; font-size: 13px; font-weight: normal; color: rgba(255,255,255,0.8); background: none !important; box-shadow: none !important; }
    .submenu a:hover { color: white; }
    .submenu a::before { content: "•"; margin-right: 10px; }
    
    /* FIXED: Prevent Submenu active links from turning blue on a blue background */
    .nav-menu .submenu a.active { color: white !important; font-weight: bold; }

    /* Profile & Logout */
    .user-profile-section { margin-top: auto; padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
    .profile-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
    .profile-avatar { width: 40px; height: 40px; background-color: white; color: var(--primary-blue); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 18px; font-weight: bold; }
    .profile-name { font-size: 14px; font-weight: bold; }
    .profile-role { font-size: 11px; color: rgba(255,255,255,0.7); }
    
    .btn-logout { width: 100%; display: flex; align-items: center; gap: 10px; background: transparent; border: none; color: white; padding: 10px 0; cursor: pointer; font-size: 14px; font-weight: 500; transition: 0.2s; }
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
                <a href="{{ url('/cashier/dashboard') }}" class="{{ request()->is('cashier/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-cells-large"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ url('/cashier/qr-verification') }}" class="{{ request()->is('cashier/qr-verification') ? 'active' : '' }}">
                    <i class="fa-solid fa-qrcode"></i> QR Verification
                </a>
            </li>
            <li>
                <a href="{{ url('/cashier/reservations') }}" class="{{ request()->is('cashier/reservations') ? 'active' : '' }}">
                    <i class="fa-regular fa-calendar-check"></i> Reservations
                </a>
            </li>
            <li>
                <a href="{{ url('/cashier/walk-in') }}" class="{{ request()->is('cashier/walk-in') ? 'active' : '' }}">
                    <i class="fa-solid fa-shoe-prints"></i> Walk-In
                </a>
            </li>
            
            <!-- Sales Report Dropdown -->
            <li class="has-submenu {{ request()->is('cashier/sales-report') || request()->is('cashier/sales/*') ? 'open' : '' }}">
                
                <!-- Added logic to keep the parent tab active/white when viewing subtabs -->
                <a href="{{ url('/cashier/sales-report') }}" class="{{ request()->is('cashier/sales-report') || request()->is('cashier/sales/*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-simple"></i> Sales Report 
                    <i class="fa-solid fa-angle-down dropdown-icon" id="salesReportToggle" style="margin-left: auto; width: auto; font-size: 12px; padding: 5px; transition: transform 0.3s ease; {{ request()->is('cashier/sales-report') || request()->is('cashier/sales/*') ? 'transform: rotate(180deg);' : '' }}"></i>
                </a>
                
                <ul class="submenu">
                    <li><a href="{{ url('/cashier/sales/transactions') }}" class="{{ request()->is('cashier/sales/transactions') ? 'active' : '' }}">Transactions</a></li>
                    <li><a href="{{ url('/cashier/sales/refunds') }}" class="{{ request()->is('cashier/sales/refunds') ? 'active' : '' }}">Refunds</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="menu-group">
        <div class="menu-title">Support</div>
        <ul class="nav-menu">
            <li>
                <a href="{{ url('/cashier/settings') }}" class="{{ request()->is('cashier/settings') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Setting
                </a>
            </li>
            <li>
                <a href="{{ url('/cashier/help') }}" class="{{ request()->is('cashier/help') ? 'active' : '' }}">
                    <i class="fa-regular fa-circle-question"></i> Help
                </a>
            </li>
        </ul>
    </div>

    <div class="user-profile-section">
        <a href="{{ url('/cashier/profile') }}" class="profile-info" style="text-decoration: none; color: inherit; display: flex;">
            <div class="profile-avatar"><i class="fa-solid fa-user"></i></div>
            <div>
                <!-- This will now show 'Not Logged In' if there is no active session -->
                <div class="profile-name">{{ Auth::user()->name ?? 'Not Logged In' }}</div>
                <div class="profile-role">Cashier</div>
            </div>
        </a>
        
        <form action="{{ route('logout') }}" method="POST">
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