<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements | Batangas Badminton</title>
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
        
        /* Container */
        .settings-card { background: var(--card-bg); border-radius: 16px; padding: 40px 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); }
        .section-title { font-size: 20px; color: var(--dark-blue); font-weight: 600; margin-bottom: 25px; }

        /* Form Inputs */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-size: 15px; font-weight: 500; color: var(--dark-blue); margin-bottom: 10px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; color: var(--text-main); box-sizing: border-box; transition: 0.2s; }
        .form-control:focus { outline: none; border-color: var(--primary-blue); }

        .btn-save { background: var(--primary-blue); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-save:hover { background: var(--dark-blue); }

        .btn-danger { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-danger:hover { background: #dc2626; }

        /* Table */
        .announcements-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .announcements-table th, .announcements-table td { padding: 12px 15px; border-bottom: 1px solid var(--border-color); text-align: left; }
        .announcements-table th { background: #f8fafc; font-weight: 600; color: var(--dark-blue); font-size: 14px; }
        .announcements-table td { font-size: 14px; color: var(--text-main); }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <h1>Announcements</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                @include('partials.notif-bell')
            </div>
        </header>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                {{ session('success') }}
            </div>
        @endif

        <div class="settings-card" style="margin-bottom: 30px;">
            <div class="section-title">Create New Announcement</div>
            <form action="{{ url('/admin/announcements') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn-save">Post Announcement</button>
            </form>
        </div>

        <div class="settings-card">
            <div class="section-title">Existing Announcements</div>
            <table class="announcements-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td style="white-space: nowrap;">{{ $announcement->created_at->format('M d, Y h:i A') }}</td>
                            <td style="font-weight: 500;">{{ $announcement->title }}</td>
                            <td>{{ Str::limit($announcement->content, 100) }}</td>
                            <td>
                                <form action="{{ url('/admin/announcements/'.$announcement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted);">No announcements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
    @include('partials.notif-script')
</body>
</html>
