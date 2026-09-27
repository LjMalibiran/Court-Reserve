@extends('layouts.app')

@section('title', 'History | Court Reserve')
@section('header_title', 'History')

@section('styles')
<style>
    /* Tabs */
    .tabs { display: flex; gap: 24px; margin-bottom: 25px; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 0; }
    .tab-link { color: #64748b; font-weight: 600; text-decoration: none; cursor: pointer; padding: 10px 4px; border: none; background: transparent; transition: 0.2s; white-space: nowrap; font-size: 15px; border-bottom: 3px solid transparent; margin-bottom: -1.5px; border-radius: 0; }
    .tab-link:hover { color: #0f2b6e; background: transparent; }
    .tab-link.active { color: #0033cc; border-bottom-color: #0033cc; background: transparent; }

    /* Search Bar */
    .search-container { display: flex; gap: 10px; margin-bottom: 25px; }
    .search-bar { flex-grow: 1; padding: 12px 20px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; outline: none; }
    .filter-btn { padding: 12px 20px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer; color: var(--text-gray); }

    /* History Items */
    .history-card { background: white; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.02); cursor: pointer; transition: 0.2s; }
    .history-card:hover { border-color: #ccc; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .res-info { display: flex; align-items: center; gap: 15px; }
    
    .crc-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 24px; }
    .crc-icon.pickleball { color: #f39c12; }
    .crc-icon.badminton { color: var(--primary-blue); }

    .badge { padding: 6px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .badge-completed { background: #d1fae5; color: #059669; }
    .badge-cancelled { background: #fee2e2; color: #dc2626; }
    
    .empty-state { flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 40px 20px; }
    .empty-state i { font-size: 50px; color: #e0e0e0; margin-bottom: 15px; }
    .empty-state h4 { color: var(--text-dark); margin: 0 0 8px 0; font-size: 18px; }
    .empty-state p { color: var(--text-gray); margin: 0; font-size: 14px; }

    @media (max-width: 768px) {
        .history-card { flex-direction: column; align-items: flex-start; gap: 15px; }
        .badge { align-self: flex-start; }
        .tabs { gap: 15px; overflow-x: auto; padding-bottom: 5px; }
    }
</style>
@endsection

@section('content')
<div class="tabs">
    <a class="tab-link active">All</a>
    <a class="tab-link">Completed</a>
    <a class="tab-link">Cancelled</a>
</div>

<hr style="border: none; border-top: 1.5px solid #e2e8f0; margin: 10px 0 20px 0;">

<div class="search-container">
    <input type="text" class="search-bar" id="searchBar" placeholder="Search reservations...">
    <button class="filter-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
</div>

<div class="history-list">
    @forelse($historyReservations ?? collect() as $res)
        @php
            $paid = (float)$res->amount_paid;
            if ($paid == 0 && in_array($res->payment_type, ['full', 'half'])) {
                $paid = ($res->payment_type == 'half') ? ((float)$res->total_price / 2) : (float)$res->total_price;
            }
            $displayRefundAmt = (float)$res->refund_amount > 0 ? (float)$res->refund_amount : $paid;
        @endphp
        <div class="history-card" data-search="{{ strtolower($res->sport . ' ' . $res->court_id . ' ' . $res->reservation_code . ' ' . \Carbon\Carbon::parse($res->start_time)->format('F j, Y M j, Y Y-m-d')) }}" onclick="openHistoryDetails('{{ $res->id }}', '{{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->reservation_code }}', '{{ ucfirst($res->status) }}', '{{ $res->refund_status }}', '{{ $displayRefundAmt }}')">
            <div class="res-info">
                <div class="crc-icon {{ strtolower($res->sport ?? 'badminton') }}">
                    @if(($res->sport ?? 'Badminton') == 'Pickleball')
                        <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                    @else
                        <img src="{{ asset('images/shuttlecock.png') }}" width="30">
                    @endif
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--text-gray);">{{ $res->reservation_code }}</div>
                    <div style="font-weight: bold; color: var(--primary-blue);">{{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}</div>
                    <div style="font-size: 13px; color: var(--text-gray);">{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y | g:i A') }}</div>
                </div>
            </div>
            <div>
                <span class="badge {{ $res->status == 'completed' ? 'badge-completed' : 'badge-cancelled' }}">
                    {{ ucfirst($res->status) }}
                </span>
                <i class="fa-solid fa-chevron-right" style="margin-left: 15px; color: #ccc;"></i>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fa-solid fa-inbox"></i>
            <h4>No history found</h4>
            <p>You don't have any completed or cancelled reservations yet.</p>
        </div>
    @endforelse
</div>
@endsection

@section('modals')
<div class="modal-overlay" id="historyDetailsModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeGlobalModal('historyDetailsModal')">&times;</button>
        <h2 class="modal-title" style="font-size: 20px; color: var(--primary-blue); margin-bottom: 20px;">Reservation Details</h2>
        
        <div style="margin-bottom: 20px; text-align: center;">
            <h3 id="hd-title" style="margin: 0; color: var(--primary-blue);"></h3>
            <span id="hd-badge" style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; margin-top: 5px;"></span>
        </div>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; gap: 10px; margin-bottom: 10px; font-size: 14px; color: var(--text-gray);">
                <i class="fa-regular fa-calendar" style="width: 20px; text-align: center;"></i>
                <span id="hd-date"></span>
            </div>
            <div style="display: flex; gap: 10px; font-size: 14px; color: var(--text-gray);">
                <i class="fa-regular fa-clock" style="width: 20px; text-align: center;"></i>
                <span id="hd-time"></span>
            </div>
        </div>

        <!-- Refund Section -->
        <div id="hd-refund-section" style="display: none; background: #fff5f5; border: 1px solid #fed7d7; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left;">
            <h4 style="margin: 0 0 10px 0; color: #c53030; font-size: 14px;">Refund Details</h4>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px;">
                <span style="color: var(--text-gray);">Status:</span>
                <strong id="hd-refund-status" style="text-transform: capitalize;"></strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 13px;">
                <span style="color: var(--text-gray);">Amount Requested:</span>
                <strong id="hd-refund-amount"></strong>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 10px;">
            <img id="hd-qr" src="" alt="QR" width="100" height="100" style="border-radius: 8px; border: 1px solid #eee; margin-bottom: 10px;">
            <span style="display: block; font-size: 11px; color: var(--text-gray); margin-top: 5px;">Reservation Code: <strong id="hd-code"></strong></span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.tab-link').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab-link').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.innerText.toLowerCase().trim();
            const cards = document.querySelectorAll('.history-card');
            
            cards.forEach(card => {
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

    document.getElementById('searchBar').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        const cards = document.querySelectorAll('.history-card');
        const activeTab = document.querySelector('.tab-link.active').innerText.toLowerCase().trim();

        cards.forEach(card => {
            const badge = card.querySelector('.badge').innerText.toLowerCase().trim();
            const text = card.getAttribute('data-search');
            
            const matchesTab = (activeTab === 'all' || badge === activeTab);
            const matchesSearch = text.includes(term);

            if (matchesTab && matchesSearch) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    function openHistoryDetails(id, title, date, time, code, status, refundStatus, refundAmount) {
        document.getElementById('hd-title').innerText = title;
        document.getElementById('hd-date').innerText = date;
        document.getElementById('hd-time').innerText = time;
        document.getElementById('hd-code').innerText = code;
        
        const badge = document.getElementById('hd-badge');
        badge.innerText = status;
        if(status === 'Completed') {
            badge.style.backgroundColor = '#e0e7ff'; badge.style.color = '#4338ca';
        } else if(status === 'Cancelled') {
            badge.style.backgroundColor = '#fee2e2'; badge.style.color = '#dc2626';
        }

        const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" + encodeURIComponent(code);
        document.getElementById('hd-qr').src = qrUrl;

        // Refund Logic
        const refundSection = document.getElementById('hd-refund-section');
        if (refundStatus && refundStatus !== 'null' && refundStatus !== '') {
            refundSection.style.display = 'block';
            document.getElementById('hd-refund-status').innerText = refundStatus;
            document.getElementById('hd-refund-amount').innerText = '₱ ' + parseFloat(refundAmount).toFixed(2);
            
            const rsElem = document.getElementById('hd-refund-status');
            if (refundStatus === 'refunded') {
                rsElem.style.color = '#16a34a';
                refundSection.style.backgroundColor = '#f0fdf4';
                refundSection.style.borderColor = '#bbf7d0';
            } else if (refundStatus === 'rejected') {
                rsElem.style.color = '#dc2626';
                refundSection.style.backgroundColor = '#fef2f2';
                refundSection.style.borderColor = '#fecaca';
            } else {
                rsElem.style.color = '#d97706';
                refundSection.style.backgroundColor = '#fffbeb';
                refundSection.style.borderColor = '#fde68a';
            }
        } else {
            refundSection.style.display = 'none';
        }

        document.getElementById('historyDetailsModal').style.display = 'flex';
    }
</script>
@endsection