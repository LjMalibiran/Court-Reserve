<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transactions Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #002277; }
        .header p { margin: 5px 0; color: #555; }
        
        .kpi-row { width: 100%; margin-bottom: 20px; }
        .kpi-row td { width: 25%; text-align: center; border: 1px solid #ddd; padding: 10px; background-color: #f9f9f9; }
        .kpi-row h4 { margin: 0 0 5px 0; font-size: 11px; color: #777; text-transform: uppercase; }
        .kpi-row h3 { margin: 0; font-size: 16px; color: #002277; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .data-table th { background-color: #002277; color: #fff; }
        .data-table tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Batangas Badminton</h2>
        <p>Transactions Report - {{ date('F j, Y') }}</p>
    </div>

    <table class="kpi-row">
        <tr>
            <td>
                <h4>Total Transactions</h4>
                <h3>{{ $reservations->count() }}</h3>
            </td>
            <td>
                <h4>Total Revenue</h4>
                <h3>PHP {{ number_format($totalRevenue, 2) }}</h3>
            </td>
            <td>
                <h4>Pending</h4>
                <h3>PHP {{ number_format($pendingAmount, 2) }}</h3>
            </td>
            <td>
                <h4>Total Cash</h4>
                <h3>PHP {{ number_format($cashPayments, 2) }}</h3>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Court</th>
                <th>Time</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $res)
                @php
                    $name = $res->user ? $res->user->name : ($res->walk_in_name ?: 'Walk-In');
                    $time = \Carbon\Carbon::parse($res->start_time)->format('g:ia') . '-' . \Carbon\Carbon::parse($res->end_time)->format('g:ia');
                    $payment = strtolower($res->payment_type) == 'cash' ? 'Cash' : 'Gcash';
                    $date = \Carbon\Carbon::parse($res->start_time)->format('m/d/y');
                @endphp
                <tr>
                    <td>{{ $res->id }}</td>
                    <td>{{ $name }}</td>
                    <td>Court {{ $res->court_id }}</td>
                    <td>{{ $time }}</td>
                    <td>PHP {{ number_format($res->total_price, 2) }}</td>
                    <td>{{ $payment }}</td>
                    <td>{{ $date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

<script>window.onload = function() { window.print(); }</script>
</body>
</html>


