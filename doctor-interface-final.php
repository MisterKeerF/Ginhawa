<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    echo "Doctor not logged in.";
    exit();
}


$host = "localhost";
$dbname = "db1";
$username = "root";
$password = "";

$conn = new mysqli("localhost", "root", "", "db1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$doctorName = $_SESSION['user_name'];

// 2. Adjust SQL: Assuming appointments have a 'doctor_name' column to filter on
$sql = "SELECT * FROM appointment WHERE doctor = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $doctorName);
$stmt->execute();
$result = $stmt->get_result();

ob_start(); // Start output buffering
echo "<div id='patient-appointments-container'>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='name-item'>";
        echo "<strong>Patient:</strong> " . htmlspecialchars($row['name']) . "<br>";
        echo "<strong>Date:</strong> " . htmlspecialchars($row['date']) . "<br>";
        echo "</div>";
    }
} else {
    echo "<div class='name-item'>No appointments for you yet.</div>";
}
echo "</div>";

$appointmentsHTML = ob_get_clean(); // Save to a variable and end buffering

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>GINHAWA - Notifications</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f9f7d9;
    }


    .header {
      background-color: #244032;
      color: #fffde4;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
    }

    .header .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header .logo img {
      height: 70px;
    }

    .header .nav {
      display: flex;
      gap: 25px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
    }

    .main-container {
      display: flex;
      height: calc(100vh - 70px);
    }

    .sidebar {
      width: 280px;
      background-color: #f3f1d2;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar h2 {
      padding: 15px 20px;
      color: #244032;
      font-size: 24px;
      letter-spacing: 1px;
      margin: 30px 20px 0 20px;
      box-shadow: 0 8px 10px -4px rgba(36, 64, 50, 0.5);
      cursor: pointer;
      user-select: none;
      transition: box-shadow 0.3s ease;
    }

    .sidebar h2:hover {
      box-shadow: 0 12px 14px -6px rgba(36, 64, 50, 0.7);
    }

    .sidebar-footer {
      padding: 20px;
      font-size: 10px;
      color: #444;
      text-align: center;
    }

    .sidebar-footer .logo-group {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 10px;
      flex-wrap: wrap;
    }

    .sidebar-footer .logo-group img {
      height: 30px;
      width: auto;
    }

    .main {
      flex: 1;
      padding: 40px;
      background-color: #f9f7d9;
      position: relative;
      display: flex;
      flex-direction: column;
    }

    .main h3 {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .button-group {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    .button-group button {
      background-color: #244032;
      color: #fffde4;
      border: none;
      padding: 10px 30px;
      border-radius: 25px;
      font-size: 14px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s;
    }

    .button-group button:hover {
      background-color: #2f5747;
    }

    /* Calendar Styles */
    .calendar {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      margin-top: 20px;
      justify-items: center;
      border: 2px solid #244032;
      border-radius: 10px;
      padding: 10px;
    }

    .calendar-day {
      display: inline-block;
      width: 40px;
      height: 40px;
      line-height: 40px;
      text-align: center;
      margin: 5px;
      border-radius: 5px;
      color: #000;
      background-color: #fff;
      border: 1px solid #ccc;
      font-size: 14px;
      font-weight: bold;
      user-select: none;
    }

    .calendar-day.scheduled {
      background-color: lightblue; /* Color for scheduled days */
    }

    .calendar-day.no-schedule {
      background-color: lightgray; /* Color for days with no schedule */
    }

    .calendar-day.cancelled {
      background-color: lightpink; /* Color for cancelled days */
    }

    .calendar-day:hover {
      background-color: #fff;
      cursor: pointer;
    }

    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .calendar-header h4 {
      margin: 0;
    }

    .calendar-header button {
      background-color: #244032;
      color: #fff;
      padding: 5px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .calendar-header button:hover {
      background-color: #2f5747;
    }

    .calendar-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      justify-items: center;
      margin-bottom: 10px;
    }

    .calendar-days div {
      width: 40px;
      text-align: center;
      font-weight: bold;
      color: #244032;
      user-select: none;
    }

    /* Profile and Logout Dialog */
    .dialog {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 300px;
      text-align: center;
      z-index: 1000;
    }

    .dialog button {
      background-color: #244032;
      color: #fff;
      padding: 10px 20px;
      border: none;
      margin: 10px;
      cursor: pointer;
    }

    .dialog button:hover {
      background-color: #2f5747;
    }

    /* Patient Details - Vertical Layout */
    .name-item {
      display: flex;
      flex-direction: column;
      margin-bottom: 20px;
      margin-top: 10px;

      border: 2px solid #244032; /* matching your theme green */
      border-radius: 15px;
      padding: 15px 20px;
      box-shadow: 0 4px 8px rgba(36, 64, 50, 0.3);
      background-color: #f9f7d9; /* slightly lighter background */
      transition: box-shadow 0.3s ease;
    }

    .name-item:hover {
      box-shadow: 0 6px 12px rgba(36, 64, 50, 0.5);
      cursor: pointer;
    }

    .blur-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      backdrop-filter: blur(5px);
      background-color: rgba(0, 0, 0, 0.2);
      z-index: 999;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 20px;
      color: #244032;
      cursor: pointer;
      user-select: none;
    }

    /* Separate containers styling */
    #names-container, #patient-appointments-container {
      position: relative;
      flex: 1;
      overflow-y: auto;
      padding-right: 10px;
      background-color: #fdf6d8; /* match page background */
      border: none;             /* remove border */
      box-shadow: none;  
    }
    
    /* Hide container by default for proper toggling */
    #patient-appointments-container {
      display: none;
    }

  </style>

  <script>
    // Function to format the date for input type 'datetime-local'
    function formatDateForInput(dateString) {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
      const day = String(date.getDate()).padStart(2, '0');
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    let currentMonthIndex = new Date().getMonth();
    const months = [
      'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    ];
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    // Store the currently selected day (default to today)
    let selectedDay = new Date().getDate();
    let selectedYear = new Date().getFullYear();
    let selectedMonthIndex = new Date().getMonth();

    // Profile and Logout Dialog Functions
    function showProfile() {
      const blur = document.createElement('div');
      blur.className = 'blur-bg';
      blur.id = 'blur-bg';
      document.body.appendChild(blur);

      const profileDialog = document.createElement('div');
      profileDialog.classList.add('dialog');
      profileDialog.innerHTML = ` 
        <h3>Profile</h3>
        <div><strong>Name:</strong> Dra. Ariane Jade Bote</div>
        <div><strong>Contact Number:</strong> 09123456789</div>
        <div><strong>Email:</strong> ariana@gmail.com</div>
        <div><strong>Specialization:</strong> doctorkwakwak</div>
        <button onclick="closeDialog()">Close</button>
      `;
      document.body.appendChild(profileDialog);
      profileDialog.style.display = 'block';
    }


    function showLogout() {
      const blur = document.createElement('div');
      blur.className = 'blur-bg';
      blur.id = 'blur-bg';
      document.body.appendChild(blur);

      const logoutDialog = document.createElement('div');
      logoutDialog.classList.add('dialog');
      logoutDialog.innerHTML = ` 
        <h3>Are you sure you want to log out?</h3>
        <button onclick="confirmLogout()">Yes</button>
        <button onclick="closeDialog()">No</button>
      `;
      document.body.appendChild(logoutDialog);
      logoutDialog.style.display = 'block';
    }

      function confirmLogout() {
       // Example: redirect to logout.php or homepage
       window.location.href = 'main page.html'; // change this to your actual logout URL
    }

    function closeDialog() {
      const dialogs = document.querySelectorAll('.dialog');
      dialogs.forEach(dialog => {
        dialog.style.display = 'none';
        dialog.remove();
      });
      const blur = document.getElementById('blur-bg');
      if (blur) blur.remove();
    }

    // Show the calendar schedule in names-container (existing behavior)
    function showSchedule() {
       const container = document.getElementById('names-container');
  
       // Hide patient appointments container when showing schedule
       document.getElementById('patient-appointments-container').style.display = 'none';
  
       const monthYear = months[currentMonthIndex] + ' ' + new Date().getFullYear();
       const firstDayOfMonth = new Date(new Date().getFullYear(), currentMonthIndex, 1).getDay();
       const daysInMonth = new Date(new Date().getFullYear(), currentMonthIndex + 1, 0).getDate();

       selectedMonthIndex = currentMonthIndex;
       selectedYear = new Date().getFullYear();

       let html = ``;
       container.innerHTML = html;

       html += `<div class="calendar-header">
                  <button onclick="changeMonth(-1)">&lt;</button>
                  <h4>${monthYear}</h4>
                  <button onclick="changeMonth(1)">&gt;</button>
                </div>
                <div class="calendar-days">`;

        // Display days of the week
        daysOfWeek.forEach(day => {
         html += `<div>${day}</div>`;
        });

        html += `</div><div class="calendar">`;

        for (let i = 0; i < firstDayOfMonth; i++) {
         html += `<div class="calendar-day"></div>`;
        }

        for (let i = 1; i <= daysInMonth; i++) {
          let statusClass = '';
          if (i === 20) {
             statusClass = 'scheduled';
          } else if (i === 26) {
             statusClass = 'cancelled';
          } else {
             statusClass = 'no-schedule';
          }

          html += `<div class="calendar-day ${statusClass}" onclick="showDayDetails('${i}')">${i}</div>`;
        }

        html += `</div>`;
        container.innerHTML = html;

        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = 'X';
        closeBtn.classList.add('close-btn');
        closeBtn.onclick = closeNames;
        container.appendChild(closeBtn);
     }

     // Display details for a clicked calendar day in 'names-container' (calendar area)
     function showDayDetails(day) {
       const scheduleNotes = {
         '20': 'Active Schedule: Patient Appointment at 9:30 AM. <br>Link: <a href="https://meet.google.com/">Join GMeet</a>',
         '25': 'Active Schedule: Follow-up Appointment at 11:00 AM. <br>Link: <a href="https://meet.google.com/">Join GMeet</a>',
         '26': 'Cancelled Schedule. <br>Link: N/A',
        };

        const year = new Date().getFullYear();
        const month = String(currentMonthIndex + 1).padStart(2, '0');
        const dayStr = String(day).padStart(2, '0');
        const formattedDate = `${year}-${month}-${dayStr}`;

        const note = scheduleNotes[day] || 'No schedule for this day.';
        const notesContainer = document.getElementById('names-container');
        notesContainer.innerHTML = `<h4>Details for ${months[currentMonthIndex]} ${day}, ${selectedYear}:</h4><p>${note}</p>`;

        fetch(`get_appointments_doc.php?date=${formattedDate}`)
           .then(response => response.json())
           .then(data => {
            const notesContainer = document.getElementById('names-container');
            if (data.length === 0) {
              notesContainer.innerHTML = `<h4>Appointments for ${months[currentMonthIndex]} ${day}, ${year}:</h4><p>No appointments scheduled.</p>`;
            } else {
              let html = `<h4>Appointments for ${months[currentMonthIndex]} ${day}, ${year}:</h4>`;
              data.forEach(appointment => {
                html += `<div class="name-item">
                           <div class="name-info">
                             <strong>Name:</strong> ${appointment.name}<br>
                             <strong>Time:</strong> ${appointment.time}
                           </div>
                         </div>>`;
              });
              notesContainer.innerHTML = html;
            }

            const closeBtn = document.createElement('span');
            closeBtn.innerHTML = 'X';
            closeBtn.classList.add('close-btn');
            closeBtn.onClick = closeNames;
            notesContainer.appendChild(closeBtn);
           })
           .catch(error => {
            console.error('Error fetching appointments:', error);
           });

      }

      function changeMonth(offset) {
        currentMonthIndex += offset;
        if (currentMonthIndex < 0) {
          currentMonthIndex = 11;
        } else if (currentMonthIndex > 11) {
          currentMonthIndex = 0;
        }
      }
  </script>
</head>

<body>
  <div class="header">
    <div class="logo">
      <img src="cream1.png" alt="Logo" />
    </div>
    <div class="nav">
      <div style="cursor:pointer;" onclick="showProfile()">PROFILE</div>
      <div onclick="showLogout()" style="cursor:pointer;">LOG OUT</div>
    </div>
  </div>
  <div class="main-container">
    <div class="sidebar">
      <div>
        <h2>NOTIFICATIONS</h2>
      </div>
      <div class="sidebar-footer">
        <div class="logo-group">
          <img src="PALAYAN.png" alt="Logo 1" />
          <img src="DICT-LOGO.png" alt="Logo 2" />
          <img src="Bagong_Pilipinas_logo.png" alt="Logo 3" />
          <img src="wup.png" alt="Logo 4" />
        </div>
        <p>
          Copyright © Ginhawa 2025<br />
          Wesleyan University - Philippines Interns<br />
          All Rights Reserved
        </p>
      </div>
    </div>
    <div class="main">
      <h3>Mabuhay!</h3>
      <div class="button-group">
        <button onclick="showPatientAppointments()">Patient</button>
        <button onclick="showSchedule()">Schedule</button>
      </div>
      <!-- Container showing calendar day details -->
      <div id="names-container"style="display: none;"></div>
      <!-- Separate container for patient tab appointment details -->
      <div id="patient-appointments-container"style="display: none;"></div>
      <div id="schedule-content"style="display: none;"></div>
    </div>
      <?php echo $appointmentsHTML; ?>
  </div>
  
</body>
</html>

