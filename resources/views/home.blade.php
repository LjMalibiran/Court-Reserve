@extends('layouts.app')

@section('title', 'Dashboard | Court Reserve')
@section('header_title', 'Home')

@section('content')
<style>
    /* Welcome Card */
    .welcome-card { background: #ffffff !important; border-radius: 20px !important; padding: 32px 40px !important; display: flex !important; justify-content: space-between !important; align-items: center !important; box-shadow: 0 4px 20px rgba(15, 43, 110, 0.05) !important; margin-bottom: 24px !important; border: 1px solid #e2e8f0 !important; }
    .welcome-text h2 { margin: 0 !important; font-weight: 500 !important; color: #0f2b6e !important; font-size: 24px !important; line-height: 1.2 !important; }
    .welcome-text h2 strong { font-size: 40px !important; display: block !important; margin-top: 4px !important; font-weight: 800 !important; color: #0f2b6e !important; letter-spacing: -0.5px !important; }
    .welcome-text p { color: #94a3b8 !important; margin: 8px 0 0 0 !important; font-size: 15px !important; }
    
    .sport-buttons { display: flex !important; gap: 20px !important; }
    .sport-btn { background: #ffffff !important; border: 1px solid #e2e8f0 !important; padding: 20px 36px !important; border-radius: 20px !important; cursor: pointer !important; box-shadow: 0 8px 24px rgba(0,0,0,0.06) !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; transition: transform 0.2s, box-shadow 0.2s !important; text-decoration: none !important; min-width: 150px !important; box-sizing: border-box !important; }
    .sport-btn:hover { transform: translateY(-4px) !important; box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important; }
    .sport-btn span { font-size: 20px !important; font-weight: 700 !important; margin-top: 10px !important; }
    .sport-btn.badminton span { color: #0033cc !important; }
    .sport-btn.pickleball span { color: #ea580c !important; }

    /* Grid Layout */
    .reservations-grid { display: grid !important; grid-template-columns: 1fr 1.15fr !important; gap: 24px !important; }
    .panel { background: #ffffff !important; border-radius: 20px !important; padding: 28px 32px !important; box-shadow: 0 4px 18px rgba(15, 43, 110, 0.04) !important; border: 1px solid #e2e8f0 !important; display: flex !important; flex-direction: column !important; position: relative !important; }
    .panel-header { display: flex !important; justify-content: space-between !important; align-items: center !important; margin-bottom: 24px !important; }
    .panel-header h3 { margin: 0 !important; color: #0f2b6e !important; font-size: 20px !important; font-weight: 700 !important; }
    .panel-header a { color: #0033cc !important; font-size: 13px !important; text-decoration: none !important; font-weight: 600 !important; }
    
    /* Scrollable Area */
    .scrollable-list { flex-grow: 1; overflow-y: auto; max-height: 480px; padding-right: 8px; display: flex; flex-direction: column; }
    .scrollable-list::-webkit-scrollbar { width: 6px; }
    .scrollable-list::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .scrollable-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .scrollable-list::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Badges */
    .badge-confirmed { background-color: #dcfce7 !important; color: #15803d !important; padding: 6px 16px !important; border-radius: 6px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-block !important; }
    .badge-pending { background-color: #fef3c7 !important; color: #d97706 !important; padding: 6px 16px !important; border-radius: 6px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-block !important; }
    .badge-cancelled { background-color: #fee2e2 !important; color: #dc2626 !important; padding: 6px 16px !important; border-radius: 6px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-block !important; }

    /* Today's Res Layout */
    .today-res-wrapper { display: flex; flex-direction: column; }
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
    
    .today-res-actions { padding-top: 10px; display: flex; flex-direction: column; gap: 12px; align-items: center; }
    .btn-today-edit { background: white; border: 1.5px solid #0033cc; color: #0033cc; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 100%; max-width: 260px; text-align: center; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-today-edit:hover { background: #e0e7ff; }
    .btn-today-cancel { background: white; border: 1.5px solid #b91c1c; color: #b91c1c; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 100%; max-width: 260px; text-align: center; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-today-cancel:hover { background: #fee2e2; }
    .divider { border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0; }

    .empty-state { display: flex; justify-content: center; align-items: center; text-align: center; color: #cbd5e1; font-size: 20px; font-weight: 700; letter-spacing: 1px; min-height: 200px; margin: auto; }

    /* Compact Res Card (Upcoming) */
    .compact-res-card { border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 20px; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: 0.2s; background: white; }
    .compact-res-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    .crc-left { display: flex; align-items: center; gap: 16px; }
    .crc-icon { width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; }
    .crc-details h4 { margin: 0; color: #0f2b6e; font-size: 16px; font-weight: 700; line-height: 1.2; }
    .crc-details .crc-court { font-size: 14px; color: #0f2b6e; font-weight: 500; margin-top: 2px; }
    .crc-details p { margin: 6px 0 0 0; font-size: 12px; color: #64748b; font-weight: 500; }
    .crc-right { display: flex; align-items: center; gap: 14px; }
    .crc-chevron { color: #94a3b8; font-size: 14px; }

    /* MODAL CSS */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 9999; padding: 20px; }
    .modal-content { background: white; border-radius: 20px; padding: 32px; width: 100%; max-width: 480px; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.15); }
    .modal-close { position: absolute; top: 24px; right: 24px; background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; padding: 0; line-height: 1; }
    .modal-header-title { text-align: center; font-size: 22px; font-weight: 700; margin: 0 0 24px 0; }
    
    .res-brief-card { border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 24px; }
    .res-brief-id { color: #0033cc; font-size: 13px; font-weight: 700; margin-bottom: 12px; }
    .res-brief-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
    .res-brief-info { display: flex; gap: 14px; align-items: center; }
    .res-brief-info h4 { margin: 0; color: #0f2b6e; font-size: 18px; font-weight: 700; }
    .res-brief-info div { color: #0f2b6e; font-size: 15px; font-weight: 500; margin-top: 2px;}
    .res-brief-details { color: #64748b; font-size: 13px; display: flex; flex-direction: column; gap: 8px; font-weight: 500;}
    
    .form-group-row { display: flex; gap: 16px; margin-bottom: 24px; }
    .form-group { flex: 1; }
    .section-label { display: block; font-size: 13px; color: #0f2b6e; margin-bottom: 8px; font-weight: 600; }
    .form-control { width: 100%; padding: 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 15px; outline: none; color: #0f2b6e; font-weight: 600; box-sizing: border-box;}
    
    .time-slot-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 24px; }
    .ts-btn { background: white; border: 1.5px solid #e2e8f0; padding: 12px 0; border-radius: 8px; color: #0033cc; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s; text-align: center;}
    .ts-btn.active { background: #0033cc; color: white; border-color: #0033cc; }
    .ts-btn.disabled { color: #cbd5e1; border-color: #f1f5f9; background: #f8fafc; pointer-events: none; }
    
    .rental-section { display: flex; justify-content: space-between; margin-bottom: 30px; }
    .rental-item { display: flex; align-items: center; justify-content: space-between; flex: 1; }
    .rental-item:first-child { margin-right: 24px; }
    .rental-info h5 { margin: 0 0 2px 0; font-size: 15px; color: #0f2b6e; font-weight: 700; }
    .rental-info span { font-size: 10px; color: #94a3b8; font-weight: 600;}
    .counter-widget { display: flex; align-items: center; border: 1.5px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
    .counter-btn { background: white; border: none; padding: 6px 12px; font-size: 16px; color: #0f2b6e; cursor: pointer; font-weight: 600;}
    .counter-val { padding: 6px 14px; font-size: 14px; font-weight: 600; color: #0f2b6e; border-left: 1.5px solid #e2e8f0; border-right: 1.5px solid #e2e8f0; min-width: 12px; text-align: center;}
    
    .refund-policy-box { background: #fef2f2; border-radius: 8px; padding: 18px; margin-bottom: 24px; }
    .refund-policy-box h4 { color: #b91c1c; margin: 0 0 8px 0; font-size: 16px; font-weight: 700;}
    .refund-policy-box p { color: #0f2b6e; margin: 0; font-size: 13px; line-height: 1.5; font-weight: 500;}
    .refund-policy-box p.no-refund { color: #0033cc; font-weight: 600; margin-top: 6px; }
    
    .btn-solid-blue { background: #0033cc; color: white; border: none; border-radius: 8px; padding: 14px; font-size: 15px; font-weight: 600; width: 100%; cursor: pointer; transition: 0.2s;}
    .btn-solid-blue:hover { background: #002299; }
    .btn-solid-red { background: #b91c1c; color: white; border: none; border-radius: 8px; padding: 14px; font-size: 15px; font-weight: 600; width: 100%; cursor: pointer; transition: 0.2s;}
    .btn-solid-red:hover { background: #991b1b; }
    .btn-outline-blue { background: white; color: #0033cc; border: 1.5px solid #0033cc; border-radius: 8px; padding: 14px; font-size: 15px; font-weight: 600; width: 100%; cursor: pointer; transition: 0.2s; box-sizing: border-box;}
    .btn-outline-blue:hover { background: #f0f4ff; }
    .btn-group { display: flex; gap: 14px; }

    .success-icon-wrap { position: relative; width: 90px; height: 90px; margin: 0 auto 24px auto; }
    .cancel-circle { background: #dc2626; color: white; width: 100%; height: 100%; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 40px; position: relative; z-index: 2; box-shadow: 0 0 0 6px #fee2e2;}
    .confetti { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 1; }
    .confetti::before, .confetti::after { content: ''; position: absolute; width: 6px; height: 6px; border-radius: 50%; }
    .confetti::before { background: #d97706; top: -10px; left: 10px; box-shadow: 60px 10px 0 #15803d, 80px 40px 0 #0033cc, -20px 40px 0 #ea580c; }
    .confetti::after { background: #0033cc; bottom: -10px; right: 10px; box-shadow: -60px -10px 0 #b91c1c, -80px -40px 0 #15803d, 20px -40px 0 #d97706; }

    @media (max-width: 768px) {
        .welcome-card { flex-direction: column !important; text-align: center !important; gap: 20px !important; padding: 24px 20px !important; height: auto !important;}
        .welcome-text h2 { font-size: 20px !important; }
        .welcome-text h2 strong { font-size: 32px !important; }
        .sport-buttons { width: 100% !important; gap: 12px !important; }
        .sport-btn { min-width: 0 !important; flex: 1 !important; padding: 16px 10px !important; }
        .sport-btn span { font-size: 16px !important; margin-top: 8px !important; }
        .sport-btn svg { width: 42px !important; height: 42px !important; }
        .reservations-grid { display: flex !important; flex-direction: column !important; gap: 20px !important; }
        .panel { padding: 24px 20px !important; }
        .time-slot-grid { grid-template-columns: repeat(3, 1fr); }
        .form-group-row { flex-direction: column; gap: 12px; }
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
    <div class="panel">
        <div class="panel-header">
            <h3>Today's Reservation</h3>
        </div>
        
        <div class="scrollable-list">
            @forelse($todayReservations as $res)
                @php
                    $badgeClass = $res->status == 'confirmed' ? 'badge-confirmed' : ($res->status == 'cancelled' ? 'badge-cancelled' : 'badge-pending');
                @endphp
                <div class="compact-res-card" onclick="openResDetailsModal({{ $res->id }}, '{{ $res->sport ?? 'Badminton' }}', '{{ $res->court_id }}', '{{ $res->start_time }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }}', '{{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->reservation_code }}', '{{ $res->status }}')">
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
                <div class="empty-state">NO RESERVATION</div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Reservation Panel -->
    <div class="panel">
        <div class="panel-header">
            <h3>Upcoming Reservation</h3>
            <a href="{{ route('history.index') }}">View All</a>
        </div>
        
        <div class="scrollable-list">
            @forelse($upcomingReservations as $res)
                @php
                    $badgeClass = $res->status == 'confirmed' ? 'badge-confirmed' : ($res->status == 'cancelled' ? 'badge-cancelled' : 'badge-pending');
                @endphp
                <div class="compact-res-card" onclick="openResDetailsModal({{ $res->id }}, '{{ $res->sport ?? 'Badminton' }}', '{{ $res->court_id }}', '{{ $res->start_time }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }}', '{{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->reservation_code }}', '{{ $res->status }}')">
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
                <div class="empty-state">NO RESERVATION</div>
            @endforelse
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- Reservation Details Modal -->
<div class="modal-overlay" id="resDetailsModal">
    <div class="modal-content">
        <button type="button" class="modal-close" onclick="closeGlobalModal('resDetailsModal')">&times;</button>
        <h2 class="modal-header-title" style="color: #0f2b6e;">Reservation Details</h2>
        
        <div class="res-brief-card">
            <div class="res-brief-id" id="detail-res-code"></div>
            <div class="res-brief-header">
                <div class="res-brief-info">
                    <svg width="28" height="28" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g transform="translate(6, 6) rotate(-25 26 26)">
                            <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/>
                            <path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                        </g>
                    </svg>
                    <div>
                        <h4 id="detail-res-sport">Badminton</h4>
                        <div id="detail-res-court">Court 1</div>
                    </div>
                </div>
                <span class="badge-confirmed" id="detail-res-badge">Confirmed</span>
            </div>
            <div class="res-brief-details">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-regular fa-calendar"></i> <span id="detail-res-date">Mon, June 1, 2026</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-regular fa-clock"></i> <span id="detail-res-time">4:00 - 5:00 PM | 1 hr</span>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <img id="detail-res-qr" src="" style="width: 120px; height: 120px; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 8px;">
            <div style="font-size: 11px; color: #94a3b8; margin-top: 6px;">Scan this QR code at the entrance</div>
        </div>

        <div id="detail-actions" style="display: flex; flex-direction: column; gap: 10px;">
            <!-- Buttons injected by JS based on status -->
        </div>
    </div>
</div>

<!-- Edit Reservation Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <button type="button" class="modal-close" onclick="closeGlobalModal('editModal')">&times;</button>
        <h2 class="modal-header-title" style="color: #0f2b6e;">Edit Reservation</h2>
        
        <div class="res-brief-card">
            <div class="res-brief-header">
                <div class="res-brief-info">
                    <svg width="28" height="28" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g transform="translate(6, 6) rotate(-25 26 26)">
                            <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/>
                            <path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                        </g>
                    </svg>
                    <div>
                        <h4 id="edit-res-sport">Badminton</h4>
                        <div id="edit-res-court">Court 1</div>
                    </div>
                </div>
                <span class="badge-confirmed" id="edit-res-badge">Confirmed</span>
            </div>
            <div class="res-brief-details">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-regular fa-calendar"></i> <span id="edit-res-date-display">Mon, June 1, 2026</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-regular fa-clock"></i> <span id="edit-res-time-display">4:00 - 5:00 PM | 1 hr</span>
                </div>
            </div>
        </div>

        <form id="editForm" method="POST" action="">
            @csrf
            <h4 class="section-label" style="font-size: 15px;">Details</h4>
            <div class="form-group-row">
                <div class="form-group">
                    <label class="section-label" style="color: #64748b; font-weight:500;">Date</label>
                    <input type="date" id="edit-date" name="reservation_date" class="form-control" required onchange="checkAvailability()">
                </div>
                <div class="form-group">
                    <label class="section-label" style="color: #64748b; font-weight:500;">Duration</label>
                    <select class="form-control" style="background: #f8fafc; cursor: not-allowed; color: #64748b;" disabled>
                        <option>1 Hour</option>
                    </select>
                </div>
            </div>

            <label class="section-label">Available Time Slot</label>
            <div class="time-slot-grid" id="edit-time-slots">
                <!-- Filled by JS -->
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label class="section-label" style="color: #64748b; font-weight:500;">Court</label>
                    <select class="form-control" id="edit-court-select" onchange="changeEditCourt()">
                        <option value="1">Court 1</option>
                        <option value="2">Court 2</option>
                        <option value="3">Court 3</option>
                    </select>
                </div>
            </div>

            <input type="hidden" id="edit-start-time" name="start_time" required>
            <input type="hidden" id="edit-end-time" name="end_time" required>
            <input type="hidden" id="edit-court-id" name="court_id">

            <button type="submit" class="btn-solid-blue">Save Changes</button>
        </form>
    </div>
</div>

<!-- Cancel Reservation Modal -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal-content">
        <button type="button" class="modal-close" onclick="closeGlobalModal('cancelModal')">&times;</button>
        <h2 class="modal-header-title" style="color: #b91c1c;">Cancel Reservation</h2>
        
        <div class="res-brief-card">
            <div class="res-brief-id" id="cancel-res-code">BC26-02</div>
            <div class="res-brief-header">
                <div class="res-brief-info">
                    <svg width="28" height="28" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g transform="translate(6, 6) rotate(-25 26 26)">
                            <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/>
                            <path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                        </g>
                    </svg>
                    <div>
                        <h4 id="cancel-res-sport">Badminton</h4>
                        <div id="cancel-res-court">Court 1</div>
                    </div>
                </div>
                <span class="badge-confirmed" id="cancel-res-badge">Confirmed</span>
            </div>
            <div class="res-brief-details">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-regular fa-calendar"></i> <span id="cancel-res-date">Mon, June 1, 2026</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-regular fa-clock"></i> <span id="cancel-res-time">4:00 - 5:00 PM | 1 Hour</span>
                </div>
            </div>
        </div>

        <form id="cancelForm" method="POST" action="">
            @csrf
            <label class="section-label" style="color: #64748b; font-weight: 500;">Please select a reason for cancellation</label>
            <select name="reason" class="form-control" style="margin-bottom: 24px;">
                <option>Schedule Conflict</option>
                <option>Weather Conditions</option>
                <option>Personal Emergency</option>
                <option>Other</option>
            </select>

            <div class="refund-policy-box">
                <h4>Refund Policy</h4>
                <p>Payment will only be refunded if you cancel at least 5 hours before your reservation.</p>
                <p class="no-refund">No refund for late cancellation.</p>
            </div>

            <div class="btn-group">
                <button type="button" class="btn-outline-blue" onclick="closeGlobalModal('cancelModal')">Keep Reservation</button>
                <button type="button" class="btn-solid-red" onclick="submitCancel()">Confirm Cancellation</button>
            </div>
        </form>
    </div>
</div>

<!-- Success Cancel Modal -->
<div class="modal-overlay" id="successCancelModal">
    <div class="modal-content" style="text-align: center; max-width: 400px;">
        <div class="success-icon-wrap">
            <div class="confetti"></div>
            <div class="cancel-circle">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        
        <h2 class="modal-header-title" style="color: #b91c1c; margin-bottom: 12px;">Reservation Cancelled</h2>
        <p style="color: #0f2b6e; font-size: 15px; line-height: 1.5; font-weight: 500; margin-bottom: 24px;">
            Your reservation for<br>
            <strong style="color: #0033cc; font-size: 16px;" id="success-cancel-title">Badminton Court 1</strong><br>
            on <strong style="color: #0033cc; font-size: 16px;" id="success-cancel-datetime">June 1, 2026 at 4:00 PM</strong><br>
            has been cancelled.
        </p>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 24px; font-weight: 500;">A cancellation receipt has been sent to your email.</p>
        <button class="btn-solid-blue" onclick="location.reload()">Done</button>
    </div>
</div>

<script>
    function closeGlobalModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Store current detail modal reservation data for passing to edit/cancel
    let detailResData = {};

    function openResDetailsModal(id, sport, courtId, date, startTime, endTime, code, status) {
        detailResData = { id, sport, courtId, date, startTime, endTime, code, status };

        document.getElementById('detail-res-code').innerText = code;
        document.getElementById('detail-res-sport').innerText = sport;
        document.getElementById('detail-res-court').innerText = 'Court ' + courtId;

        const d = new Date(date);
        const dateOptions = { weekday: 'short', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('detail-res-date').innerText = d.toLocaleDateString('en-US', dateOptions);
        const s = new Date("1970/01/01 " + startTime);
        const e = new Date("1970/01/01 " + endTime);
        let diff = (e - s) / 3600000;
        if (diff < 0) diff += 24;
        const durText = diff + (diff > 1 ? ' hrs' : ' hr');
        document.getElementById('detail-res-time').innerText = startTime + ' - ' + endTime + ' | ' + durText;

        // QR Code
        document.getElementById('detail-res-qr').src = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(code);

        // Badge
        const badge = document.getElementById('detail-res-badge');
        badge.innerText = status.charAt(0).toUpperCase() + status.slice(1);
        badge.className = '';
        if (status === 'confirmed') badge.className = 'badge-confirmed';
        else if (status === 'cancelled') badge.className = 'badge-cancelled';
        else badge.className = 'badge-pending';

        // Action buttons - Edit only when confirmed, Cancel when not cancelled
        const actionsDiv = document.getElementById('detail-actions');
        actionsDiv.innerHTML = '';

        if (status === 'confirmed') {
            actionsDiv.innerHTML += `<button class="btn-solid-blue" onclick="closeGlobalModal('resDetailsModal'); openEditModal(${id}, '${sport}', '${courtId}', '${date}', '${startTime}', '${endTime}')">Edit Reservation</button>`;
            actionsDiv.innerHTML += `<button class="btn-solid-red" onclick="closeGlobalModal('resDetailsModal'); openCancelModal(${id}, '${code}', '${sport}', '${courtId}', '${date}', '${startTime}', '${endTime}')">Cancel Reservation</button>`;
        } else if (status === 'pending') {
            actionsDiv.innerHTML += `<button class="btn-solid-red" onclick="closeGlobalModal('resDetailsModal'); openCancelModal(${id}, '${code}', '${sport}', '${courtId}', '${date}', '${startTime}', '${endTime}')">Cancel Reservation</button>`;
        }
        // If cancelled, no action buttons shown

        document.getElementById('resDetailsModal').style.display = 'flex';
    }

    function updateCounter(id, delta) {
        const span = document.getElementById(id + '-count');
        const input = document.getElementById(id + '-input');
        let val = parseInt(span.innerText) + delta;
        if(val < 0) val = 0;
        if(val > 10) val = 10;
        span.innerText = val;
        input.value = val;
    }

    let currentEditCourtId = null;
    let currentEditStartTimeStr = null;

    function changeEditCourt() {
        currentEditCourtId = document.getElementById('edit-court-select').value;
        document.getElementById('edit-court-id').value = currentEditCourtId;
        document.getElementById('edit-res-court').innerText = 'Court ' + currentEditCourtId;
        checkAvailability();
    }

    function openEditModal(id, sport, courtId, date, startTime, endTime) {
        document.getElementById('editForm').action = '/reservations/' + id + '/edit-user';
        document.getElementById('edit-res-sport').innerText = sport;
        document.getElementById('edit-res-court').innerText = 'Court ' + courtId;
        
        const d = new Date(date);
        const dateOptions = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
        document.getElementById('edit-res-date-display').innerText = d.toLocaleDateString('en-US', dateOptions);
        const s = new Date("1970/01/01 " + startTime);
        const e = new Date("1970/01/01 " + endTime);
        let diff = (e - s) / 3600000;
        if (diff < 0) diff += 24;
        const durText = diff + (diff > 1 ? ' hrs' : ' hr');
        document.getElementById('edit-res-time-display').innerText = startTime + ' - ' + endTime + ' | ' + durText;
        
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        document.getElementById('edit-date').value = `${yyyy}-${mm}-${dd}`;
        
        const nowTz = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
        const minYYYY = nowTz.getFullYear();
        const minMM = String(nowTz.getMonth() + 1).padStart(2, '0');
        const minDD = String(nowTz.getDate()).padStart(2, '0');
        document.getElementById('edit-date').min = `${minYYYY}-${minMM}-${minDD}`;
        
        document.getElementById('edit-court-id').value = courtId;
        
        currentEditCourtId = courtId;
        
        const courtSelect = document.getElementById('edit-court-select');
        courtSelect.innerHTML = `
            <option value="1">Court 1</option>
            <option value="2">Court 2</option>
            <option value="3">Court 3</option>
        `;
        courtSelect.value = courtId;
        
        let match = startTime.match(/(\d+):(\d+) (AM|PM)/);
        if(match) {
            let h = parseInt(match[1]);
            if(match[3] === 'PM' && h !== 12) h += 12;
            if(match[3] === 'AM' && h === 12) h = 0;
            currentEditStartTimeStr = (h < 10 ? '0' + h : h) + ':00';
        }

        checkAvailability();
        document.getElementById('editModal').style.display = 'flex';
    }

    function checkAvailability() {
        let date = document.getElementById('edit-date').value;
        let courtId = currentEditCourtId;

        if(!date || !courtId) return;

        fetch(`/api/check-availability?date=${date}&court_id=${courtId}`)
            .then(response => response.json())
            .then(data => {
                renderTimeSlots(data.booked_slots || []);
            });
    }

    function renderTimeSlots(bookedSlots) {
        const container = document.getElementById('edit-time-slots');
        container.innerHTML = '';
        
        const selectedDate = document.getElementById('edit-date').value;
        const now = new Date();
        const manilaTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
        const todayStr = manilaTime.getFullYear() + '-' + String(manilaTime.getMonth() + 1).padStart(2, '0') + '-' + String(manilaTime.getDate()).padStart(2, '0');
        const currentHour = manilaTime.getHours();

        const dObj = new Date(selectedDate);
        const dayOfWeek = dObj.getDay(); 
        const endHour = (dayOfWeek === 0) ? 14 : 21; 

        for(let hour = 7; hour <= endHour; hour++) {
            let timeString24 = (hour < 10 ? '0' + hour : hour) + ':00';
            let suffix = hour >= 12 ? 'PM' : 'AM';
            let hour12 = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
            let timeString12 = `${hour12}:00 ${suffix}`;

            let btn = document.createElement('div');
            btn.className = 'ts-btn';
            btn.innerText = timeString12;
            btn.dataset.time24 = timeString24;

            let isPast = (selectedDate === todayStr) && (hour <= currentHour);

            if((bookedSlots.includes(timeString12) || isPast) && timeString24 !== currentEditStartTimeStr) {
                btn.classList.add('disabled');
            } else {
                btn.onclick = function() {
                    document.querySelectorAll('#edit-time-slots .ts-btn').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                    
                    document.getElementById('edit-start-time').value = this.dataset.time24 + ':00';
                    let endH = parseInt(this.dataset.time24.split(':')[0]) + 1;
                    document.getElementById('edit-end-time').value = (endH < 10 ? '0' + endH : endH) + ':00:00';
                };
            }

            if(timeString24 === currentEditStartTimeStr) {
                btn.classList.add('active');
                document.getElementById('edit-start-time').value = timeString24 + ':00';
                let endH = parseInt(timeString24.split(':')[0]) + 1;
                document.getElementById('edit-end-time').value = (endH < 10 ? '0' + endH : endH) + ':00:00';
            }

            container.appendChild(btn);
        }
    }

    function openCancelModal(id, code, sport, courtId, date, startTime, endTime) {
        document.getElementById('cancelForm').action = '/reservations/' + id + '/cancel-user';
        document.getElementById('cancel-res-code').innerText = code;
        document.getElementById('cancel-res-sport').innerText = sport;
        document.getElementById('cancel-res-court').innerText = 'Court ' + courtId;
        
        const d = new Date(date);
        const dateOptions = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
        document.getElementById('cancel-res-date').innerText = d.toLocaleDateString('en-US', dateOptions);
        const s = new Date("1970/01/01 " + startTime);
        const e = new Date("1970/01/01 " + endTime);
        let diff = (e - s) / 3600000;
        if (diff < 0) diff += 24;
        const durText = diff + (diff > 1 ? ' hrs' : ' hr');
        document.getElementById('cancel-res-time').innerText = startTime + ' - ' + endTime + ' | ' + durText;
        
        document.getElementById('success-cancel-title').innerText = sport + ' Court ' + courtId;
        document.getElementById('success-cancel-datetime').innerText = d.toLocaleDateString('en-US', {month: 'long', day:'numeric', year:'numeric'}) + ' at ' + startTime;

        document.getElementById('cancelModal').style.display = 'flex';
    }

    function submitCancel() {
        document.getElementById('cancelModal').style.display = 'none';
        
        fetch(document.getElementById('cancelForm').action, {
            method: 'POST',
            body: new FormData(document.getElementById('cancelForm'))
        }).then(response => {
            if(response.ok) {
                document.getElementById('successCancelModal').style.display = 'flex';
            } else {
                alert('Error cancelling reservation.');
                location.reload();
            }
        });
    }

    // Real-time auto-refresh for reservations grid
    setInterval(() => {
        fetch('/home')
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('.reservations-grid');
                if (newGrid) {
                    document.querySelector('.reservations-grid').innerHTML = newGrid.innerHTML;
                    
                    // If details modal is open, refresh its data too
                    if (detailResData && detailResData.id && document.getElementById('resDetailsModal').style.display === 'flex') {
                        const updatedCard = newGrid.querySelector(`.compact-res-card[data-id="${detailResData.id}"]`);
                        if (updatedCard) {
                            const onclickStr = updatedCard.getAttribute('onclick');
                            if (onclickStr) {
                                // Extract the arguments from openResDetailsModal(...)
                                const argsMatch = onclickStr.match(/openResDetailsModal\((.*)\)/);
                                if (argsMatch && argsMatch[1]) {
                                    // Evaluate the arguments to pass them safely
                                    const args = new Function(`return [${argsMatch[1]}]`)();
                                    // If status changed, update the modal UI
                                    if (args[7] !== detailResData.status) {
                                        openResDetailsModal(...args);
                                    }
                                }
                            }
                        } else {
                            // If card no longer exists (e.g. cancelled and removed from today/upcoming), close modal
                            closeGlobalModal('resDetailsModal');
                        }
                    }
                }
            })
            .catch(err => console.error('Error auto-refreshing reservations:', err));
    }, 5000);
</script>
@endsection