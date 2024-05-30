const userId = 1;
const selectedSlots = new Set();

document.addEventListener('DOMContentLoaded', () => {
    const schedule = document.getElementById('schedule');
    const tbody = schedule.querySelector('tbody');
    const days = generateDaysOfWeek();

    const headerRow = schedule.querySelector('thead tr');
    days.forEach(day => {
        const th = document.createElement('th');
        th.textContent = `${day.date} (${day.day})`;
        headerRow.appendChild(th);
    });

    const hours = generateTimeSlots();
    hours.forEach(hour => {
        const row = document.createElement('tr');
        const timeCell = document.createElement('td');
        timeCell.textContent = hour;
        row.appendChild(timeCell);

        days.forEach(day => {
            const cell = document.createElement('td');
            cell.classList.add('available');
            cell.dataset.date = day.date;
            cell.dataset.time = hour;
            cell.addEventListener('click', () => handleCellClick(cell));
            row.appendChild(cell);
        });

        tbody.appendChild(row);
    });

    fetch('/api/appointments')
        .then(response => response.json())
        .then(data => {
            data.forEach(appointment => {
                const { date, start_time, user_id, id } = appointment;
                const cell = document.querySelector(`td[data-date="${date}"][data-time="${start_time}"]`);
                if (cell) {
                    cell.classList.remove('available');
                    cell.classList.add('booked');
                    if (user_id == userId) {
                        cell.classList.add('booked-by-user');
                        cell.dataset.appointmentId = id;
                        cell.addEventListener('click', () => handleCancelClick(cell));
                    }
                }
            });
        });
});

function generateDaysOfWeek() {
    const today = new Date();
    const daysOfWeek = ['یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه', 'شنبه'];
    const days = [];

    for (let i = 0; i < 7; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        days.push({
            date: date.toISOString().split('T')[0],
            day: daysOfWeek[date.getDay()]
        });
    }

    return days;
}

function generateTimeSlots() {
    const slots = [];
    for (let hour = 7; hour < 15; hour++) {
        slots.push(`${String(hour).padStart(2, '0')}:00`);
        slots.push(`${String(hour).padStart(2, '0')}:30`);
    }
    slots.push(`15:00`);
    return slots;
}

function handleCellClick(cell) {
    if (!cell.classList.contains('available') && !cell.classList.contains('selected')) return;

    const slot = JSON.stringify({ date: cell.dataset.date, time: cell.dataset.time });

    if (cell.classList.contains('selected')) {
        cell.classList.remove('selected');
        selectedSlots.delete(slot);
    } else {
        cell.classList.add('selected');
        selectedSlots.add(slot);
    }

    if (selectedSlots.size > 0) {
        document.getElementById('reserveForm').style.display = 'block';
    } else {
        document.getElementById('reserveForm').style.display = 'none';
    }
}

function reserveAppointment() {
    const slots = Array.from(selectedSlots).map(slot => JSON.parse(slot));

    console.log(slots);
    fetch('/api/appointments/reserve', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ userId, slots })
    })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        });
}

function handleCancelClick(cell) {
    if (!cell.classList.contains('booked-by-user')) return;
    const appointmentId = cell.dataset.appointmentId;
    fetch('/api/appointments/cancel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ appointmentId, userId })
    })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        });
}
