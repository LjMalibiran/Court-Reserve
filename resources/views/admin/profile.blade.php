<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #9ca3af;
            --border-color: #e5e7eb;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        /* Profile Cards */
        .profile-card { background: var(--card-bg); border-radius: 16px; padding: 30px 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); margin-bottom: 30px; }
        .card-title { font-size: 20px; color: var(--dark-blue); font-weight: 600; margin-bottom: 30px; }
        
        /* Admin Info Section */
        .admin-info-layout { display: flex; gap: 60px; align-items: flex-start; }
        
        /* Photo Upload Area */
        .photo-section { display: flex; flex-direction: column; align-items: center; gap: 20px; min-width: 180px; }
        .avatar-preview { width: 130px; height: 130px; background-color: #e0e7ff; color: var(--primary-blue); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 60px; overflow: hidden; }
        .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
        .btn-outline { background: white; border: 1px solid var(--primary-blue); color: var(--primary-blue); padding: 8px 20px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: 0.2s; }
        .btn-outline:hover { background: #eff6ff; }
        
        /* Form Inputs */
        .form-section { flex-grow: 1; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 20px; }
        .form-group label { font-size: 14px; font-weight: 500; color: var(--text-muted); margin-bottom: 8px; }
        .form-control { padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; color: var(--text-main); box-sizing: border-box; transition: 0.2s; width: 100%; }
        .form-control:focus { outline: none; border-color: var(--primary-blue); }
        
        /* Password Section */
        .password-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 30px; }
        
        .btn-primary { background: var(--primary-blue); color: white; border: none; padding: 10px 25px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: 0.2s; }
        .btn-primary:hover { background: var(--dark-blue); }

        /* Hide actual file input */
        #photoUpload { display: none; }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <h1>Profile</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <!-- Admin Information Card -->
        <div class="profile-card">
            <h2 class="card-title">Admin Information</h2>
            
            <div class="admin-info-layout">
                <div class="photo-section">
                    <div class="avatar-preview" id="avatarPreview">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <label for="photoUpload" class="btn-outline">Change Photo</label>
                    <input type="file" id="photoUpload" accept="image/*" onchange="previewImage(event)">
                </div>
                
                <div class="form-section">
                    <form onsubmit="event.preventDefault(); alert('Profile information updated!');">
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label>Name</label>
                                <input type="text" class="form-control" value="Lj Malibiran">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label>Role</label>
                                <input type="text" class="form-control" value="Admin" readonly style="background-color: #f8fafc; color: var(--text-muted);">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="admin@batangasbadminton.com">
                        </div>
                        
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" value="09123456789">
                        </div>
                        
                        <!-- Invisible submit button to allow enter key submission if needed -->
                        <button type="submit" style="display: none;"></button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="profile-card">
            <h2 class="card-title">Change Password</h2>
            
            <form onsubmit="event.preventDefault(); alert('Password successfully updated!');">
                <div class="password-grid">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Current Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>New Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn-primary">Update Password</button>
                </div>
            </form>
        </div>

    </main>

    <script>
        // Simple preview script for the avatar upload
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('avatarPreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview">`;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>