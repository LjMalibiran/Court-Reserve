<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report | Batangas Badminton</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
            --border-color: #e5e7eb;
            --accent-light: #e0e7ff;
            --gcash-blue: #3b82f6;
            --cash-cyan: #22d3ee;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        /* Top Controls */
        .controls-bar { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px; gap: 15px; }
        .filter-select { padding: 8px 15px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; color: var(--text-main); outline: none; background-color: #fff; cursor: pointer; }
        .btn-export { background: #fff; border: 1px solid var(--border-color); padding: 8px 12px; border-radius: 6px; color: var(--dark-blue); cursor: pointer; font-size: 16px; }

        /* KPI Cards Grid */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .kpi-card { background: var(--card-bg); border-radius: 12px; padding: 25px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .kpi-title { font-size: 14px; color: var(--text-muted); font-weight: 500; margin-bottom: 10px; width: 100%; }
        .kpi-value-container { display: flex; align-items: center; gap: 15px; }
        .kpi-icon { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        
        /* Icon Colors */
        .icon-sales { background-color: #e0f2fe; color: #0284c7; }
        .icon-reserved { background-color: #e0e7ff; color: #4f46e5; }
        .icon-walkin { background-color: #e0f2fe; color: #0284c7; }
        .icon-transaction { background-color: #f3f4f6; color: #4b5563; }

        .kpi-value { font-size: 32px; font-weight: 700; color: var(--dark-blue); margin: 0; }

        /* Charts Grid */
        .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .chart-card { background: var(--card-bg); border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .chart-card h3 { margin: 0 0 15px 0; font-size: 16px; font-weight: 500; color: var(--text-main); }
        
        /* Total Sales Specifics */
        .sales-amount-display { font-size: 36px; font-weight: 600; color: var(--dark-blue); margin-bottom: 20px; }
        
        /* Chart Containers */
        .line-chart-container { position: relative; height: 300px; width: 100%; }
        .pie-chart-container { position: relative; height: 300px; width: 100%; display: flex; justify-content: center; align-items: center; }

        /* Empty State Indicator */
        .empty-text { position: absolute; font-size: 14px; color: #9ca3af; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <h1>Sales Report</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <div class="controls-bar">
            <select class="filter-select">
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
            <button class="btn-export"><i class="fa-solid fa-file-export"></i></button>
        </div>

        <!-- KPI Cards -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-title">Current Sales</div>
                <div class="kpi-value-container">
                    <div class="kpi-icon icon-sales"><i class="fa-solid fa-wallet"></i></div>
                    <h2 class="kpi-value">0</h2>
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-title">Today Reserved</div>
                <div class="kpi-value-container">
                    <div class="kpi-icon icon-reserved"><i class="fa-solid fa-calendar-check"></i></div>
                    <h2 class="kpi-value">0</h2>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-title">Total Walk-In</div>
                <div class="kpi-value-container">
                    <div class="kpi-icon icon-walkin"><i class="fa-solid fa-shoe-prints"></i></div>
                    <h2 class="kpi-value">0</h2>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-title">Total Transaction</div>
                <div class="kpi-value-container">
                    <div class="kpi-icon icon-transaction"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                    <h2 class="kpi-value">0</h2>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <!-- Line Chart -->
            <div class="chart-card">
                <h3>Total Sales</h3>
                <div class="sales-amount-display">0</div>
                <div class="line-chart-container">
                    <canvas id="salesLineChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="chart-card">
                <h3>Payment Method Breakdown</h3>
                <div class="pie-chart-container">
                    <div class="empty-text" id="pieEmptyText">No transactions yet</div>
                    <canvas id="paymentPieChart"></canvas>
                </div>
            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Line Chart Configuration (Zeroed out) ---
            const lineCtx = document.getElementById('salesLineChart').getContext('2d');
            
            // Gradient for the line area
            let gradient = lineCtx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales',
                        data: [0, 0, 0, 0, 0, 0, 0], // Empty data
                        borderColor: '#3b82f6',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4 // Smooth curves
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₱ ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 1000, // Provides visual space even when data is 0
                            grid: {
                                borderDash: [5, 5],
                                color: '#f3f4f6',
                                drawBorder: false
                            },
                            ticks: { color: '#9ca3af', font: { size: 12 } }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#9ca3af', font: { size: 12 } }
                        }
                    }
                }
            });

            // --- Pie Chart Configuration (Zeroed out) ---
            const pieCtx = document.getElementById('paymentPieChart').getContext('2d');
            
            // Array of data for empty state
            const pieData = [0, 0]; 
            
            const paymentChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Gcash', 'Cash'],
                    datasets: [{
                        data: pieData,
                        backgroundColor: ['#3b82f6', '#22d3ee'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { size: 12 },
                                color: '#4b5563'
                            }
                        }
                    }
                }
            });

            // Hide the placeholder text if data actually exists later on
            const hasData = pieData.some(val => val > 0);
            if (hasData) {
                document.getElementById('pieEmptyText').style.display = 'none';
            }
        });
    </script>
</body>
</html>