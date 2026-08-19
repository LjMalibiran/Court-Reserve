<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History | Court Reserve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-blue: #0033cc; --bg-gray: #f8f9fa; --text-gray: #777; --success-bg: #d4edda; --success-text: #155724; --danger-bg: #f8d7da; --danger-text: #721c24; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: var(--bg-gray); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar (Reusable) */
        .sidebar { width: 250px; background-color: white; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
        .logo-container { padding: 20px; text-align: center; border-bottom: 1px solid #ddd; }
        .nav-menu { list-style: none; padding: 0; margin: 20px 0; flex-grow: 1; }
        .nav-menu a { display: flex; align-items: center; padding: 15px 30px; color: var(--primary-blue); text-decoration: none; font-size: 16px; font-weight: 500; }
        .nav-menu a i { margin-right: 15px; width: 20px; text-align: center; }
        .nav-menu a.active { background-color: #e6edff; border-left: 4px solid var(--primary-blue); }

        /* Main Content */
        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Tabs */
        .tabs { display: flex; gap: 30px; margin-bottom: 25px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .tab-link { color: var(--text-gray); font-weight: 600; text-decoration: none; cursor: pointer; }
        .tab-link.active { color: var(--primary-blue); border-bottom: 2px solid var(--primary-blue); padding-bottom: 10px; }

        /* Search Bar */
        .search-container { display: flex; gap: 10px; margin-bottom: 30px; }
        .search-bar { flex-grow: 1; padding: 12px 20px; border: 1px solid #ddd; border-radius: 8px; }
        .filter-btn { padding: 12px 20px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer; }

        /* History Items */
        .history-card { background: white; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .res-info { display: flex; align-items: center; gap: 15px; }
        .badge { padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .badge-completed { background: var(--success-bg); color: var(--success-text); }
        .badge-cancelled { background: var(--danger-bg); color: var(--danger-text); }

        /* Mobile App Navigation Override */
        @media (max-width: 768px) {
            body { 
                flex-direction: column; 
            }
            
            /* Transforms sidebar into a bottom navbar */
            .sidebar {
                position: fixed; 
                bottom: 0; 
                left: 0; 
                width: 100%; 
                height: 70px;
                flex-direction: row; 
                border-right: none; 
                border-top: 1px solid #ddd;
                z-index: 1000; 
                padding: 0;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            }
            
            /* Hide the big logo on mobile */
            .logo-container { 
                display: none; 
            }
            
            /* Arrange the icons horizontally */
            .nav-menu { 
                display: flex; 
                flex-direction: row; 
                margin: 0; 
                width: 100%; 
                justify-content: space-around; 
                align-items: center; 
            }
            
            .nav-menu a { 
                padding: 10px; 
                flex-direction: column; /* Stacks icon above text */
                font-size: 11px; 
                border-left: none; 
                color: #777;
            }
            
            .nav-menu a i { 
                margin-right: 0; 
                margin-bottom: 4px; 
                font-size: 20px; 
            }
            
            /* Mobile active state (underline instead of left border) */
            .nav-menu a:hover, .nav-menu a.active { 
                border-left: none; 
                background: transparent; 
                color: var(--primary-blue); 
            }

            /* Push main content up so it isn't hidden behind the new bottom bar */
            .main-content { 
                padding: 20px;
                padding-bottom: 90px; 
            }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 150px;">
        </div>
        <ul class="nav-menu">
            <li><a href="{{ url('/home') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="{{ route('reservation.index') }}"><i class="fa-regular fa-calendar-plus"></i> Reservation</a></li>
            <li><a href="{{ route('history.index') }}" class="active"><i class="fa-solid fa-clock-rotate-left"></i> History</a></li>
            <li><a href="{{ route('profile.index') }}"><i class="fa-regular fa-user"></i> Profile</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h1>History</h1>
            <i class="fa-regular fa-bell" style="font-size: 24px; color: var(--primary-blue);"></i>
        </header>

        <div class="tabs">
            <a class="tab-link active">All</a>
            <a class="tab-link">Completed</a>
            <a class="tab-link">Cancelled</a>
        </div>

        <div class="search-container">
            <input type="text" class="search-bar" placeholder="Search...">
            <button class="filter-btn"><i class="fa-solid fa-filter"></i></button>
        </div>

        <div class="history-list">
            
            <div class="history-card">
                <div class="res-info">
                    <div style="font-size: 24px; color: var(--primary-blue);"><i class="fa-solid fa-shuttlecock"></i></div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-gray);">PC26-02</div>
                        <div style="font-weight: bold; color: var(--primary-blue);">Badminton Court 1</div>
                        <div style="font-size: 13px; color: var(--text-gray);">June 3, 2026 | 4:00 PM</div>
                    </div>
                </div>
                <div>
                    <span class="badge badge-completed">Completed</span>
                    <i class="fa-solid fa-chevron-right" style="margin-left: 15px; color: #ccc;"></i>
                </div>
            </div>

            <div class="history-card">
                <div class="res-info">
                    <div style="font-size: 24px; color: var(--primary-blue);"><i class="fa-solid fa-shuttlecock"></i></div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-gray);">BC26-02</div>
                        <div style="font-weight: bold; color: var(--primary-blue);">Badminton Court 1</div>
                        <div style="font-size: 13px; color: var(--text-gray);">June 3, 2026 | 4:00 PM</div>
                    </div>
                </div>
                <div>
                    <span class="badge badge-cancelled">Cancelled</span>
                    <i class="fa-solid fa-chevron-right" style="margin-left: 15px; color: #ccc;"></i>
                </div>
            </div>

        </div>
    </main>
    <script>
    document.querySelectorAll('.tab-link').forEach(tab => {
        tab.addEventListener('click', function() {
            // 1. Reset visual styles for all tabs
            document.querySelectorAll('.tab-link').forEach(t => {
                t.classList.remove('active');
                t.style.color = '#777'; 
                t.style.borderBottom = 'none';
            });
            
            // 2. Apply active styles to the clicked tab
            this.classList.add('active');
            this.style.color = '#0033cc'; 
            this.style.borderBottom = '2px solid #0033cc';
            
            // 3. Filtering Logic
            const filter = this.innerText.toLowerCase().trim();
            const cards = document.querySelectorAll('.history-card');
            
            cards.forEach(card => {
                // Look for the element with the class "badge"
                const badge = card.querySelector('.badge');
                
                if (badge) {
                    const status = badge.innerText.toLowerCase().trim();
                    if (filter === 'all' || status === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
</script>

</body>
</html>