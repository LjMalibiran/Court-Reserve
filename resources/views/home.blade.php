@extends('layouts.app')

@section('title', 'Dashboard | Court Reserve')
@section('header_title', 'Home')

@section('styles')
<style>
    /* Welcome Card */
    .welcome-card { background: #ffffff; border-radius: 20px; padding: 32px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 20px rgba(15, 43, 110, 0.04); margin-bottom: 24px; border: 1px solid #e2e8f0; }
    .welcome-text h2 { margin: 0; font-weight: 500; color: #0f2b6e; font-size: 22px; line-height: 1.2; }
    .welcome-text h2 strong { font-size: 42px; display: block; margin-top: 4px; font-weight: 800; color: #0f2b6e; letter-spacing: -0.5px; }
    .welcome-text p { color: #94a3b8; margin: 8px 0 0 0; font-size: 15px; font-weight: 400; }
    
    .sport-buttons { display: flex; gap: 24px; }
    .sport-btn { background: #ffffff; border: 1px solid #e2e8f0; padding: 20px 36px; border-radius: 20px; cursor: pointer; box-shadow: 0 10px 30px rgba(0,0,0,0.06); display: flex; flex-direction: column; align-items: center; justify-content: center; transition: transform 0.2s, box-shadow 0.2s; text-decoration: none; min-width: 160px; box-sizing: border-box; }
    .sport-btn:hover { transform: translateY(-4px); box-shadow: 0 14px 35px rgba(0,0,0,0.1); }
    .sport-btn span { font-size: 22px; font-weight: 700; margin-top: 12px; }
    .sport-btn.badminton span { color: #0033cc; }
    .sport-btn.pickleball span { color: #ea580c; }

    /* Grid Layout */
    .reservations-grid { display: grid; grid-template-columns: 1fr 1.15fr; gap: 24px; }
    .panel { background: #ffffff; border-radius: 20px; padding: 28px 32px; box-shadow: 0 4px 18px rgba(15, 43, 110, 0.03); border: 1px solid #e2e8f0; display: flex; flex-direction: column; position: relative; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .panel-header h3 { margin: 0; color: #0f2b6e; font-size: 20px; font-weight: 700; }
    .panel-header a { color: #0033cc; font-size: 13px; text-decoration: none; font-weight: 600; transition: 0.2s; }
    .panel-header a:hover { text-decoration: underline; }
    
    /* Badges */
    .badge-confirmed { background-color: #dcfce7; color: #15803d; padding: 6px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-pending { background-color: #fef3c7; color: #d97706; padding: 6px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-cancelled { background-color: #fee2e2; color: #dc2626; padding: 6px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; }

    /* Today's Reservation Internal Layout */
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
    
    /* Action Buttons */
    .today-res-actions { margin-top: auto; padding-top: 10px; display: flex; flex-direction: column; gap: 12px; align-items: center; }
    .btn-today-edit { background: white; border: 1.5px solid #0033cc; color: #0033cc; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 100%; max-width: 260px; text-align: center; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-today-edit:hover { background: #e0e7ff; }
    .btn-today-cancel { background: white; border: 1.5px solid #b91c1c; color: #b91c1c; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 100%; max-width: 260px; text-align: center; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-today-cancel:hover { background: #fee2e2; }
    .divider { border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0; }

    /* Empty State */
    .empty-state { flex-grow: 1; display: flex; justify-content: center; align-items: center; text-align: center; color: #cbd5e1; font-size: 20px; font-weight: 700; letter-spacing: 1px; min-height: 200px; }

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

    /* Modals CSS */
    .time-slots { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; margin-top: 10px; }
    .time-slot { border: 1px solid #ddd; border-radius: 6px; padding: 8px; text-align: center; font-size: 12px; cursor: pointer; color: var(--primary-blue); transition: 0.2s; }
    .time-slot.selected { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
    .time-slot.booked { background: #f0f0f0; color: #aaa; text-decoration: line-through; cursor: not-allowed; border-color: #eee; }
    .cancel-circle { background: var(--danger-red); color: white; width: 70px; height: 70px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 35px; margin: 0 auto 20px auto; border: 5px solid var(--danger-light); }
    
    @media (max-width: 768px) {
        .welcome-card { flex-direction: column; text-align: center; gap: 24px; margin-bottom: 30px; background: transparent; border: none; box-shadow: none; padding: 20px 10px; }
        .reservations-grid { display: flex; flex-direction: column; gap: 24px; }
    }
</style>
@endsection

@section('content')
<div class="welcome-card">
    <div class="welcome-text">
        <h2>Welcome back, <strong>{{ Auth::user()->name ?? 'Player' }}!</strong></h2>
        <p>What sport are you playing today?</p>
    </div>
    <div class="sport-buttons">
        <a href="{{ route('reservation.index') }}?sport=Badminton" class="sport-btn badminton">
            <svg width="60" height="60" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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
            <svg width="60" height="60" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                <svg width="46" height="46" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                <svg width="46" height="46" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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

@section('modals')
<!-- Cancel Reservation Modal -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeGlobalModal('cancelModal')">&times;</button>
        <h2 class="modal-title" style="color: var(--danger-red);">Cancel Reservation</h2>
        
        <div style="margin-bottom: 15px;">
            <span id="cancel-res-code" style="color: var(--primary-blue); font-weight: bold; font-size: 14px;"></span>
            <div class="res-title" id="cancel-res-title" style="font-size: 16px; font-weight: bold; margin-top: 5px;"></div>
            <div style="font-size: 13px; color: var(--text-gray); margin-top: 5px;">
                <i class="fa-regular fa-calendar" style="width: 15px;"></i> <span id="cancel-res-date"></span>
            </div>
            <div style="font-size: 13px; color: var(--text-gray); margin-top: 5px;">
                <i class="fa-regular fa-clock" style="width: 15px;"></i> <span id="cancel-res-time"></span>
            </div>
        </div>

        <form id="cancelForm" method="POST" action="">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="font-size: 12px; color: var(--text-gray); display: block; margin-bottom: 5px;">Please select a reason for cancellation</label>
                <select name="reason" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; outline: none; font-family: inherit;">
                    <option>Schedule Conflict</option>
                    <option>Weather Conditions</option>
                    <option>Personal Emergency</option>
                    <option>Other</option>
                </select>
            </div>

            <div style="background: var(--danger-light); color: var(--danger-red); padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                <strong>Refund Policy</strong><br>
                Payment will only be refunded if you cancel at least 5 hours before your reservation.<br>
                No refund for late cancellation.
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn-outline-blue" onclick="closeGlobalModal('cancelModal')">Keep Reservation</button>
                <button type="button" class="btn-primary-solid" style="background: var(--danger-red); width: 100%;" onclick="submitCancel()">Confirm Cancellation</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Reservation Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content" style="max-width: 500px;">
        <button class="modal-close" onclick="closeGlobalModal('editModal')">&times;</button>
        <h2 class="modal-title">Edit Reservation</h2>
        
        <div style="margin-bottom: 20px;">
            <div class="res-title" id="edit-res-title" style="font-size: 16px; font-weight: bold;"></div>
            <span style="padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; background: #d1fae5; color: #059669; margin-top: 5px; display: inline-block;">Confirmed</span>
        </div>

        <form id="editForm" method="POST" action="">
            @csrf
            <div style="margin-bottom: 20px;">
                <h4 style="margin: 0 0 10px 0; color: var(--primary-blue);">Details</h4>
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label style="font-size: 12px; color: var(--text-gray);">Date</label>
                        <input type="date" id="edit-date" name="reservation_date" required onchange="checkAvailability()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; outline: none;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 12px; color: var(--text-gray);">Duration</label>
                        <select id="edit-duration" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; outline: none; background: #f9f9f9; cursor: not-allowed;" disabled>
                            <option>1 Hour</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-size: 12px; color: var(--text-gray);">Available Time Slot</label>
                <div class="time-slots" id="edit-time-slots">
                    <!-- Filled by JS -->
                </div>
            </div>

            <input type="hidden" id="edit-start-time" name="start_time" required>
            <input type="hidden" id="edit-end-time" name="end_time" required>
            <input type="hidden" id="edit-court-id" name="court_id">

            <button type="submit" class="btn-primary-solid" style="width: 100%;">Save Changes</button>
        </form>
    </div>
</div>

<!-- Success Cancel Modal -->
<div class="modal-overlay" id="successCancelModal">
    <div class="modal-content" style="text-align: center;">
        <div class="cancel-circle">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <h2 class="modal-title" style="color: var(--danger-red);">Reservation Cancelled</h2>
        <p class="notification-msg" style="margin-bottom: 20px; font-size: 14px;">
            Your reservation for<br>
            <strong style="color: var(--primary-blue);" id="success-cancel-title"></strong><br>
            on <strong style="color: var(--primary-blue);" id="success-cancel-datetime"></strong><br>
            has been cancelled.
        </p>
        <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 20px;">A cancellation receipt has been sent to your email.</p>
        <button class="btn-primary-solid" style="width: 100%;" onclick="location.reload()">Done</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openResDetails(id, courtId, date, startTime, endTime, sport, code, status) {
        // Open the global notifDetailsModal
        openNotificationDetails(id, sport + ' Court ' + courtId, date, startTime + ' - ' + endTime, code, status);
        
        // Inject Edit/Cancel buttons if confirmed
        const actionsDiv = document.getElementById('nd-actions');
        if (status === 'confirmed') {
            actionsDiv.style.display = 'flex';
            actionsDiv.innerHTML = `
                <button class="btn-outline-blue" onclick="closeGlobalModal('notifDetailsModal'); openEditModal(${id}, '${courtId}', '${date}', '${startTime}', '${sport}', '${code}')">Edit Reservation</button>
                <button class="btn-outline-red" onclick="closeGlobalModal('notifDetailsModal'); openCancelModal(${id}, '${code}', '${sport} Court ${courtId}', '${date}', '${startTime} - ${endTime}')">Cancel Reservation</button>
            `;
        } else {
            actionsDiv.style.display = 'none';
        }
    }

    // Modal Handling
    function openCancelModal(id, code, title, date, time) {
        document.getElementById('cancelForm').action = '/reservations/' + id + '/cancel-user';
        document.getElementById('cancel-res-code').innerText = code;
        document.getElementById('cancel-res-title').innerHTML = title;
        document.getElementById('cancel-res-date').innerText = date;
        document.getElementById('cancel-res-time').innerText = time;
        
        // Store for success modal
        document.getElementById('success-cancel-title').innerHTML = title;
        document.getElementById('success-cancel-datetime').innerHTML = date + ' at ' + time.split(' - ')[0];

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

    let currentEditCourtId = null;
    let currentEditStartTimeStr = null;

    function openEditModal(id, courtId, date, startTime, sport, code) {
        document.getElementById('editForm').action = '/reservations/' + id + '/edit-user';
        document.getElementById('edit-res-title').innerHTML = sport + ' Court ' + courtId;
        
        // Convert 'M j, Y' to YYYY-MM-DD for date input
        const d = new Date(date);
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        
        document.getElementById('edit-date').value = `${yyyy}-${mm}-${dd}`;
        document.getElementById('edit-date').min = new Date().toISOString().split('T')[0];
        document.getElementById('edit-court-id').value = courtId;
        
        currentEditCourtId = courtId;
        
        // convert "4:00 PM" to "16:00"
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
                renderTimeSlots(data.booked_slots);
            });
    }

    function renderTimeSlots(bookedSlots) {
        const container = document.getElementById('edit-time-slots');
        container.innerHTML = '';
        
        const startHour = 8;
        const endHour = 22;

        for(let hour = startHour; hour < endHour; hour++) {
            let timeString24 = (hour < 10 ? '0' + hour : hour) + ':00';
            let suffix = hour >= 12 ? 'PM' : 'AM';
            let hour12 = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
            let timeString12 = `${hour12}:00 ${suffix}`;

            let div = document.createElement('div');
            div.className = 'time-slot';
            div.innerText = timeString12;
            div.dataset.time24 = timeString24;

            if(bookedSlots.includes(timeString12) && timeString24 !== currentEditStartTimeStr) {
                div.classList.add('booked');
            } else {
                div.onclick = function() {
                    document.querySelectorAll('#edit-time-slots .time-slot').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    document.getElementById('edit-start-time').value = this.dataset.time24 + ':00';
                    
                    let endH = parseInt(this.dataset.time24.split(':')[0]) + 1;
                    let endString24 = (endH < 10 ? '0' + endH : endH) + ':00:00';
                    document.getElementById('edit-end-time').value = endString24;
                };
            }

            if(timeString24 === currentEditStartTimeStr) {
                div.classList.add('selected');
                document.getElementById('edit-start-time').value = timeString24 + ':00';
                let endH = parseInt(timeString24.split(':')[0]) + 1;
                document.getElementById('edit-end-time').value = (endH < 10 ? '0' + endH : endH) + ':00:00';
            }

            container.appendChild(div);
        }
    }
</script>
@endsection