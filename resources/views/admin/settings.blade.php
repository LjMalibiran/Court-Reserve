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

        /* Logo & Branding */
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
        .time-inputs input { border: 1px solid #d1d5db; border-radius: 6px; padding: 4px 8px; font-size: 13px; color: var(--text-muted); outline: none; }
        .time-inputs input:focus { border-color: var(--primary-blue); }

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
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Settings saved successfully!');">
            <div class="settings-card">
                <div class="settings-grid">
                    
                    <!-- Left Column: General Information -->
                    <div class="left-col">
                        <div class="section-title">General Information</div>
                        
                        <div class="form-group">
                            <label>System Name</label>
                            <input type="text" class="form-control" value="Batangas Badminton Court Reserve">
                        </div>
                        
                        <div class="form-group">
                            <label>Reservation Duration</label>
                            <input type="text" class="form-control" value="1 Hour, 2 Hours, 3 Hours">
                        </div>
                        
                        <div class="form-group">
                            <label>Time Format</label>
                            <input type="text" class="form-control" value="12 Hours (AM/PM)">
                        </div>

                        <div class="toggle-row">
                            <span class="toggle-label">Allow Same day Reserve</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Right Column: Logo & Branding / System Settings -->
                    <div class="right-col">
                        <div class="section-title">Logo & Branding</div>
                        
                        <div class="branding-section">
                            <div class="current-logo">
                                <!-- Placeholder for actual logo -->
                                <img src="{{ asset('images/logo.png') }}" alt="System Logo" onerror="this.onerror=null; this.src='https://via.placeholder.com/180x60?text=Batangas+Badminton';">
                            </div>
                            
                            <label class="upload-logo-btn">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span>Upload new logo here</span>
                                <input type="file" accept="image/*">
                            </label>
                        </div>

                        <div class="section-title" style="margin-top: 30px;">System Setting</div>
                        
                        <div class="toggle-row">
                            <span class="toggle-label">Maintenance Mode</span>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>

                        <!-- Operating Hours based on provided schedule -->
                        <div class="hours-container">
                            <div class="hours-title">Reservation Day & Time</div>
                            
                            <div class="day-row">
                                <span class="day-name">Monday</span>
                                <div class="time-inputs">
                                    <input type="time" value="07:00"> - <input type="time" value="21:00">
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Tuesday</span>
                                <div class="time-inputs">
                                    <input type="time" value="07:00"> - <input type="time" value="21:00">
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Wednesday</span>
                                <div class="time-inputs">
                                    <input type="time" value="07:00"> - <input type="time" value="17:00">
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Thursday</span>
                                <div class="time-inputs">
                                    <input type="time" value="07:00"> - <input type="time" value="21:00">
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Friday</span>
                                <div class="time-inputs">
                                    <input type="time" value="07:00"> - <input type="time" value="21:00">
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Saturday</span>
                                <div class="time-inputs">
                                    <input type="time" value="07:00"> - <input type="time" value="21:00">
                                </div>
                            </div>
                            <div class="day-row">
                                <span class="day-name">Sunday</span>
                                <div class="time-inputs">
                                    <input type="time" value="07:00"> - <input type="time" value="13:00">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="settings-footer">
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </div>
        </form>

    </main>

</body>
</html>