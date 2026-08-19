<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions | Batangas Badminton</title>
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
            --gcash-color: #3b82f6;
            --cash-color: #06b6d4;
            --pending-color: #f59e0b;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        /* KPI Cards */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .kpi-card { background: var(--card-bg); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); }
        .kpi-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
        
        /* KPI Variants */
        .icon-revenue { background-color: #e0e7ff; color: #4f46e5; }
        .icon-gcash { background-color: #eff6ff; color: var(--gcash-color); }
        .icon-cash { background-color: #ecfeff; color: var(--cash-color); }
        .icon-pending { background-color: #fef3c7; color: var(--pending-color); }
        
        .kpi-details { flex-grow: 1; }
        .kpi-title { font-size: 13px; color: var(--text-muted); font-weight: 500; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .kpi-value { font-size: 24px; font-weight: 700; color: var(--dark-blue); margin: 0; }

        /* Filter Controls */
        .controls-bar { background: var(--card-bg); padding: 15px 20px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .filter-group { display: flex; align-items: center; gap: 15px; }
        
        .filter-item { display: flex; align-items: center; gap: 8px; }
        .filter-item label { font-size: 13px; font-weight: 600; color: var(--text-main); }
        .filter-control { padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; color: var(--text-main); outline: none; background: #fff; }
        .filter-control:focus { border-color: var(--primary-blue); }
        
        .btn-filter { background: var(--primary-blue); color: white; border: none; padding: 9px 20px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-filter:hover { background: var(--dark-blue); }
        
        .search-box { display: flex; align-items: center; gap: 10px; }
        .search-input-wrapper { position: relative; }
        .search-input-wrapper i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-input-wrapper input { padding: 9px 35px 9px 15px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; width: 220px; }
        .btn-export { background: #fff; border: 1px solid var(--border-color); padding: 8px 12px; border-radius: 6px; color: var(--dark-blue); cursor: pointer; font-size: 16px; }

        /* Tables */
        .table-container { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); flex-grow: 1; display: flex; flex-direction: column; overflow: visible; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 20px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: var(--dark-blue); font-weight: 600; border-bottom: 2px solid var(--border-color); }
        td { border-bottom: 1px solid #f0f0f0; color: #4b5563; }
        
        .empty-state { text-align: center !important; padding: 60px 40px !important; color: var(--text-muted) !important; font-style: italic; }
        .empty-icon { font-size: 40px; color: #cbd5e1; margin-bottom: 15px; }
        
        .pagination { display: flex; justify-content: flex-end; padding: 20px; gap: 10px; margin-top: auto; }
        .pagination a { color: var(--text-main); text-decoration: none; padding: 5px 10px; border-radius: 4px; }
        .pagination a.active { background: var(--primary-blue); color: white; }
    </style>
</head>
<body>

    @include('cashier.sidebar')

    <main class="main-content">
        
        <!-- Header -->
        <header class="top-header">
            <h1>Transactions</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <!-- KPI Cards -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon icon-revenue"><i class="fa-solid fa-wallet"></i></div>
                <div class="kpi-details">
                    <div class="kpi-title">Total Revenue</div>
                    <div class="kpi-value">₱ 0.00</div>
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon icon-gcash"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <div class="kpi-details">
                    <div class="kpi-title">GCash Payments</div>
                    <div class="kpi-value">₱ 0.00</div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon icon-cash"><i class="fa-solid fa-money-bill-wave"></i></div>
                <div class="kpi-details">
                    <div class="kpi-title">Cash Payments</div>
                    <div class="kpi-value">₱ 0.00</div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon icon-pending"><i class="fa-regular fa-clock"></i></div>
                <div class="kpi-details">
                    <div class="kpi-title">Pending / Unpaid</div>
                    <div class="kpi-value">₱ 0.00</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="controls-bar">
            <div class="filter-group">
                <div class="filter-item">
                    <label>Court:</label>
                    <select class="filter-control">
                        <option value="all">All Courts</option>
                        <option value="1">Court 1</option>
                        <option value="2">Court 2</option>
                        <option value="3">Court 3</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label>From:</label>
                    <input type="date" class="filter-control">
                </div>

                <div class="filter-item">
                    <label>To:</label>
                    <input type="date" class="filter-control">
                </div>

                <button class="btn-filter">Apply Filter</button>
            </div>
            
            <div class="search-box">
                <div class="search-input-wrapper">
                    <input type="text" placeholder="Search Transaction ID...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <button class="btn-export" title="Export Data"><i class="fa-solid fa-file-export"></i></button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Customer</th>
                        <th>Court</th>
                        <th>Date & Time</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-icon"><i class="fa-solid fa-receipt"></i></div>
                            <div>No transactions found for the selected criteria.</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="pagination">
                <a href="#"><i class="fa-solid fa-chevron-left"></i></a>
                <a href="#" class="active">1</a>
                <a href="#"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>

    </main>

</body>
</html>