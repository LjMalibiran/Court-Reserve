<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Dashboard Variables & Styling */
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }

        /* Main Content Container matching Dashboard */
        .main-content { flex-grow: 1; min-height: 0; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        .header-right i { font-size: 20px; cursor: pointer; }

        /* Toolbar (Search & Export) */
        .toolbar { display: flex; justify-content: flex-end; gap: 15px; margin-bottom: 25px; }
        .search-box { position: relative; width: 250px; }
        .search-box input { width: 100%; padding: 10px 15px 10px 35px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; outline: none; box-sizing: border-box; }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; }
        .btn-export { background: #fff; border: 1px solid #e5e7eb; padding: 10px 15px; border-radius: 8px; color: var(--text-muted); cursor: pointer; transition: 0.2s; }
        .btn-export:hover { background: #f9fafb; color: var(--dark-blue); }

        /* KPI Cards */
        .kpi-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .kpi-card { background: var(--card-bg); padding: 25px; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .kpi-icon { width: 60px; height: 60px; border-radius: 12px; background: #e0f2fe; color: #0ea5e9; display: flex; justify-content: center; align-items: center; font-size: 28px; }
        .kpi-details { text-align: center; }
        .kpi-details p { margin: 0; font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .kpi-details h3 { margin: 5px 0 0 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; line-height: 1; }

        /* Filters */
        .filters-row { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .filter-select { padding: 8px 15px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; color: var(--text-main); background: #fff; outline: none; cursor: pointer; }
        .filter-select.primary { background: var(--dark-blue); color: white; border: none; font-weight: 500; }
        .filter-label { font-size: 13px; color: var(--text-muted); margin: 0 5px; }

        /* Transactions Table */
        .table-container { background: var(--card-bg); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .transactions-table { width: 100%; border-collapse: collapse; text-align: center; }
        .transactions-table th { padding: 15px; font-size: 14px; color: var(--dark-blue); font-weight: 600; background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
        .transactions-table td { padding: 15px; font-size: 14px; color: var(--text-main); border-bottom: 1px solid #f1f5f9; }
        /* Alternating row colors matching the mockup */
        .transactions-table tbody tr:nth-child(even) { background-color: #f0f8ff; }
        .transactions-table tbody tr:hover { background-color: #e0f2fe; }

        .action-icon { color: var(--dark-blue); font-size: 18px; cursor: pointer; opacity: 0.7; transition: 0.2s; }
        .action-icon:hover { opacity: 1; }

        /* Pagination placeholder */
        .pagination { display: flex; justify-content: flex-end; padding: 20px; gap: 8px; align-items: center; }
        .page-btn { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 4px; border: none; background: transparent; color: var(--text-muted); cursor: pointer; font-size: 14px; font-weight: 500; }
        .page-btn.active { background: #0033ff; color: white; }
        .page-btn:hover:not(.active) { background: #f1f5f9; }
    </style>
</head>
<body>

    @include('cashier.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <h1>Transactions</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                @include('partials.notif-bell')
            </div>
        </header>

        


                <div class="kpi-container">
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-right-left"></i></div>
                <div class="kpi-details">
                    <p>Total Transactions</p>
                    <h3>{{ $reservations->count() }}</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-wallet"></i></div>
                <div class="kpi-details">
                    <p>Total Revenue</p>
                    <h3>&#8369;{{ number_format($totalRevenue, 0) }}</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-file-invoice"></i></div>
                <div class="kpi-details">
                    <p>Pending</p>
                    <h3>&#8369;{{ number_format($pendingAmount, 0) }}</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                <div class="kpi-details">
                    <p>Total Cash</p>
                    <h3>&#8369;{{ number_format($cashPayments, 0) }}</h3>
                </div>
            </div>
        </div>

                <form method="GET" action="{{ url('/cashier/transactions') }}" class="filters-row" id="filterForm" style="justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <select name="court" class="filter-select primary" onchange="document.getElementById('filterForm').submit()">
                    <option value="all" {{ request('court') == 'all' ? 'selected' : '' }}>All Courts</option>
                    <option value="1" {{ request('court') == '1' ? 'selected' : '' }}>Court 1</option>
                    <option value="2" {{ request('court') == '2' ? 'selected' : '' }}>Court 2</option>
                    <option value="3" {{ request('court') == '3' ? 'selected' : '' }}>Court 3</option>
                </select>
                <span class="filter-label">From</span>
                <input type="date" name="start_date" class="filter-select" value="{{ request('start_date') }}" onchange="document.getElementById('filterForm').submit()">
                <span class="filter-label">To</span>
                <input type="date" name="end_date" class="filter-select" value="{{ request('end_date') }}" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div style="display: flex; gap: 15px;">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}">
                </div>
                <button type="submit" name="export" value="1" formtarget="_blank" class="btn-export"><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
            </div>
        </form>

        <div class="table-container">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Court</th>
                        <th>Time</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Receipt</th>
                        
                    </tr>
                </thead>
                                <tbody>
                    @forelse ($reservations as $res)
                    <tr>
                        <td>{{ $res->id }}</td>
                        <td>{{ $res->user ? $res->user->name : ($res->walk_in_name ?: 'Walk-In') }}</td>
                        <td>Court {{ $res->court_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($res->start_time)->format('g:ia') }}-{{ \Carbon\Carbon::parse($res->end_time)->format('g:ia') }}</td>
                        <td>&#8369;{{ number_format($res->total_price, 2) }}</td>
                        <td>{{ strtolower($res->payment_type) == 'cash' ? 'Cash' : 'Gcash' }}</td>
                        <td>{{ \Carbon\Carbon::parse($res->start_time)->format('m/d/y') }}</td>
                        <td>
                            @if($res->receipt_path)
                                <i class="fa-regular fa-file-lines action-icon" onclick="window.open('{{ asset('storage/' . $res->receipt_path) }}', '_blank')" title="View Receipt"></i>
                            @else
                                <i class="fa-regular fa-file-lines action-icon" style="opacity: 0.2; cursor: not-allowed;" title="No Receipt"></i>
                            @endif
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            
        </div>

    </main>
    
@include('partials.notif-script')
</body>
</html>








