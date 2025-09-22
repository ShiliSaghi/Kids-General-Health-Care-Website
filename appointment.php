<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./photos/1.png'); 
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat;  
            display: flex;
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            margin: 0;
        }
        .main-container {
            background-color: rgba(252, 245, 226, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 10px 10px 50px rgba(250, 169, 169, 0.1);
            text-align: center;
            max-width: 800px;
            width: 100%;
        }
        table th, table td {
            text-align: center;
            vertical-align: middle;
            color: #333; /* Dark text color */
        }
        .available {
            background-color: #4aad4f; /* Matching button color green */
            color: white;
            cursor: pointer;
        }
        .unavailable {
            background-color: #ddd;
            color: #888;
            cursor: not-allowed;
        }
        .selected {
            background-color: #fcb40bb4  !important; /* orange highlight for selection */
            color: white !important;
        }
        .btn-primary {
            background-color: #4aad4f; /* Matching theme color */
            border-color: #4aad4f;
        }
        .btn-primary:hover {
            background-color: #fcb40bb4; /* Hover color matching theme */
            border-color: #fcb40bb4;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1>Book an Appointment</h1>

        <form action="save_appointment.php" method="POST">
            <!-- Dynamic Week Table -->
            <table class="table table-bordered mt-3">
                <thead class="thead-light">
                    <tr id="week-days">
                        <!-- Days will be inserted here dynamically -->
                    </tr>
                </thead>
                <tbody id="time-slots">
                    <!-- Time slots will be inserted here dynamically -->
                </tbody>
            </table>
            <input type="hidden" name="appointment_day" id="appointment_day">
            <input type="hidden" name="appointment_time" id="appointment_time">
            <button type="submit" class="btn btn-primary mt-3" id="submit_button" style="background-color: #fcb40bb9; border-color: #fcb40bb9" disabled>Confirm Appointment</button>
        </form>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]; 
const timeSlots = [
    "9-10 am", 
    "10-11 am", 
    "11-12 am", 
    "1-2 pm", 
    "2-3 pm", 
    "3-4 pm"
];

window.onload = function() {
    const today = new Date();
    const weekDaysRow = document.getElementById('week-days');
    const timeSlotsBody = document.getElementById('time-slots');

    // Calculate the start of the week (Monday)
    const dayOfWeek = today.getDay();
    const startDate = new Date(today);
    startDate.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1)); // Adjust if Sunday

    // Fill in the week days (only Monday to Friday)
    for (let i = 0; i < 5; i++) {  // Only loop through 5 days
        const day = new Date(startDate);
        day.setDate(startDate.getDate() + i);
        const formattedDate = `${daysOfWeek[i]}<br>${day.getDate()}/${day.getMonth() + 1}`;
        weekDaysRow.innerHTML += `<th>${formattedDate}</th>`;
    }

    // Fill in the time slots
    for (let i = 0; i < timeSlots.length; i++) {
        let row = '<tr>'; // Start row without scope="row" for the time slot
        for (let j = 0; j < 5; j++) {  // Only loop through 5 days
            const day = new Date(startDate);
            day.setDate(startDate.getDate() + j);
            const dayString = `${day.getFullYear()}-${(day.getMonth() + 1).toString().padStart(2, '0')}-${day.getDate().toString().padStart(2, '0')}`;
            row += `<td class="available" data-day="${dayString}" data-time="${timeSlots[i]}">${timeSlots[i]}</td>`;
        }
        row += '</tr>';
        timeSlotsBody.innerHTML += row;
    }

    // Enable slot selection
    enableSlotSelection();
};

function enableSlotSelection() {
    const cells = document.querySelectorAll('td.available');
    cells.forEach(cell => {
        cell.addEventListener('click', function() {
            // Deselect all cells
            cells.forEach(c => c.classList.remove('selected'));
            // Select the clicked cell
            this.classList.add('selected');

            // Set the hidden input values
            document.getElementById('appointment_day').value = this.dataset.day;
            document.getElementById('appointment_time').value = this.dataset.time;
            document.getElementById('submit_button').disabled = false;
        });
    });
}

    </script>
</body>
</html>
