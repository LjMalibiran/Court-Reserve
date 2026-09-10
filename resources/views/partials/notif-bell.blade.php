<div style="position: relative;">
    <i class="fa-regular fa-bell" id="bellIcon" style="cursor: pointer; font-size: 20px; position: relative;" onclick="toggleNotifDropdown()">
        <span id="notifBadge" style="display: none; position: absolute; top: -5px; right: -5px; background: #e53935; color: white; font-size: 10px; font-weight: bold; padding: 2px 5px; border-radius: 10px; border: 2px solid var(--bg-color, #f4f7f6);">0</span>
    </i>
    
    <!-- Notification Dropdown -->
    <div id="notifDropdown" style="display: none; position: absolute; right: 0; top: 35px; width: 320px; background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); z-index: 1000; overflow: hidden; border: 1px solid #eee; text-align: left;">
        <div style="padding: 15px 20px; background: #fafbfc; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; color: #002277; font-size: 15px;">Notifications</h4>
            <button onclick="markNotificationsRead()" type="button" style="background: none; border: none; color: #0044ff; font-size: 12px; cursor: pointer; font-weight: 600;">Mark all read</button>
        </div>
        <div id="notifList" style="max-height: 350px; overflow-y: auto; padding: 0;">
            <div style="padding: 20px; text-align: center; color: #999; font-size: 13px;">Loading...</div>
        </div>
    </div>
</div>
