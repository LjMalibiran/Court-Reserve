@extends('layouts.app')

@section('title', 'Dashboard | Court Reserve')
@section('header_title', 'Home')

@section('styles')
<style>
    /* Welcome Card */
    .welcome-card { background: white; border-radius: 12px; padding: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 60px; border: 1px solid #eaeaea; }
    .welcome-text h2 { margin: 0; font-weight: normal; color: var(--primary-blue); font-size: 20px;}
    .welcome-text h2 strong { font-size: 32px; display: block; margin-top: 5px; }
    .welcome-text p { color: var(--text-gray); margin: 5px 0 0 0; }
    
    .sport-buttons { display: flex; gap: 15px; }
    .sport-btn { background: white; border: 1px solid #ddd; padding: 15px 30px; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; gap: 10px; transition: 0.2s; text-decoration: none; }
    .sport-btn i { font-size: 30px; }
    .sport-btn.badminton { color: var(--primary-blue); }
    .sport-btn.pickleball { color: #f39c12; }
    .sport-btn:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }

    /* Grid Layout */
    .reservations-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
    .panel { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; min-height: 300px; display: flex; flex-direction: column; max-height: 400px; overflow-y: auto; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .panel-header h3 { margin: 0; color: var(--primary-blue); font-size: 18px; }
    
    /* Empty State */
    .empty-state { flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px; }
    .empty-state i { font-size: 50px; color: #e0e0e0; margin-bottom: 15px; }
    .empty-state h4 { color: var(--text-dark); margin: 0 0 8px 0; font-size: 18px; }
    .empty-state p { color: var(--text-gray); margin: 0 0 20px 0; font-size: 14px; }

    /* Compact Res Card (Like Photo) */
    .compact-res-card { border: 1px solid #eee; border-radius: 8px; padding: 12px 15px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: 0.2s; background: white; }
    .compact-res-card:hover { border-color: #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .crc-left { display: flex; align-items: center; gap: 15px; }
    .crc-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 24px; }
    .crc-icon.pickleball { color: #f39c12; }
    .crc-details h4 { margin: 0; color: var(--primary-blue); font-size: 15px; }
    .crc-details p { margin: 2px 0 0 0; font-size: 12px; color: var(--text-gray); }
    .crc-right { display: flex; align-items: center; gap: 10px; }
    .crc-badge { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; }
    .crc-chevron { color: #ccc; font-size: 14px; }

    /* Modals CSS that are specific to home page (Edit/Cancel) */
    .time-slots { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; margin-top: 10px; }
    .time-slot { border: 1px solid #ddd; border-radius: 6px; padding: 8px; text-align: center; font-size: 12px; cursor: pointer; color: var(--primary-blue); transition: 0.2s; }
    .time-slot.selected { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
    .time-slot.booked { background: #f0f0f0; color: #aaa; text-decoration: line-through; cursor: not-allowed; border-color: #eee; }
    .cancel-circle { background: var(--danger-red); color: white; width: 70px; height: 70px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 35px; margin: 0 auto 20px auto; border: 5px solid var(--danger-light); }
    
    @media (max-width: 768px) {
        .welcome-card { flex-direction: column; text-align: center; gap: 20px; margin-bottom: 30px; background: transparent; border: none; box-shadow: none; padding: 20px 10px; }
        .reservations-grid { display: flex; flex-direction: column; gap: 20px; }
        .panel { max-height: none; }
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
            <img src="{{ asset('images/shuttlecock.png') }}" alt="Badminton Icon" width="60" style="margin-bottom: 5px;">
            Badminton
        </a>
        <a href="{{ route('reservation.index') }}?sport=Pickleball" class="sport-btn pickleball">
            <i class="fa-solid fa-table-tennis-paddle-ball" style="font-size: 45px; margin-bottom: 5px;"></i>
            Pickleball
        </a>
    </div>
</div>

<div class="reservations-grid">
    <!-- Today's Reservation Panel -->
    <div class="panel">
        <div class="panel-header">
            <h3>Today's Reservation</h3>
        </div>
        
        @forelse($todayReservations as $res)
            <div class="compact-res-card" onclick="openResDetails({{ $res->id }}, '{{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }}', '{{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->sport ?? 'Badminton' }}', '{{ $res->reservation_code }}', '{{ $res->status }}')">
                <div class="crc-left">
                    <div class="crc-icon {{ strtolower($res->sport ?? 'badminton') }}">
                        @if(($res->sport ?? 'Badminton') == 'Pickleball')
                            <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                        @else
                            <img src="{{ asset('images/shuttlecock.png') }}" width="30">
                        @endif
                    </div>
                    <div class="crc-details">
                        <h4>{{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}</h4>
                        <p>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y | g:i A') }}</p>
                    </div>
                </div>
                <div class="crc-right">
                    <span class="crc-badge" style="background-color: {{ $res->status == 'confirmed' ? '#d1fae5' : ($res->status == 'cancelled' ? '#fee2e2' : '#fef3c7') }}; color: {{ $res->status == 'confirmed' ? '#059669' : ($res->status == 'cancelled' ? '#dc2626' : '#d97706') }};">
                        {{ ucfirst($res->status) }}
                    </span>
                    <i class="fa-solid fa-chevron-right crc-chevron"></i>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-regular fa-calendar-xmark"></i>
                <h4>No reservations today</h4>
                <p>You don't have any court bookings for today.</p>
                <a href="{{ route('reservation.index') }}">
                    <button class="btn-primary-solid">Book a Court</button>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Upcoming Reservation Panel -->
    <div class="panel">
        <div class="panel-header">
            <h3>Upcoming Reservation</h3>
        </div>
        
        @forelse($upcomingReservations as $res)
            <div class="compact-res-card" onclick="openResDetails({{ $res->id }}, '{{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }}', '{{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}', '{{ $res->sport ?? 'Badminton' }}', '{{ $res->reservation_code }}', '{{ $res->status }}')">
                <div class="crc-left">
                    <div class="crc-icon {{ strtolower($res->sport ?? 'badminton') }}">
                        @if(($res->sport ?? 'Badminton') == 'Pickleball')
                            <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                        @else
                            <img src="{{ asset('images/shuttlecock.png') }}" width="30">
                        @endif
                    </div>
                    <div class="crc-details">
                        <h4>{{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}</h4>
                        <p>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y | g:i A') }}</p>
                    </div>
                </div>
                <div class="crc-right">
                    <span class="crc-badge" style="background-color: {{ $res->status == 'confirmed' ? '#d1fae5' : ($res->status == 'cancelled' ? '#fee2e2' : '#fef3c7') }}; color: {{ $res->status == 'confirmed' ? '#059669' : ($res->status == 'cancelled' ? '#dc2626' : '#d97706') }};">
                        {{ ucfirst($res->status) }}
                    </span>
                    <i class="fa-solid fa-chevron-right crc-chevron"></i>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-regular fa-calendar-xmark"></i>
                <h4>No upcoming reservations</h4>
                <p>You don't have any future bookings yet.</p>
                <a href="{{ route('reservation.index') }}">
                    <button class="btn-primary-solid">Book a Court</button>
                </a>
            </div>
        @endforelse
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