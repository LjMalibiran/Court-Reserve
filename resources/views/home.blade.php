@extends('layouts.app')

@section('title', 'Dashboard | Court Reserve')
@section('header_title', 'Home')

@section('content')
<!-- Inline styles directly inside content to bypass layout yield issues -->
<style>
    .welcome-card { background: #ffffff !important; border-radius: 20px !important; padding: 32px 40px !important; display: flex !important; justify-content: space-between !important; align-items: center !important; box-shadow: 0 4px 20px rgba(15, 43, 110, 0.05) !important; margin-bottom: 24px !important; border: 1.5px solid #e2e8f0 !important; }
    .welcome-text h2 { margin: 0 !important; font-weight: 500 !important; color: #0f2b6e !important; font-size: 24px !important; line-height: 1.2 !important; }
    .welcome-text h2 strong { font-size: 40px !important; display: block !important; margin-top: 4px !important; font-weight: 800 !important; color: #0f2b6e !important; letter-spacing: -0.5px !important; }
    .welcome-text p { color: #94a3b8 !important; margin: 8px 0 0 0 !important; font-size: 15px !important; }
    
    .sport-buttons { display: flex !important; gap: 20px !important; }
    .sport-btn { background: #ffffff !important; border: 1.5px solid #e2e8f0 !important; padding: 20px 36px !important; border-radius: 20px !important; cursor: pointer !important; box-shadow: 0 8px 24px rgba(0,0,0,0.06) !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; transition: transform 0.2s, box-shadow 0.2s !important; text-decoration: none !important; min-width: 150px !important; box-sizing: border-box !important; }
    .sport-btn:hover { transform: translateY(-4px) !important; box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important; }
    .sport-btn span { font-size: 20px !important; font-weight: 700 !important; margin-top: 10px !important; }
    .sport-btn.badminton span { color: #0033cc !important; }
    .sport-btn.pickleball span { color: #ea580c !important; }

    .reservations-grid { display: grid !important; grid-template-columns: 1fr 1.15fr !important; gap: 24px !important; }
    .panel { background: #ffffff !important; border-radius: 20px !important; padding: 28px 32px !important; box-shadow: 0 4px 18px rgba(15, 43, 110, 0.04) !important; border: 1.5px solid #e2e8f0 !important; display: flex !important; flex-direction: column !important; position: relative !important; }
    .panel-header { display: flex !important; justify-content: space-between !important; align-items: center !important; margin-bottom: 24px !important; }
    .panel-header h3 { margin: 0 !important; color: #0f2b6e !important; font-size: 20px !important; font-weight: 700 !important; }
    .panel-header a { color: #0033cc !important; font-size: 13px !important; text-decoration: none !important; font-weight: 600 !important; }
    
    .badge-confirmed { background-color: #dcfce7 !important; color: #15803d !important; padding: 6px 16px !important; border-radius: 6px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-block !important; }
    .badge-pending { background-color: #fef3c7 !important; color: #d97706 !important; padding: 6px 16px !important; border-radius: 6px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-block !important; }
    .badge-cancelled { background-color: #fee2e2 !important; color: #dc2626 !important; padding: 6px 16px !important; border-radius: 6px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-block !important; }

    .today-res-wrapper { flex-grow: 1; display: flex; flex-direction: column; }
    .today-res-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
    .today-res-title-group { display: flex; align-items: flex-start; gap: 16px; }
    .today-res-title-text h4 { margin: 0; color: #0f2b6e; font-size: 22px; font-weight: 700; line-height: 1.2; }
    .today-res-title-text div { font-weight: 500; font-size: 18px; color: #0f2b6e; margin-top: 4px; }
    .today-res-qr { text-align: center; cursor: pointer; display: flex; flex-direction: column; align-items: center; }
    .today-res-qr img { width: 90px; height: 90px; border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px; }
    .today-res-qr span { display: block; font-size: 11px; color: #94a3b8; margin-top: 6px; }
    .today-res-datetime { display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px; color: #475569; font-size: 14px; font-weight: 500; }
    .datetime-row { display: flex; align-items: center; gap: 14px; }
    .datetime-row i { font-size: 18px; color: #64748b; width: 20px; text-align: center; }
    
    .today-res-actions { margin-top: auto; padding-top: 10px; display: flex; flex-direction: column; gap: 12px; align-items: center; }
    .btn-today-edit { background: white; border: 1.5px solid #0033cc; color: #0033cc; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 100%; max-width: 260px; text-align: center; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-today-edit:hover { background: #e0e7ff; }
    .btn-today-cancel { background: white; border: 1.5px solid #b91c1c; color: #b91c1c; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 100%; max-width: 260px; text-align: center; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-today-cancel:hover { background: #fee2e2; }
    .divider { border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0; }

    .empty-state { flex-grow: 1; display: flex; justify-content: center; align-items: center; text-align: center; color: #cbd5e1; font-size: 20px; font-weight: 700; letter-spacing: 1px; min-height: 200px; }

    .compact-res-card { border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 16px 20px; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: 0.2s; background: white; }
    .compact-res-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    .crc-left { display: flex; align-items: center; gap: 16px; }
    .crc-icon { width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; }
    .crc-details h4 { margin: 0; color: #0f2b6e; font-size: 16px; font-weight: 700; line-height: 1.2; }
    .crc-details .crc-court { font-size: 14px; color: #0f2b6e; font-weight: 500; margin-top: 2px; }
    .crc-details p { margin: 6px 0 0 0; font-size: 12px; color: #64748b; font-weight: 500; }
    .crc-right { display: flex; align-items: center; gap: 14px; }
    .crc-chevron { color: #94a3b8; font-size: 14px; }

    .time-slots { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; margin-top: 10px; }
    .time-slot { border: 1px solid #ddd; border-radius: 6px; padding: 8px; text-align: center; font-size: 12px; cursor: pointer; color: #0033cc; transition: 0.2s; }
    .time-slot.selected { background: #0033cc; color: white; border-color: #0033cc; }
    .time-slot.booked { background: #f0f0f0; color: #aaa; text-decoration: line-through; cursor: not-allowed; border-color: #eee; }
    .cancel-circle { background: #dc2626; color: white; width: 70px; height: 70px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 35px; margin: 0 auto 20px auto; border: 5px solid #fee2e2; }

    @media (max-width: 768px) {
        .welcome-card { flex-direction: column !important; text-align: center !important; gap: 24px !important; padding: 20px 10px !important; }
        .reservations-grid { display: flex !important; flex-direction: column !important; gap: 24px !important; }
    }
</style>

<div class="welcome-card">
    <div class="welcome-text">
        <h2>Welcome back, <strong>{{ Auth::user()->name ?? 'Player' }}!</strong></h2>
        <p>What sport are you playing today?</p>
    </div>
    <div class="sport-buttons">
        <a href="{{ route('reservation.index') }}?sport=Badminton" class="sport-btn badminton">
            <svg width="56" height="56" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(6, 6) rotate(-25 26 26)">
                    <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/>
                    <path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                    <path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                    <path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                    <path d="M14 20C20 23 32 23 38 20" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                    <path d="M16 28C21 31 31 31 36 28" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                    <rect x="18" y="36" width="16" height="3" rx="1" fill="#0033cc"/>
                    <path d="M18 39C18 44.5 21.5 48 26 48C30.5 48 34 44.5 34 39H18Z" fill="#0033cc"/>
                </g>
            </svg>
            <span>Badminton</span>
        </a>
        <a href="{{ route('reservation.index') }}?sport=Pickleball" class="sport-btn pickleball">
            <svg width="56" height="56" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="24" fill="#f97316"/>
                <circle cx="32" cy="32" r="24" stroke="#ea580c" stroke-width="2"/>
                <circle cx="32" cy="18" r="3.2" fill="#ffffff"/>
                <circle cx="21" cy="23" r="3.2" fill="#ffffff"/>
                <circle cx="43" cy="23" r="3.2" fill="#ffffff"/>
                <circle cx="16" cy="32" r="3.2" fill="#ffffff"/>
                <circle cx="32" cy="32" r="3.5" fill="#ffffff"/>
                <circle cx="48" cy="32" r="3.2" fill="#ffffff"/>
                <circle cx="21" cy="41" r="3.2" fill="#ffffff"/>
                <circle cx="43" cy="41" r="3.2" fill="#ffffff"/>
                <circle cx="32" cy="46" r="3.2" fill="#ffffff"/>
            </svg>
            <span>Pickleball</span>
        </a>
    </div>
</div>

<div class="reservations-grid">
    <!-- Today's Reservation Panel -->
    <div class="panel">
        <div class="panel-header">
            <h3>Today's Reservation</h3>
            @if(count($todayReservations) > 0)
                @php
                    $firstToday = $todayReservations->first();
                    $badgeClass = $firstToday->status == 'confirmed' ? 'badge-confirmed' : ($firstToday->status == 'cancelled' ? 'badge-cancelled' : 'badge-pending');
                @endphp
                <span class="{{ $badgeClass }}">{{ ucfirst($firstToday->status) }}</span>
            @endif
        </div>
        
        @forelse($todayReservations as $res)
            <div class="today-res-wrapper">
                <div class="today-res-top">
                    <div class="today-res-title-group">
                        <div>
                            @if(($res->sport ?? 'Badminton') == 'Pickleball')
                                <svg width="44" height="44" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="32" r="24" fill="#f97316"/>
                                    <circle cx="32" cy="32" r="24" stroke="#ea580c" stroke-width="2"/>
                                    <circle cx="32" cy="18" r="3.2" fill="#ffffff"/>
                                    <circle cx="21" cy="23" r="3.2" fill="#ffffff"/>
                                    <circle cx="43" cy="23" r="3.2" fill="#ffffff"/>
                                    <circle cx="16" cy="32" r="3.2" fill="#ffffff"/>
                                    <circle cx="32" cy="32" r="3.5" fill="#ffffff"/>
                                    <circle cx="48" cy="32" r="3.2" fill="#ffffff"/>
                                    <circle cx="21" cy="41" r="3.2" fill="#ffffff"/>
                                    <circle cx="43" cy="41" r="3.2" fill="#ffffff"/>
                                    <circle cx="32" cy="46" r="3.2" fill="#ffffff"/>
                                </svg>
                            @else
                                <svg width="44" height="44" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="translate(6, 6) rotate(-25 26 26)">
                                        <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/>
                                        <path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M14 20C20 23 32 23 38 20" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M16 28C21 31 31 31 36 28" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <rect x="18" y="36" width="16" height="3" rx="1" fill="#0033cc"/>
                                        <path d="M18 39C18 44.5 21.5 48 26 48C30.5 48 34 44.5 34 39H18Z" fill="#0033cc"/>
                                    </g>
                                </svg>
                            @endif
                        </div>
                        <div class="today-res-title-text">
                            <h4>{{ $res->sport ?? 'Badminton' }}</h4>
                            <div>Court {{ $res->court_id }}</div>
                        </div>
                    </div>
                    <div class="today-res-qr" onclick="openResDetails({{ $res->id }}, '{{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }}', '{{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->sport ?? 'Badminton' }}', '{{ $res->reservation_code }}', '{{ $res->status }}')">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($res->reservation_code) }}" alt="QR Code">
                        <span>Tap to view QR</span>
                    </div>
                </div>

                <div class="today-res-datetime">
                    <div class="datetime-row">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($res->start_time)->format('D, F j, Y') }}</span>
                    </div>
                    <div class="datetime-row">
                        <i class="fa-regular fa-clock"></i>
                        <span>{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }} | {{ max(1, \Carbon\Carbon::parse($res->start_time)->diffInHours(\Carbon\Carbon::parse($res->end_time))) }} hr</span>
                    </div>
                </div>
                
                <div class="today-res-actions">
                    <button class="btn-today-edit" onclick="openEditModal({{ $res->id }}, '{{ $res->sport ?? 'Badminton' }}')">Edit Reservation</button>
                    @if($res->status != 'cancelled')
                        <button class="btn-today-cancel" onclick="openCancelModal({{ $res->id }}, '{{ $res->reservation_code }}')">Cancel Reservation</button>
                    @endif
                </div>
            </div>
            @if(!$loop->last) <hr class="divider"> @endif
        @empty
            <div class="empty-state">
                NO RESERVATION
            </div>
        @endforelse
    </div>

    <!-- Upcoming Reservation Panel -->
    <div class="panel">
        <div class="panel-header">
            <h3>Upcoming Reservation</h3>
            <a href="{{ route('history.index') }}">View All</a>
        </div>
        
        <div style="display: flex; flex-direction: column; flex-grow: 1;">
            @forelse($upcomingReservations as $res)
                @php
                    $badgeClass = $res->status == 'confirmed' ? 'badge-confirmed' : ($res->status == 'cancelled' ? 'badge-cancelled' : 'badge-pending');
                @endphp
                <div class="compact-res-card" onclick="openResDetails({{ $res->id }}, '{{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }}', '{{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->sport ?? 'Badminton' }}', '{{ $res->reservation_code }}', '{{ $res->status }}')">
                    <div class="crc-left">
                        <div class="crc-icon">
                            @if(($res->sport ?? 'Badminton') == 'Pickleball')
                                <svg width="36" height="36" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="32" r="24" fill="#f97316"/>
                                    <circle cx="32" cy="32" r="24" stroke="#ea580c" stroke-width="2"/>
                                    <circle cx="32" cy="18" r="3.2" fill="#ffffff"/>
                                    <circle cx="21" cy="23" r="3.2" fill="#ffffff"/>
                                    <circle cx="43" cy="23" r="3.2" fill="#ffffff"/>
                                    <circle cx="16" cy="32" r="3.2" fill="#ffffff"/>
                                    <circle cx="32" cy="32" r="3.5" fill="#ffffff"/>
                                    <circle cx="48" cy="32" r="3.2" fill="#ffffff"/>
                                    <circle cx="21" cy="41" r="3.2" fill="#ffffff"/>
                                    <circle cx="43" cy="41" r="3.2" fill="#ffffff"/>
                                    <circle cx="32" cy="46" r="3.2" fill="#ffffff"/>
                                </svg>
                            @else
                                <svg width="36" height="36" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="translate(6, 6) rotate(-25 26 26)">
                                        <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/>
                                        <path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M14 20C20 23 32 23 38 20" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M16 28C21 31 31 31 36 28" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                                        <rect x="18" y="36" width="16" height="3" rx="1" fill="#0033cc"/>
                                        <path d="M18 39C18 44.5 21.5 48 26 48C30.5 48 34 44.5 34 39H18Z" fill="#0033cc"/>
                                    </g>
                                </svg>
                            @endif
                        </div>
                        <div class="crc-details">
                            <h4>{{ $res->sport ?? 'Badminton' }}</h4>
                            <div class="crc-court">Court {{ $res->court_id }}</div>
                            <p>{{ \Carbon\Carbon::parse($res->start_time)->format('F j, Y | g:i A') }}</p>
                        </div>
                    </div>
                    <div class="crc-right">
                        <span class="{{ $badgeClass }}">{{ ucfirst($res->status) }}</span>
                        <i class="fa-solid fa-chevron-right crc-chevron"></i>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    NO RESERVATION
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection