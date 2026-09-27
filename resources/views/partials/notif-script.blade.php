<style>
    /* GLOBAL DARK MODE OVERRIDES */
    body.dark-mode {
        --bg-color: #0f172a;
        --card-bg: #1e293b;
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --dark-blue: #60a5fa;
        --primary-blue: #3b82f6;
        --border-color: #334155;
    }
    
    /* General element overrides for dark mode */
    body.dark-mode input, body.dark-mode select, body.dark-mode textarea {
        background-color: #0f172a !important;
        color: #f8fafc !important;
        border-color: #334155 !important;
    }
    body.dark-mode .card, body.dark-mode .settings-card, body.dark-mode .table-container, body.dark-mode .kpi-card, body.dark-mode .scan-box, body.dark-mode .manual-entry, body.dark-mode .details-card, body.dark-mode .tab-btn, body.dark-mode .btn-export, body.dark-mode .btn-receipt, body.dark-mode .dropdown-content, body.dark-mode .modal-content, body.dark-mode div[style*="background: #fff"] {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
    }
    /* Force text to white across all tabs */
    body.dark-mode h1, body.dark-mode h2, body.dark-mode h3, body.dark-mode h4, body.dark-mode h5, body.dark-mode h6,
    body.dark-mode p, body.dark-mode strong, body.dark-mode label, body.dark-mode .kpi-value, body.dark-mode .kpi-label,
    body.dark-mode .court-name, body.dark-mode .section-title, body.dark-mode .calendar-header, body.dark-mode .upcoming-header,
    body.dark-mode .form-group label {
        color: #f8fafc !important;
    }
    
    body.dark-mode [style*="color: #0f2b6e"], body.dark-mode [style*="color: #374151"], body.dark-mode [style*="color: #4b5563"], 
    body.dark-mode [style*="color: #6b7280"], body.dark-mode [style*="color: #0033cc"], body.dark-mode [style*="color: #777"], 
    body.dark-mode [style*="color: #999"], body.dark-mode [style*="color: #2563eb"], body.dark-mode [style*="color: #64748b"] {
        color: #f8fafc !important;
    }
    
    body.dark-mode table th { background: #1e293b !important; color: #f8fafc !important; border-bottom: 1px solid #334155 !important; }
    body.dark-mode table td { border-bottom: 1px solid #334155 !important; color: #f8fafc !important; }
    body.dark-mode table td *:not(.badge) { color: #f8fafc !important; }
    body.dark-mode table tbody tr:nth-child(even) { background-color: #0f172a !important; }
    body.dark-mode table tbody tr:hover { background-color: #334155 !important; }
    body.dark-mode .upcoming-table th, body.dark-mode .upcoming-table td { border-bottom-color: #334155 !important; }
    body.dark-mode .sidebar {
        background-color: #0f172a !important;
        border-right: 1px solid #334155 !important;
    }
    body.dark-mode .sidebar .nav-menu a.active {
        background-color: #334155 !important;
        color: #60a5fa !important;
    }
    /* Dark Mode Forms & UI Adjustments */
    body.dark-mode { color-scheme: dark; }
    
    body.dark-mode .counter button {
        background-color: #334155 !important;
        color: #f8fafc !important;
        border: 1px solid #475569 !important;
    }
    
    body.dark-mode .btn-cancel, body.dark-mode .btn-outline {
        background-color: transparent !important;
        border-color: #475569 !important;
        color: #f8fafc !important;
    }

    body.dark-mode input[type="date"]::-webkit-calendar-picker-indicator {
        /* This filter turns the black calendar icon into a bright blue color */
        filter: invert(48%) sepia(93%) saturate(1915%) hue-rotate(196deg) brightness(101%) contrast(98%);
        cursor: pointer;
    }

    body.dark-mode img[src*="qr"], body.dark-mode img[src*="QR"], body.dark-mode img[id*="qr"], body.dark-mode img[id*="QR"], body.dark-mode canvas {
        background-color: white !important;
        padding: 10px !important;
        border-radius: 8px !important;
    }
    
    body.dark-mode .counter button {
        background-color: #334155 !important;
        color: #f8fafc !important;
        border: 1px solid #475569 !important;
    }
</style>
<script>
    // Apply dark mode immediately on page load to prevent flash
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>
<script>
    // ==========================================
    // NOTIFICATIONS
    // ==========================================
    function toggleNotifDropdown() {
        let dropdown = document.getElementById('notifDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        if(dropdown.style.display === 'block') {
            fetchNotifications(); // load fresh when opened
        }
    }

    // Close dropdown if clicked outside
    document.addEventListener('click', function(event) {
        let bellIcon = document.getElementById('bellIcon');
        let dropdown = document.getElementById('notifDropdown');
        if (dropdown && bellIcon && !bellIcon.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    function markSingleNotificationRead(id, link) {
        fetch(`/notifications/${id}/mark-read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            window.location.href = link;
        }).catch(err => {
            console.error(err);
            window.location.href = link;
        });
    }

    function fetchNotifications() {
        fetch('/notifications/unread')
            .then(res => res.json())
            .then(data => {
                let badge = document.getElementById('notifBadge');
                if(data.count > 0) {
                    badge.style.display = 'block';
                    badge.innerHTML = data.count;
                } else {
                    badge.style.display = 'none';
                }

                let html = '';
                if(data.notifications.length === 0) {
                    html = '<div style="padding: 20px; text-align: center; color: #999; font-size: 13px;">No new notifications</div>';
                } else {
                    data.notifications.forEach(n => {
                        let bg = n.is_read ? 'transparent' : '#f0f7ff';
                        let dot = n.is_read ? '' : '<div style="width: 8px; height: 8px; background: #0044ff; border-radius: 50%; margin-top: 5px;"></div>';
                        
                        let basePath = window.location.pathname.startsWith('/cashier') ? '/cashier' : '/admin';
                        let link = `${basePath}/reservations?tab=pending`;
                        
                        if (n.title) {
                            if (n.title.includes('Refund')) {
                                link = `${basePath}/sales/refunds?tab=pending`;
                            } else if (n.title.includes('Cancelled')) {
                                link = `${basePath}/reservations?tab=cancelled`;
                            } else if (n.title.includes('Rescheduled')) {
                                link = `${basePath}/reservations?tab=pending`;
                            }
                        }
                        
                        html += `
                        <div style="padding: 15px 20px; border-bottom: 1px solid #eee; background: ${bg}; display: flex; gap: 15px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='${bg}'" onclick="markSingleNotificationRead(${n.id}, '${link}')">
                            <div style="flex-shrink: 0;">${dot}</div>
                            <div>
                                <h5 style="margin: 0 0 5px 0; color: #002277; font-size: 13px;">${n.title}</h5>
                                <p style="margin: 0; color: #666; font-size: 12px; line-height: 1.4;">${n.message}</p>
                                <span style="font-size: 10px; color: #999; margin-top: 5px; display: block;">${n.time_ago}</span>
                            </div>
                        </div>`;
                    });
                }
                let notifList = document.getElementById('notifList');
                if (notifList) notifList.innerHTML = html;
            })
            .catch(err => console.error(err));
    }

    function markNotificationsRead() {
        fetch('/notifications/mark-read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            fetchNotifications();
        });
    }

    // Auto-fetch badge count on load and every 30s
    document.addEventListener('DOMContentLoaded', function() {
        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    });
</script>









