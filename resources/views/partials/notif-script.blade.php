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
                        let link = n.user_id ? `/admin/reservations?tab=pending` : `#`;
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
