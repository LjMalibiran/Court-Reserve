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
    
        /* 3-Dot Action Dropdown */
        .dropdown { position: relative; display: inline-block; }
        .action-dots { cursor: pointer; color: #4b5563; font-size: 20px; padding: 5px 10px; font-weight: bold; background: none; border: none; }
        .dropdown-content { display: none; position: absolute; right: 0; background-color: #ffffff; min-width: 140px; box-shadow: 0px 4px 12px rgba(0,0,0,0.15); z-index: 50; border-radius: 8px; border: 1px solid #eee; overflow: hidden; }
        .dropdown-content button, .dropdown-content a { color: #333; padding: 10px 16px; text-decoration: none; display: block; font-size: 13px; text-align: left; width: 100%; border: none; background: none; cursor: pointer; font-family: inherit; }
        .dropdown-content a:hover, .dropdown-content button:hover { background-color: #f8fafc; color: var(--primary-blue); }
        .dropdown:hover .dropdown-content { display: block; }
        .dropdown-content i { margin-right: 8px; width: 16px; text-align: center; }
        
        .text-danger { color: #dc2626 !important; }
        .text-danger:hover { background-color: #fef2f2 !important; }

        /* Receipt Button */
        .btn-receipt { display: inline-flex; align-items: center; justify-content: center; border: 1px solid #cbd5e1; background: #fff; padding: 6px 10px; border-radius: 6px; color: #475569; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn-receipt:hover { background: #f1f5f9; color: var(--dark-blue); }

        /* Outline Buttons for Pending */
        .action-btns { display: flex; gap: 8px; justify-content: center; }
        .btn-outline-confirm { border: 1px solid #10b981; color: #10b981; background: transparent; padding: 6px 16px; border-radius: 6px; font-weight: 500; font-size: 13px; transition: 0.2s; cursor: pointer; }
        .btn-outline-confirm:hover { background: #10b981; color: white; }
        .btn-outline-cancel { border: 1px solid #ef4444; color: #ef4444; background: transparent; padding: 6px 16px; border-radius: 6px; font-weight: 500; font-size: 13px; transition: 0.2s; cursor: pointer; }
        .btn-outline-cancel:hover { background: #ef4444; color: white; }

        /* Receipt Lightbox Modal */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); display: none; justify-content: center; align-items: center; z-index: 2000; backdrop-filter: blur(3px); }
    </style>
</head>
<body>

    @include('cashier.sidebar')

    <main class="main-content">
        <header class="top-header">
            <h1>Reservations</h1>
            <div class="header-right">
                <span>{{ now()->timezone('Asia/Manila')->format('l, F j, Y') }}</span>
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
                        <th>ID</th><th>Name</th><th>Sport & Court</th><th>Date & Time</th><th>Amount</th><th style="text-align: center;">Receipt</th><th style="text-align: center;">Status</th><th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                        <tr>
                            <td style="color: var(--primary-blue); font-weight: 600;">{{ $res->reservation_code }}</td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>{{ $res->court->sport ?? 'Badminton' }} - Court {{ $res->court_id }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}</div>
                                <div style="font-size: 12px; color: #777;">{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</div>
                            </td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;">
                                @if($res->receipt_path)
                                    <button type="button" class="btn-receipt" onclick="viewReceipt('{{ asset('storage/' . $res->receipt_path) }}')"><i class="fa-regular fa-image"></i></button>
                                @else
                                    <span style="color: #999; font-size: 12px;">N/A</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span class="badge badge-{{ strtolower($res->status) }}">{{ ucfirst($res->status) }}</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="dropdown">
                                    <button class="action-dots">⋮</button>
                                    <div class="dropdown-content">
                                        <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                        <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                        <form action="{{ url(Request::segment(1).'/reservations/'.$res->id.'/cancel') }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="text-danger" onclick="return confirm('Are you sure you want to delete this reservation?');"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-state">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TABLE 2: PENDING -->
            <table id="table-pending" class="data-table" style="display: none;">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Sport & Court</th><th>Date & Time</th><th>Amount</th><th style="text-align: center;">Receipt</th><th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'pending') as $res)
                        <tr>
                            <td style="color: var(--primary-blue); font-weight: 600;">{{ $res->reservation_code }}</td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>{{ $res->court->sport ?? 'Badminton' }} - Court {{ $res->court_id }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}</div>
                                <div style="font-size: 12px; color: #777;">{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</div>
                            </td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;">
                                @if($res->receipt_path)
                                    <button type="button" class="btn-receipt" onclick="viewReceipt('{{ asset('storage/' . $res->receipt_path) }}')"><i class="fa-regular fa-image"></i></button>
                                @else
                                    <span style="color: #999; font-size: 12px;">N/A</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="action-btns">
                                    <form action="{{ url(Request::segment(1).'/reservations/'.$res->id.'/confirm') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-outline-confirm">Confirm</button>
                                    </form>
                                    <form action="{{ url(Request::segment(1).'/reservations/'.$res->id.'/cancel') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-outline-cancel" onclick="return confirm('Are you sure you want to cancel this reservation?');">Cancel</button>
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
                        <th>ID</th><th>Name</th><th>Sport & Court</th><th>Date & Time</th><th>Amount</th><th style="text-align: center;">Receipt</th><th style="text-align: center;">Status</th><th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'confirmed') as $res)
                        <tr>
                            <td style="color: var(--primary-blue); font-weight: 600;">{{ $res->reservation_code }}</td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>{{ $res->court->sport ?? 'Badminton' }} - Court {{ $res->court_id }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}</div>
                                <div style="font-size: 12px; color: #777;">{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</div>
                            </td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;">
                                @if($res->receipt_path)
                                    <button type="button" class="btn-receipt" onclick="viewReceipt('{{ asset('storage/' . $res->receipt_path) }}')"><i class="fa-regular fa-image"></i></button>
                                @else
                                    <span style="color: #999; font-size: 12px;">N/A</span>
                                @endif
                            </td>
                            <td style="text-align: center;"><span class="badge badge-confirmed">Confirmed</span></td>
                            <td style="text-align: center;">
                                <div class="dropdown">
                                    <button class="action-dots">⋮</button>
                                    <div class="dropdown-content">
                                        <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                        <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                        <form action="{{ url(Request::segment(1).'/reservations/'.$res->id.'/cancel') }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="text-danger" onclick="return confirm('Are you sure you want to delete this reservation?');"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-state">No confirmed reservations.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TABLE 4: COMPLETED -->
            <table id="table-completed" class="data-table" style="display: none;">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Sport & Court</th><th>Date & Time</th><th>Amount</th><th style="text-align: center;">Receipt</th><th style="text-align: center;">Status</th><th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'completed') as $res)
                        <tr>
                            <td style="color: var(--primary-blue); font-weight: 600;">{{ $res->reservation_code }}</td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>{{ $res->court->sport ?? 'Badminton' }} - Court {{ $res->court_id }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}</div>
                                <div style="font-size: 12px; color: #777;">{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</div>
                            </td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;">
                                @if($res->receipt_path)
                                    <button type="button" class="btn-receipt" onclick="viewReceipt('{{ asset('storage/' . $res->receipt_path) }}')"><i class="fa-regular fa-image"></i></button>
                                @else
                                    <span style="color: #999; font-size: 12px;">N/A</span>
                                @endif
                            </td>
                            <td style="text-align: center;"><span class="badge badge-completed">Completed</span></td>
                            <td style="text-align: center;">
                                <div class="dropdown">
                                    <button class="action-dots">⋮</button>
                                    <div class="dropdown-content">
                                        <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                        <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                        <form action="{{ url(Request::segment(1).'/reservations/'.$res->id.'/cancel') }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="text-danger" onclick="return confirm('Are you sure you want to delete this reservation?');"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-state">No completed reservations.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TABLE 5: CANCELLED -->
            <table id="table-cancelled" class="data-table" style="display: none;">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Sport & Court</th><th>Date & Time</th><th>Amount</th><th style="text-align: center;">Receipt</th><th style="text-align: center;">Status</th><th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations->where('status', 'cancelled') as $res)
                        <tr>
                            <td style="color: var(--primary-blue); font-weight: 600;">{{ $res->reservation_code }}</td>
                            <td>{{ $res->user->name ?? 'User '.$res->user_id }}</td>
                            <td>{{ $res->court->sport ?? 'Badminton' }} - Court {{ $res->court_id }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}</div>
                                <div style="font-size: 12px; color: #777;">{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</div>
                            </td>
                            <td>₱{{ number_format($res->total_price, 2) }}</td>
                            <td style="text-align: center;">
                                @if($res->receipt_path)
                                    <button type="button" class="btn-receipt" onclick="viewReceipt('{{ asset('storage/' . $res->receipt_path) }}')"><i class="fa-regular fa-image"></i></button>
                                @else
                                    <span style="color: #999; font-size: 12px;">N/A</span>
                                @endif
                            </td>
                            <td style="text-align: center;"><span class="badge badge-cancelled">Cancelled</span></td>
                            <td style="text-align: center;">
                                <div class="dropdown">
                                    <button class="action-dots">⋮</button>
                                    <div class="dropdown-content">
                                        <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                        <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                        <form action="{{ url(Request::segment(1).'/reservations/'.$res->id.'/cancel') }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="text-danger" onclick="return confirm('Are you sure you want to delete this reservation?');"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-state">No cancelled reservations.</td></tr>
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

    <!-- Invisible Lightbox Modal for Receipts -->
    <div id="receiptModal" class="modal-overlay">
        <div style="position: relative; background: transparent; padding: 0; box-shadow: none; max-width: 80%; text-align: center;">
            <button onclick="closeReceipt()" style="position: absolute; top: -40px; right: -40px; font-size: 35px; color: white; background: none; border: none; cursor: pointer; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">&times;</button>
            <img id="receiptImage" src="" style="max-width: 100%; max-height: 85vh; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
        </div>
    </div>

    <script>
        function switchTab(tabName, btnElement) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');

            document.querySelectorAll('.data-table').forEach(table => table.style.display = 'none');
            document.getElementById('table-' + tabName).style.display = 'table';
        }

        // Functions for the Receipt Lightbox
        function viewReceipt(imageUrl) {
            document.getElementById('receiptImage').src = imageUrl;
            document.getElementById('receiptModal').style.display = 'flex';
        }

        function closeReceipt() {
            document.getElementById('receiptModal').style.display = 'none';
            document.getElementById('receiptImage').src = '';
        }
    </script>
    
    <script>
    // Real-Time Polling with Cache Buster
    setInterval(function() {
        
        let currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('t', new Date().getTime()); // <-- Cache Buster
        
        fetch(currentUrl.toString())
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                
                const tableIds = ['table-all', 'table-pending', 'table-confirmed', 'table-completed', 'table-cancelled'];
                
                tableIds.forEach(id => {
                    let newTbody = doc.querySelector(`#${id} tbody`);
                    let currentTbody = document.querySelector(`#${id} tbody`);
                    if (newTbody && currentTbody) {
                        currentTbody.innerHTML = newTbody.innerHTML;
                    }
                });

                let newSpans = doc.querySelectorAll('.tab-btn span');
                let currentSpans = document.querySelectorAll('.tab-btn span');
                
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