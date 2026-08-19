<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refunds | Batangas Badminton</title>
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
            --success-color: #16a34a;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; position: relative; }
        
        /* Header & Controls (Unchanged) */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        .controls-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .filter-tabs { display: flex; gap: 10px; }
        .tab-btn { background-color: #f0f2f5; color: var(--text-main); border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
        .tab-btn.active { background-color: var(--dark-blue); color: white; }
        
        .search-box { display: flex; align-items: center; gap: 10px; }
        .search-input-wrapper { position: relative; }
        .search-input-wrapper i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-input-wrapper input { padding: 10px 35px 10px 15px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 220px; }

        /* Tables (Unchanged) */
        .table-container { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); flex-grow: 1; display: flex; flex-direction: column; overflow: visible; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 20px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: var(--dark-blue); font-weight: 600; border-bottom: 2px solid var(--border-color); }
        td { border-bottom: 1px solid #f0f0f0; color: #4b5563; }
        .empty-state { text-align: center !important; padding: 60px 40px !important; color: var(--text-muted) !important; font-style: italic; }
        .empty-icon { font-size: 40px; color: #cbd5e1; margin-bottom: 15px; }
        .btn-dev { background: #f59e0b; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold; }

        /* --- UPDATED MODAL STYLES (Matching r (10).jpg) --- */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-overlay.show { display: flex; }
        
        .modal-card { background: var(--card-bg); width: 850px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 35px 40px; position: relative; }
        
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .modal-header h2 { margin: 0; font-size: 22px; color: var(--dark-blue); font-weight: 600; }
        .btn-close { background: none; border: none; font-size: 20px; color: var(--dark-blue); cursor: pointer; font-weight: bold; }
        
        .modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
        
        .section-title { font-size: 18px; color: var(--dark-blue); font-weight: 600; margin-bottom: 25px; }

        /* Left Column: Reservation Summary */
        .summary-row { display: grid; grid-template-columns: 130px 1fr; gap: 15px; margin-bottom: 20px; font-size: 14px; align-items: center; }
        .summary-label { color: var(--text-muted); font-weight: 400; }
        .summary-value { color: var(--dark-blue); font-weight: 500; display: flex; align-items: center; gap: 10px; }
        
        .status-confirmed { color: var(--success-color); font-weight: 500; }
        .icon-receipt { border: 1px solid var(--dark-blue); border-radius: 4px; padding: 2px 6px; font-size: 12px; color: var(--dark-blue); display: inline-flex; align-items: center; justify-content: center; }

        /* Right Column: Refund Information */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-size: 15px; font-weight: 500; color: var(--dark-blue); margin-bottom: 10px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: var(--dark-blue); box-sizing: border-box; }
        .form-control:focus { outline: none; border-color: var(--primary-blue); }

        .upload-section { margin-top: 10px; text-align: center; }
        .upload-section label { display: block; text-align: left; font-size: 15px; font-weight: 500; color: var(--dark-blue); margin-bottom: 10px; }
        .file-upload-wrapper { border: 1px solid #d1d5db; border-radius: 8px; padding: 40px 20px; text-align: center; cursor: pointer; transition: 0.2s; display: block; background: #fff; }
        .file-upload-wrapper:hover { border-color: var(--primary-blue); }
        .file-upload-wrapper i { font-size: 45px; color: var(--primary-blue); }
        .file-upload-wrapper input[type="file"] { display: none; }
        .upload-hint { font-size: 11px; color: var(--text-muted); margin-top: 8px; text-align: center; }

        /* Footer / Action Button */
        .modal-footer { margin-top: 40px; display: flex; justify-content: center; }
        .btn-confirm { background: var(--primary-blue); color: white; border: none; padding: 12px 45px; border-radius: 8px; font-size: 16px; font-weight: 500; cursor: pointer; transition: 0.2s; }
        .btn-confirm:hover { background: var(--dark-blue); }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <h1>Refund Requests</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <div class="controls-bar">
            <div class="filter-tabs">
                <button class="tab-btn active">Pending <span>0</span></button>
                <button class="tab-btn">Completed <span>0</span></button>
                <button class="tab-btn">Rejected <span>0</span></button>
            </div>
            
            <div class="search-box">
                <button class="btn-dev" onclick="openRefundModal()"><i class="fa-solid fa-eye"></i> View Modal Layout</button>
                <div class="search-input-wrapper">
                    <input type="text" placeholder="Search Refund ID...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Refund ID</th>
                        <th>Customer</th>
                        <th>Reservation Ref</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-icon"><i class="fa-solid fa-money-check-dollar"></i></div>
                            <div>No refund requests at the moment.</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- NEW REFUND PROCESSING MODAL -->
        <div class="modal-overlay" id="refundModal">
            <div class="modal-card">
                <div class="modal-header">
                    <h2>Refund Process</h2>
                    <button class="btn-close" onclick="closeRefundModal()"><i class="fa-solid fa-xmark"></i></button>
                </div>
                
                <div class="modal-grid">
                    <!-- Left Column -->
                    <div>
                        <div class="section-title">Reservation Summary</div>
                        
                        <div class="summary-row">
                            <div class="summary-label">Reservation ID</div>
                            <div class="summary-value">BC26-02</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Sport</div>
                            <div class="summary-value">Badminton</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Court</div>
                            <div class="summary-value">Court 1</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Date & Time</div>
                            <div class="summary-value">Mon, June 1, 2026, 4:00 - 5:00 PM</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Rent Item</div>
                            <div class="summary-value">1 Racket, 1 Shuttlecock</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Duration</div>
                            <div class="summary-value">1 Hour</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Payment</div>
                            <div class="summary-value">
                                Paid <span class="icon-receipt"><i class="fa-regular fa-image"></i></span>
                            </div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Status</div>
                            <div class="summary-value status-confirmed">Confirmed</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <div class="section-title">Refund Information</div>
                        
                        <div class="form-group">
                            <label>Refund Amount</label>
                            <input type="text" class="form-control" value="₱ 280.00" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Refund Reason</label>
                            <input type="text" class="form-control" value="Schedule Conflict" readonly>
                        </div>
                        
                        <div class="upload-section">
                            <label>Gcash Receipt</label>
                            <label class="file-upload-wrapper" for="receiptUpload">
                                <i class="fa-solid fa-cloud-arrow-up" id="uploadIcon"></i>
                                <input type="file" id="receiptUpload" accept="image/*" onchange="handleFileUpload(event)">
                            </label>
                            <div class="upload-hint" id="uploadText">Accepted file: JPG, PNG (Max.5MB)</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn-confirm" onclick="confirmRefund()">Confirm Refund</button>
                </div>
            </div>
        </div>

    </main>

    <script>
        const modal = document.getElementById('refundModal');
        const uploadIcon = document.getElementById('uploadIcon');
        const uploadText = document.getElementById('uploadText');

        function openRefundModal() {
            modal.classList.add('show');
        }

        function closeRefundModal() {
            modal.classList.remove('show');
            // Reset upload visuals on close
            uploadIcon.className = "fa-solid fa-cloud-arrow-up";
            uploadIcon.style.color = "var(--primary-blue)";
            uploadIcon.style.fontSize = "45px";
            uploadText.innerHTML = "Accepted file: JPG, PNG (Max.5MB)";
            document.getElementById('receiptUpload').value = "";
        }

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Change icon to show success
                uploadIcon.className = "fa-solid fa-circle-check";
                uploadIcon.style.color = "var(--success-color)";
                uploadIcon.style.fontSize = "40px";
                uploadText.innerHTML = `<span style="color:var(--success-color); font-weight:bold;">${file.name} attached</span>`;
            }
        }

        function confirmRefund() {
            alert('Refund confirmed successfully!');
            closeRefundModal();
        }

        // Close when clicking outside the modal
        window.onclick = function(event) {
            if (event.target === modal) {
                closeRefundModal();
            }
        }
    </script>
</body>
</html>