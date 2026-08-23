<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
            --border-color: #e5e7eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        /* Controls */
        .controls-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .filter-tabs { display: flex; gap: 10px; }
        .tab-btn { background-color: #f0f2f5; color: var(--text-main); border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px; }
        .tab-btn:hover { background-color: #e4e6e9; }
        .tab-btn.active { background-color: var(--dark-blue); color: white; }
        
        .search-box { display: flex; align-items: center; gap: 10px; }
        .search-input-wrapper { position: relative; }
        .search-input-wrapper i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-input-wrapper input { padding: 10px 35px 10px 15px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 250px; }
        .btn-export { background: #fff; border: 1px solid var(--border-color); padding: 9px 12px; border-radius: 6px; color: var(--dark-blue); cursor: pointer; font-size: 16px; }

        /* Tables */
        .table-container { background: var(--card-bg); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: visible; flex-grow: 1; display: flex; flex-direction: column; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 20px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: var(--dark-blue); font-weight: 600; border-bottom: 2px solid var(--border-color); }
        td { border-bottom: 1px solid #f0f0f0; color: #4b5563; vertical-align: middle; }
        
        /* Badges */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; text-align: center; display: inline-block; }
        .badge-pending { background-color: #fef3c7; color: #d97706; }
        .badge-confirmed { background-color: #d1fae5; color: #059669; }
        .badge-cancelled { background-color: #fee2e2; color: #dc2626; }
        .badge-completed { background-color: #e0e7ff; color: #4338ca; }

        /* Action Buttons */
        .action-btns { display: flex; gap: 8px; justify-content: center; }
        .btn-action { border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold; color: white; transition: 0.2s; }
        .btn-confirm { background-color: var(--success); }
        .btn-confirm:hover { background-color: #059669; }
        .btn-cancel { background-color: var(--danger); }
        .btn-cancel:hover { background-color: #dc2626; }
        
        /* Empty State */
        .empty-state { text-align: center !important; padding: 40px !important; color: var(--text-muted) !important; font-style: italic; }

        /* Pagination */
        .pagination { display: flex; justify-content: flex-end; align-items: center; padding: 20px; gap: 10px; margin-top: auto; }
        .page-item { display: flex; justify-content: center; align-items: center; width: 32px; height: 32px; border-radius: 6px; font-size: 14px; color: var(--text-main); cursor: pointer; text-decoration: none; }
        .page-item.active { background-color: var(--primary-blue); color: white; font-weight: bold; }
        
        /* Alerts */
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; font-weight: bold; }
        .alert-success { background-color: #d1fae5; color: #059669; border: 1px solid #34d399; }
        .alert-error { background-color: #fee2e2; color: #dc2626; border: 1px solid #f87171; }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        <header class="top-header">
            <h1>Reservations</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="controls-bar">
            <div class="filter-tabs">
                <button class="tab-btn active" onclick="switchTab('all', this)">All <span>{{ $reservations->count() }}</span></button>
                <button class="tab-btn" onclick="switchTab('pending', this)">Pending <span>{{ $reservations->where('status', 'pending')->count() }}</span></button>
                <button class="tab-btn" onclick="switchTab('confirmed', this)">Confirmed <span>{{ $reservations->where('status', 'confirmed')->count() }}</span></button>
                <button class="tab-btn" onclick="switchTab('completed', this)">Completed <span>{{ $reservations->where('status', 'completed')->count() }}</span></button>
                <button class="tab-btn" onclick="switchTab('cancelled', this)">Cancelled <span>{{ $reservations->where('status', 'cancelled')->count() }}</span></button>
            </div>
            
            <div class="search-box">
                <div class="search-input-wrapper">
                    <input type="text" placeholder="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <button class="btn-export"><i class="fa-solid fa-file-export"></i></button>
            </div>
        </div>

        <div class="table-container">
            
            <!-- TABLE 1: ALL -->
            <table id="table-all" class="data-table">
                <thead>
                    <tr>
                        <th>Code</th><th>Name</th><th>Court</th><th>Date</th><th>Time</th><th>Amount</th><th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                        <tr>
                            <td><strong>{{ $res->reservation_code }}</strong></td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>Court {{ $res->court_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($res->end_time)->format('h:i A') }}
                            </td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-{{ strtolower($res->status) }}">{{ ucfirst($res->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TABLE 2: PENDING -->
            <table id="table-pending" class="data-table" style="display: none;">
                <thead>
                    <tr>
                        <th>Code</th><th>Name</th><th>Court</th><th>Date & Time</th><th>Amount</th><th style="text-align: center;">Status</th><th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'pending') as $res)
                        <tr>
                            <td><strong>{{ $res->reservation_code }}</strong></td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>Court {{ $res->court_id }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</div>
                                <div style="font-size: 12px; color: #777;">{{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('h:i A') }}</div>
                            </td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-pending">Pending</span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <form action="{{ url('/admin/reservations/'.$res->id.'/confirm') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-confirm"><i class="fa-solid fa-check"></i></button>
                                    </form>
                                    <form action="{{ url('/admin/reservations/'.$res->id.'/cancel') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Are you sure you want to cancel this reservation?');"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">No pending reservations.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TABLE 3: CONFIRMED -->
            <table id="table-confirmed" class="data-table" style="display: none;">
                <thead>
                    <tr>
                        <th>Code</th><th>Name</th><th>Court</th><th>Date</th><th>Time</th><th>Amount</th><th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'confirmed') as $res)
                        <tr>
                            <td><strong>{{ $res->reservation_code }}</strong></td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>Court {{ $res->court_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }}</td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;"><span class="badge badge-confirmed">Confirmed</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">No confirmed reservations.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TABLE 4: COMPLETED -->
            <table id="table-completed" class="data-table" style="display: none;">
                <thead>
                    <tr>
                        <th>Code</th><th>Name</th><th>Court</th><th>Date</th><th>Time</th><th>Amount</th><th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'completed') as $res)
                        <tr>
                            <td><strong>{{ $res->reservation_code }}</strong></td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>Court {{ $res->court_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }}</td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;"><span class="badge badge-completed">Completed</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">No completed reservations.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TABLE 5: CANCELLED -->
            <table id="table-cancelled" class="data-table" style="display: none;">
                <thead>
                    <tr>
                        <th>Code</th><th>Name</th><th>Court</th><th>Date</th><th>Time</th><th>Amount</th><th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'cancelled') as $res)
                        <tr>
                            <td><strong>{{ $res->reservation_code }}</strong></td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>Court {{ $res->court_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }}</td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;"><span class="badge badge-cancelled">Cancelled</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">No cancelled reservations.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                <a href="#" class="page-item"><i class="fa-solid fa-chevron-left"></i></a>
                <a href="#" class="page-item active">1</a>
                <a href="#" class="page-item"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
            
        </div>
    </main>

    <script>
        function switchTab(tabName, btnElement) {
            // Update active styling
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            btnElement.classList.add('active');

            // Hide all tables, show target table
            document.querySelectorAll('.data-table').forEach(table => {
                table.style.display = 'none';
            });
            document.getElementById('table-' + tabName).style.display = 'table';
        }
    </script>
<script>
    // Set an interval to run this code every 5 seconds (5000 milliseconds)
    setInterval(function() {
        
        // 1. Silently fetch the current page URL in the background
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                
                // 2. Parse the newly downloaded HTML
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                
                // 3. UPDATE ALL TABLES
                // We loop through the exact IDs of your tables to update each one's <tbody>
                const tableIds = ['table-all', 'table-pending', 'table-confirmed', 'table-completed', 'table-cancelled'];
                
                tableIds.forEach(id => {
                    let newTbody = doc.querySelector(`#${id} tbody`);
                    let currentTbody = document.querySelector(`#${id} tbody`);
                    
                    if (newTbody && currentTbody) {
                        currentTbody.innerHTML = newTbody.innerHTML;
                    }
                });

                // 4. UPDATE THE NUMBER COUNTERS ON THE TABS
                // Grab all the <span> tags inside your tab buttons from both the new data and current screen
                let newSpans = doc.querySelectorAll('.tab-btn span');
                let currentSpans = document.querySelectorAll('.tab-btn span');
                
                // Loop through and match them up so the numbers change seamlessly
                for(let i = 0; i < currentSpans.length; i++) {
                    if(newSpans[i]) {
                        currentSpans[i].innerHTML = newSpans[i].innerHTML;
                    }
                }
                
            })
            .catch(error => console.log('Polling error, waiting for next cycle...'));
            
    }, 5000);
</script>
</body>
</html>