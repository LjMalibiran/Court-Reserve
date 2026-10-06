@php
    $timeOptions = [];
    for($i=0; $i<24; $i++) {
        $hour24 = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        $ampm = $i >= 12 ? 'PM' : 'AM';
        $hour12 = $i > 12 ? $i - 12 : ($i == 0 ? 12 : $i);
        $hour12 = str_pad($hour12, 2, '0', STR_PAD_LEFT);
        $timeOptions[$hour24] = "$hour12:00 $ampm";
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Batangas Badminton</title>
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
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        /* Settings Container */
        .settings-card { background: var(--card-bg); border-radius: 16px; padding: 40px 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); }
        .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-bottom: 40px; }
        
        .section-title { font-size: 20px; color: var(--dark-blue); font-weight: 600; margin-bottom: 25px; text-align: center; }

        /* Form Inputs */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-size: 15px; font-weight: 500; color: var(--dark-blue); margin-bottom: 10px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; color: var(--text-main); box-sizing: border-box; transition: 0.2s; }
        .form-control:focus { outline: none; border-color: var(--primary-blue); }

        /* Toggle Switch */
        .toggle-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .toggle-label { font-size: 15px; font-weight: 500; color: var(--dark-blue); }
        .switch { position: relative; display: inline-block; width: 54px; height: 28px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-blue); }
        input:checked + .slider:before { transform: translateX(26px); }

        /* Logo */
        .branding-section { display: flex; align-items: center; justify-content: space-between; margin-bottom: 40px; gap: 20px; }
        .current-logo { flex-grow: 1; text-align: center; }
        .current-logo img { max-width: 180px; }
        .upload-logo-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 20px; cursor: pointer; transition: 0.2s; width: 140px; height: 100px; }
        .upload-logo-btn:hover { border-color: var(--primary-blue); background: #eff6ff; }
        .upload-logo-btn i { font-size: 30px; color: var(--primary-blue); margin-bottom: 10px; }
        .upload-logo-btn span { font-size: 11px; color: var(--text-muted); text-align: center; }
        .upload-logo-btn input { display: none; }

        /* Operating Hours Grid (Applied from image_222e3b.png) */
        .hours-container { background: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; }
        .hours-title { font-size: 15px; font-weight: 500; color: var(--dark-blue); margin-bottom: 15px; }
        .day-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; color: var(--text-main); }
        .day-row:last-child { border-bottom: none; }
        .day-name { font-weight: 500; width: 90px; }
        .time-inputs { display: flex; align-items: center; gap: 10px; }
        .time-inputs input, .time-inputs select { border: 1px solid #d1d5db; border-radius: 6px; padding: 4px 8px; font-size: 13px; color: var(--text-muted); outline: none; }
        .time-inputs input:focus, .time-inputs select:focus { border-color: var(--primary-blue); }

        /* Action Footer */
        .settings-footer { text-align: center; margin-top: 20px; }
        .btn-save { background: var(--primary-blue); color: white; border: none; padding: 12px 40px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 10px rgba(21, 87, 192, 0.2); }
        .btn-save:hover { background: var(--dark-blue); }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <h1>Setting</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                @include('partials.notif-bell')
            </div>
        </header>

        <!-- Session Success Modal Handled Globally -->

        <form action="{{ url('/admin/settings') }}" method="POST">
            @csrf
            <div class="settings-card">
                <div class="settings-grid">
                    
                    <!-- Left Column: Logo -->
                    <div class="left-col">
                        <div class="section-title">Court Reserve: Batangas Badminton Center</div>
                        
                        <div class="branding-section">
                            <div class="current-logo">
                                <img src="{{ asset('images/logo.png') }}" alt="System Logo" onerror="this.onerror=null; this.src='https://via.placeholder.com/180x60?text=Batangas+Badminton';">
                            </div>
                        </div>
                        
                        <div class="section-title" style="margin-top: 40px;">Pricing Configuration</div>
                        
                        <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Badminton (per hour)</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-weight: bold; color: var(--text-muted);">&#8369;</span>
                                    <input type="number" name="price_badminton" class="form-control" value="{{ $settings['price_badminton'] ?? 230 }}" min="0" step="10" style="width: 100%;">
                                </div>
                            </div>

                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Pickleball (per hour)</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-weight: bold; color: var(--text-muted);">&#8369;</span>
                                    <input type="number" name="price_pickleball" class="form-control" value="{{ $settings['price_pickleball'] ?? 250 }}" min="0" step="10" style="width: 100%;">
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Racket (per item)</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-weight: bold; color: var(--text-muted);">&#8369;</span>
                                    <input type="number" name="price_racket" class="form-control" value="{{ $settings['price_racket'] ?? 50 }}" min="0" step="10" style="width: 100%;">
                                </div>
                            </div>
                            
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Shuttlecock (per item)</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-weight: bold; color: var(--text-muted);">&#8369;</span>
                                    <input type="number" name="price_shuttlecock" class="form-control" value="{{ $settings['price_shuttlecock'] ?? 50 }}" min="0" step="10" style="width: 100%;">
                                </div>
                            </div>
                        </div>                    </div>
                    <!-- Right Column: Operating Hours -->
                    <div class="right-col">
                        <div class="hours-container">
                            <div class="hours-title">Reservation Day & Time</div>
                            
                            <div class="day-row">
                                <span class="day-name">Monday</span>
                                <div class="time-inputs">
                                    <select name="operating_hours[monday][start]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['monday']['start'] ?? '07:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select> - 
                                    <select name="operating_hours[monday][end]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['monday']['end'] ?? '21:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Tuesday</span>
                                <div class="time-inputs">
                                    <select name="operating_hours[tuesday][start]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['tuesday']['start'] ?? '07:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select> - 
                                    <select name="operating_hours[tuesday][end]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['tuesday']['end'] ?? '21:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Wednesday</span>
                                <div class="time-inputs">
                                    <select name="operating_hours[wednesday][start]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['wednesday']['start'] ?? '07:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select> - 
                                    <select name="operating_hours[wednesday][end]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['wednesday']['end'] ?? '17:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Thursday</span>
                                <div class="time-inputs">
                                    <select name="operating_hours[thursday][start]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['thursday']['start'] ?? '07:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select> - 
                                    <select name="operating_hours[thursday][end]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['thursday']['end'] ?? '21:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Friday</span>
                                <div class="time-inputs">
                                    <select name="operating_hours[friday][start]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['friday']['start'] ?? '07:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select> - 
                                    <select name="operating_hours[friday][end]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['friday']['end'] ?? '21:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Saturday</span>
                                <div class="time-inputs">
                                    <select name="operating_hours[saturday][start]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['saturday']['start'] ?? '07:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select> - 
                                    <select name="operating_hours[saturday][end]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['saturday']['end'] ?? '21:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Sunday</span>
                                <div class="time-inputs">
                                    <select name="operating_hours[sunday][start]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['sunday']['start'] ?? '07:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select> - 
                                    <select name="operating_hours[sunday][end]" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;">
                                        @foreach($timeOptions as $val => $label)
                                            <option value="{{ $val }}" {{ ($settings['operating_hours']['sunday']['end'] ?? '13:00') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Blocked Dates Section -->
                        <div class="hours-container" style="margin-top: 20px;">
                            <div class="hours-title">Blocked Dates (Events/Tournaments)</div>
                            <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">Select dates to prevent users from booking.</p>
                            
                            <div class="time-inputs" style="margin-bottom: 15px;">
                                <input type="date" id="blockDateInput" class="form-control" style="width: auto;">
                                <select id="blockStartInput" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;" title="Start Time">
                                    <option value="">Start</option>
                                    @foreach($timeOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                -
                                <select id="blockEndInput" class="form-control" style="width: auto; appearance: auto; text-align: center; text-align-last: center;" title="End Time">
                                    <option value="">End</option>
                                    @foreach($timeOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="addBlockedDate()" style="background: var(--primary-blue); color: white; border: none; border-radius: 6px; padding: 6px 20px; cursor: pointer; font-weight: 600; box-shadow: 0 2px 4px rgba(21, 87, 192, 0.2); transition: 0.2s;">Add</button>
                            </div>
                            
                            <div id="blockedDatesList" style="display: flex; flex-direction: column; gap: 8px;">
                                <!-- Dates will be rendered here by JS -->
                            </div>
                            
                            <input type="hidden" name="blocked_dates" id="blockedDatesHidden" value="{{ json_encode($settings['blocked_dates'] ?? []) }}">
                        </div>
                    </div>
                </div>

                <div class="settings-footer">
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </div>
        </form>

    </main>

<script>
    let rawBlocked = document.getElementById('blockedDatesHidden').value;
    let blockedDates = [];
    try {
        let parsed = JSON.parse(rawBlocked || '[]');
        blockedDates = parsed.map(b => typeof b === 'string' ? {date: b, start: '00:00', end: '23:59'} : b);
    } catch(e) {}
    
    function renderBlockedDates() {
        const list = document.getElementById('blockedDatesList');
        list.innerHTML = '';
        blockedDates.forEach((b, index) => {
            let timeStr = (b.start === '00:00' && b.end === '23:59') ? 'All Day' : (b.start + ' - ' + b.end);
            list.innerHTML += '<div style="display: flex; justify-content: space-between; align-items: center; background: var(--card-bg); padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;"><span><i class="fa-regular fa-calendar-xmark" style="color: #ef4444; margin-right: 8px;"></i> <strong>' + b.date + '</strong> <span style="color:#64748b; margin-left:10px;">(' + timeStr + ')</span></span><button type="button" onclick="removeBlockedDate(' + index + ')" style="background: none; border: none; color: #ef4444; cursor: pointer;"><i class="fa-solid fa-trash"></i></button></div>';
        });
        document.getElementById('blockedDatesHidden').value = JSON.stringify(blockedDates);
    }
    
    function addBlockedDate() {
        const dateInput = document.getElementById('blockDateInput');
        const startInput = document.getElementById('blockStartInput');
        const endInput = document.getElementById('blockEndInput');
        
        if(dateInput.value) {
            let start = startInput.value || '00:00';
            let end = endInput.value || '23:59';
            blockedDates.push({date: dateInput.value, start: start, end: end});
            
            blockedDates.sort((a,b) => a.date.localeCompare(b.date));
            renderBlockedDates();
            
            dateInput.value = '';
            startInput.value = '';
            endInput.value = '';
        }
    }
    
    function removeBlockedDate(index) {
        blockedDates.splice(index, 1);
        renderBlockedDates();
    }
    
    window.onload = function() {
        renderBlockedDates();
    }
</script>
@include('partials.notif-script')
</body>
</html>








