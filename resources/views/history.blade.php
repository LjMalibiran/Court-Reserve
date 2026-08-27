@extends('layouts.app')

@section('title', 'History | Court Reserve')
@section('header_title', 'History')

@section('styles')
<style>
    /* Tabs */
    .tabs { display: flex; gap: 15px; margin-bottom: 25px; border-bottom: none; overflow-x: auto; padding-bottom: 5px; }
    .tab-link { color: var(--text-gray); font-weight: 600; text-decoration: none; cursor: pointer; padding: 10px 20px; border-radius: 25px; background: white; border: 1px solid #ddd; transition: 0.2s; white-space: nowrap; }
    .tab-link:hover { background: var(--light-blue); }
    .tab-link.active { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }

    /* Search Bar */
    .search-container { display: flex; gap: 10px; margin-bottom: 30px; }
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
</style>
@endsection

@section('content')
<div class="tabs">
    <a class="tab-link active">All</a>
    <a class="tab-link">Completed</a>
    <a class="tab-link">Cancelled</a>
</div>

<div class="search-container">
    <input type="text" class="search-bar" id="searchBar" placeholder="Search reservations...">
    <button class="filter-btn"><i class="fa-solid fa-filter"></i></button>
</div>

<div class="history-list">
    @forelse($historyReservations ?? collect() as $res)
        <div class="history-card" data-search="{{ strtolower($res->sport . ' ' . $res->court_id . ' ' . $res->reservation_code) }}" onclick="openNotificationDetails({{ $res->id }}, '{{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->reservation_code }}', '{{ ucfirst($res->status) }}')">
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
</script>
@endsection