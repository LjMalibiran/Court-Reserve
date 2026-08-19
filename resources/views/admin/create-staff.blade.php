<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff | Admin</title>
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
            --success: #10b981;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        
        /* Form Card */
        .card { background: var(--card-bg); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 30px; max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: var(--text-main); margin-bottom: 8px; font-size: 14px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; box-sizing: border-box; }
        .form-control:focus { outline: none; border-color: var(--primary-blue); }
        
        /* Alerts */
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; font-weight: bold; }
        .alert-success { background-color: #d1fae5; color: #059669; border: 1px solid #34d399; }
        .alert-error { background-color: #fee2e2; color: #dc2626; border: 1px solid #f87171; }

        .btn-submit { background-color: var(--primary-blue); color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%; transition: 0.2s; }
        .btn-submit:hover { background-color: var(--dark-blue); }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        <header class="top-header">
            <h1>Create Staff Account</h1>
        </header>

        <div class="card">
            @if(session('success'))
                <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" class="form-control" placeholder="09xxxxxxxxx" required>
                </div>

                <div class="form-group">
                    <label>Account Role</label>
                    <select name="role" class="form-control" required>
                        <option value="cashier">Cashier</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>
            </form>
        </div>
    </main>

</body>
</html>