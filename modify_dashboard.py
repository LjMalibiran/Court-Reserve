import re

with open(r'c:\Users\Maricel\OneDrive\Documents\lj-docu\court-reserve\court-reserve\resources\views\cashier\dashboard.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

replacement = '''    function renderCalendarMonth() {
        let today = new Date();
        let targetMonth = new Date(today.getFullYear(), today.getMonth() + currentMonthOffset, 1);
        
        let monthYearStr = targetMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        document.getElementById('calendarMonthYear').innerText = monthYearStr;
        
        let html = '';
        let weekDays = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
        
        // Add header row for days of the week
        for (let w of weekDays) {
            html += <div style="font-size: 13px; color: var(--text-muted); font-weight: bold; margin-bottom: 10px; text-align: center;">\</div>;
        }
        
        let firstDayIndex = targetMonth.getDay();
        let daysInMonth = new Date(targetMonth.getFullYear(), targetMonth.getMonth() + 1, 0).getDate();
        
        let realTodayDate = new Date();
        let realTodayStr = \-\-\;
        
        let firstDayToFetch = null;

        // Pad empty days
        for (let i = 0; i < firstDayIndex; i++) {
            html += <div></div>;
        }

        for (let i = 1; i <= daysInMonth; i++) {
            let currentDay = new Date(targetMonth.getFullYear(), targetMonth.getMonth(), i);
            
            let m = currentDay.getMonth() + 1;
            let d = currentDay.getDate();
            let y = currentDay.getFullYear();
            let formattedDate = \-\-\;
            
            if (i === 1) firstDayToFetch = formattedDate;

            let isToday = (formattedDate === realTodayStr) ? 'active' : '';
            if (currentMonthOffset !== 0 && i === 1) {
                isToday = 'active'; 
            } else if (currentMonthOffset !== 0 && isToday === 'active') {
                isToday = ''; // Should not happen since we're not in current month, but just in case
            }
            if (currentMonthOffset !== 0 && formattedDate !== firstDayToFetch) {
                 isToday = '';
            }
            
            html += <div class="cal-day \" onclick="fetchReservationsByDate('\', this)" style="cursor: pointer; transition: 0.2s; align-items: center;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='none'">
                        <span class="cal-date">\</span>
                     </div>;
        }
        
        let grid = document.getElementById('calendarGrid');
        grid.innerHTML = html;
        grid.style.display = 'grid';
        grid.style.gridTemplateColumns = 'repeat(7, 1fr)';
        grid.style.rowGap = '15px';
        grid.style.borderBottom = 'none';
        grid.style.paddingBottom = '0';
        
        let dayToFetch = (currentMonthOffset === 0) ? realTodayStr : firstDayToFetch;
        fetchReservationsByDate(dayToFetch, null);
    }
    
    // Initial Render
    renderCalendarMonth();

    function fetchReservationsByDate(date, element) {
        // Handle active state
        if (element) {
            document.querySelectorAll('.cal-day').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        let tbody = document.getElementById('upcomingTableBody');
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px;">Loading...</td></tr>';

        fetch(/api/reservations/by-date?date=\)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px;">No reservations found for this date.</td></tr>';
                    return;
                }

                let html = '';
                data.forEach(res => {
                    html += 
                        <tr>
                            <td style="font-weight: 500; color: var(--dark-blue);">\</td>
                            <td style="text-align: center;"><span style="background: #e3f2fd; color: #1557c0; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">\</span></td>
                            <td style="text-align: center;">\</td>
                            <td style="text-align: right;">\</td>
                        </tr>
                    ;
                });
                tbody.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #e53935; padding: 20px;">Error loading data.</td></tr>';
            });
    }
'''

pattern = re.compile(r'    function renderCalendarMonth\(\) \{.*?\n    \}\n', re.DOTALL)
content = pattern.sub(replacement, content)

with open(r'c:\Users\Maricel\OneDrive\Documents\lj-docu\court-reserve\court-reserve\resources\views\cashier\dashboard.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
