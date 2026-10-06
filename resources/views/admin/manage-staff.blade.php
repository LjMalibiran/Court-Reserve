<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-blue: #1557c0;
        --dark-blue: #002277;
        --bg-color: #f8fafc;
        --text-main: #1e293b;
        --border-color: #e2e8f0;
        --success-color: #10b981;
    }

    body { background-color: var(--bg-color); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; display: flex; height: 100vh; overflow: hidden; }
    
    .main-content {
        flex-grow: 1;
        padding: 30px 40px;
        overflow-y: auto;
        position: relative;
    }

    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
    .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }

    .controls-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-box { display: flex; align-items: center; gap: 10px; }
    .search-input-wrapper { position: relative; }
    .search-input-wrapper i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .search-input-wrapper input { padding: 10px 35px 10px 15px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 250px; }
    
    .btn-primary { background-color: var(--primary-blue); color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-primary:hover { background-color: var(--dark-blue); }

    .table-container { background: white; border-radius: 10px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 16px 20px; text-align: left; }
    th { background: var(--dark-blue); color: white; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
    td { border-bottom: 1px solid var(--border-color); color: var(--text-main); font-size: 14px; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background-color: #f8fafc; }

    .staff-info { display: flex; align-items: center; gap: 12px; }
    .staff-avatar { width: 36px; height: 36px; background-color: #e2e8f0; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; color: var(--dark-blue); }
    
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
    .badge-admin { background: #dbeafe; color: #1e40af; }
    .badge-cashier { background: #e0e7ff; color: #4338ca; }
    
    .status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; }
    .status-active { background-color: var(--success-color); }
    .status-inactive { background-color: #9ca3af; }

    .action-btns { display: flex; gap: 12px; align-items: center; }
    .btn-icon { background: none; border: none; cursor: pointer; font-size: 16px; color: #64748b; transition: 0.2s; padding: 0; }
    .btn-icon:hover { color: var(--primary-blue); }
    .btn-icon.delete:hover { color: #ef4444; }

    .empty-state { text-align: center; padding: 40px; color: #64748b; }

    /* Modals */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: flex-start; overflow-y: auto; padding: 20px; box-sizing: border-box; z-index: 1000; }
    .modal-content { background: white; padding: 30px; border-radius: 12px; width: 450px; position: relative;  margin: auto; }
    .modal-close { position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b; }
    .modal-title { margin-top: 0; color: var(--dark-blue); font-size: 22px; margin-bottom: 20px; }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 500; color: var(--text-main); font-size: 14px; }
    .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; box-sizing: border-box; }
    
    .btn-submit { width: 100%; background: var(--primary-blue); color: white; border: none; padding: 12px; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 10px; }
</style>
</head>
<body>

@include('admin.sidebar')

<div class="main-content">
    <header class="top-header">
        <h1>Manage Staff</h1>
        <div class="header-right">
            <span>{{ now()->format('l, F j, Y') }}</span>
            @include('partials.notif-bell')
        </div>
    </header>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: 500;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: 500;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="controls-bar">
        <div class="search-box">
            <form action="" method="GET" style="margin: 0;">
                <div class="search-input-wrapper">
                    <input type="text" name="search" placeholder="Search staff name or email..." value="{{ $search ?? '' }}">
                    <button type="submit" style="background: none; border: none; padding: 0; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; cursor: pointer;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <button class="btn-primary" onclick="openModal('addStaffModal')">
            <i class="fa-solid fa-plus"></i> Add New Staff
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Account Status</th>
                    <th>Presence</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffs as $staff)
                <tr>
                    <td>
                        <div class="staff-info">
                            <div class="staff-avatar">{{ strtoupper(substr($staff->name, 0, 2)) }}</div>
                            <span style="font-weight: 600;">{{ $staff->name }}</span>
                        </div>
                    </td>
                    <td>{{ $staff->email }}</td>
                    <td>
                        <span class="badge badge-{{ $staff->role }}">{{ $staff->role }}</span>
                    </td>
                    <td>
                        <span style="font-weight: 500;">
                            <span class="status-dot {{ $staff->is_active ? 'status-active' : 'status-inactive' }}"></span>
                            {{ $staff->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $lastSeen = $staff->last_seen_at ? \Carbon\Carbon::parse($staff->last_seen_at) : null;
                            $isOnline = $lastSeen && $lastSeen->diffInMinutes(now()) <= 5;
                        @endphp
                        <span style="font-weight: 600; font-size: 13px; color: {{ $isOnline ? '#16a34a' : '#9ca3af' }};">
                            <i class="fa-solid fa-circle" style="font-size: 10px; margin-right: 4px;"></i> {{ $isOnline ? 'Online' : 'Offline' }}
                        </span>
                        @if(!$isOnline && $lastSeen)
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 3px;">
                                Seen: {{ $lastSeen->diffForHumans() }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon" title="View Attendance" onclick="viewAttendance({{ $staff->id }})" style="color: var(--primary-blue)">
                                <i class="fa-regular fa-clock"></i>
                            </button>
                            <button class="btn-icon" title="Edit Staff" onclick="openEditModal({{ $staff->id }}, '{{ addslashes($staff->name) }}', '{{ addslashes($staff->email) }}', '{{ $staff->role }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            
                            @if(auth()->id() != $staff->id)
                            <form action="{{ route('admin.staff.toggle-status', $staff->id) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn-icon" title="{{ $staff->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fa-solid fa-power-off" style="color: {{ $staff->is_active ? '#64748b' : '#ef4444' }}"></i>
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to permanently delete this staff member?');">
                                @csrf
                                <button type="submit" class="btn-icon delete" title="Delete Staff">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                            @else
                            <span style="font-size: 12px; color: #94a3b8; font-style: italic;">(You)</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <div><i class="fa-solid fa-users" style="font-size: 24px; margin-bottom: 10px;"></i></div>
                        <div>No staff members found.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal-overlay" id="addStaffModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('addStaffModal')"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">Add New Staff</h2>
        
        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="e.g. John Doe">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="cashier">Cashier</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Temporary Password</label>
                <input type="password" name="password" required minlength="8" placeholder="Minimum 8 characters">
            </div>
            <button type="submit" class="btn-submit">Create Staff Account</button>
        </form>
    </div>
</div>

<!-- Attendance Modal -->
<div class="modal-overlay" id="attendanceModal">
    <div class="modal-content" style="width: 600px;">
        <button class="modal-close" onclick="closeModal('attendanceModal')"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">History Log</h2>
        
        <div class="table-container" style="max-height: 400px; overflow-y: auto;">
            <table style="width: 100%;">
                <thead style="position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th style="padding: 12px 15px;">Login Time</th>
                        <th style="padding: 12px 15px;">Logout Time</th>
                        <th style="padding: 12px 15px;">Duration</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody">
                    <tr><td colspan="3" class="empty-state">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal-overlay" id="editStaffModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('editStaffModal')"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">Edit Staff</h2>
        
        <form id="editStaffForm" method="POST">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit_role" required>
                    <option value="cashier">Cashier</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>New Password (Optional)</label>
                <input type="password" name="password" minlength="8" placeholder="Leave blank to keep current password">
            </div>
            <button type="submit" class="btn-submit">Update Staff Account</button>
        </form>
    </div>
</div>

<script>
    async function viewAttendance(id) {
        openModal('attendanceModal');
        const tbody = document.getElementById('attendanceTableBody');
        tbody.innerHTML = '<tr><td colspan="3" class="empty-state">Loading...</td></tr>';
        
        try {
            const response = await fetch(`/admin/staff/${id}/attendance`);
            const data = await response.json();
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="empty-state">No history records found.</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            data.forEach(record => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">${record.login_time}</td>
                    <td style="padding: 12px 15px; border-bottom: 1px solid #eee; color: ${record.logout_time === 'Active Now' ? '#16a34a' : 'inherit'}; font-weight: ${record.logout_time === 'Active Now' ? '600' : 'normal'}">${record.logout_time}</td>
                    <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">${record.duration}</td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="3" class="empty-state" style="color: #ef4444;">Failed to load history logs.</td></tr>';
        }
    }

    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    function openEditModal(id, name, email, role) {
        const form = document.getElementById('editStaffForm');
        form.action = `/admin/staff/${id}/update`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        
        openModal('editStaffModal');
    }
    
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.style.display = 'none';
        }
    }
</script>
@include('partials.notif-script')
</body>
</html>