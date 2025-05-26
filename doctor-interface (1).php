<?php
session_start();

if (!isset($_SESSION['fullName'])) {
    echo "Doctor not logged in.";
    exit();
}

$doctorName = $_SESSION['fullName']; // ✅ Correct way to get doctor name

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
      margin-bottom: 30px;
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
    }

    .main h3 {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .button-group {
      display: flex;
      gap: 20px;
      margin-bottom: 25px ;
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
      margin-top: 30px;

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
   .dialog {
      z-index: 1000;
}
  .name-info {
      margin-bottom: 10px;
      font-size: 16px;
    }

  .name-item button {
      margin-top: 10px;
      width: 80px;
      margin-right: 10px;
      padding: 8px 15px;
      background-color: #244032;
      color: #fffde4;
      border: none;
      border-radius: 20px; /* Curved corners */
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    /* Close Button Style */
  .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 20px;
      color: #244032;
      cursor: pointer;
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

    let patients = [
      { name: 'Meloja Ortiz', status: 'Admitted', time: '09:30 AM' },
      { name: 'Rollin Patricio', status: 'Discharged', time: '04:00 PM' }
    ];

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
    <div><strong>Name:</strong> Doc.</div>
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
  // alert('Logged out successfully'); ❌ remove this
  window.location.href = 'logout.php'; // ✅ Redirect to PHP logout
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


    // Patient and Schedule functionality
    function showNames(type) {
      const container = document.getElementById('names-container');
      container.innerHTML = 'Loading...';

      //fetch today's appointments from the backend
      fetch('getAppointments_doc.php')
         .then(response => response.json())
         .then(data => {
           let html = '';

           if (data.length === 0) {
            html = `<div>No patients scheduled for today.</div>`;
           } else {
             data.forEach((item, index) => {
                html += `<div class="name-item">
                          <div class="name-info">
                            <strong>Name:</strong> ${item.name}<br>
                            <strong>Status:</strong> ${item.status}<br>
                            <strong>Time:</strong> ${item.time}
                          </div>
                        <div><button onclick="editPatient(${index})">Edit</button><button onclick="deletePatient(${index})">Delete</button></div>
                     </div>`;
           });
        }

        container.innerHTML = html;

        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = 'X';
        closeBtn.classList.add('close-btn');
        closeBtn.onclick = closeNames;
        container.appendChild(closeBtn);
      })
      .catch(error => {
        container.innerHTML = `<div>Error loading patient list.</div>`;
        console.error('Error fetching patients:', error);
      });
    }

    function closeNames() {
      document.getElementById('names-container').innerHTML = '';
    }

   function editPatient(index) {
  const patient = patients[index];

  // Add blur background
  const blur = document.createElement('div');
  blur.className = 'blur-bg';
  blur.id = 'blur-bg';
  document.body.appendChild(blur);

  const editDialog = document.createElement('div');
  editDialog.classList.add('dialog');
  editDialog.innerHTML = ` 
    <h3>Edit Patient</h3>
    <label for="edit-status">Status:</label>
    <input type="text" id="edit-status" value="${patient.status}">
    <label for="edit-time">Time:</label>
    <input type="datetime-local" id="edit-time" value="${formatDateForInput(patient.time)}">
    <button onclick="saveChanges(${index}, this)">Save</button>
    <button onclick="closeDialog()">Cancel</button>
  `;
  document.body.appendChild(editDialog);
  editDialog.style.display = 'block';
}

    function saveChanges(index, button) {
      const status = document.getElementById('edit-status').value;
      const time = document.getElementById('edit-time').value;
      patients[index].status = status;
      patients[index].time = time;
      closeDialog();
      showNames('Patient');
    }

   function deletePatient(index) {
  // Add blur background
  const blur = document.createElement('div');
  blur.className = 'blur-bg';
  blur.id = 'blur-bg';
  document.body.appendChild(blur);

  const confirmDialog = document.createElement('div');
  confirmDialog.classList.add('dialog');
  confirmDialog.innerHTML = ` 
    <h3>Are you sure you want to delete this patient?</h3>
    <button onclick="confirmDelete(${index})">Yes</button>
    <button onclick="closeDialog()">No</button>
  `;
  document.body.appendChild(confirmDialog);
  confirmDialog.style.display = 'block';
}


    function confirmDelete(index) {
      patients.splice(index, 1);
      closeDialog();
      showNames('Patient');
    }

    function showSchedule() {
      const container = document.getElementById('names-container');
      const monthYear = months[currentMonthIndex] + ' ' + new Date().getFullYear();
      const firstDayOfMonth = new Date(new Date().getFullYear(), currentMonthIndex, 1).getDay();
      const daysInMonth = new Date(new Date().getFullYear(), currentMonthIndex + 1, 0).getDate();

      let html = ``;
      html += `<div class="calendar-header">
                <button onclick="changeMonth(-1)">&lt;</button>
                <h4>${monthYear}</h4>
                <button onclick="changeMonth(1)">&gt;</button>
              </div>
              <div class="calendar-days">`;

      // Displaying days of the week
      daysOfWeek.forEach(day => {
        html += `<div>${day}</div>`;
      });

      html += `</div><div class="calendar">`;

      for (let i = 0; i < firstDayOfMonth; i++) {
        html += `<div class="calendar-day"></div>`;
      }

      // Modify calendar days with scheduled status (lightblue), no schedule (lightgray), and cancelled (lightpink)
      for (let i = 1; i <= daysInMonth; i++) {
        let statusClass = '';
        if (i === 20) {
          statusClass = 'scheduled'; // Example: scheduled for May 20
        } else if (i === 26) {
          statusClass = 'cancelled'; // Example: cancelled on May 26
        } else {
          statusClass = 'no-schedule'; // Default to no schedule
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

    // Show the details of a clicked day
    function showDayDetails(day) {
      const scheduleNotes = {
        '20': 'Active Schedule: Patient Appointment at 9:30 AM. <br>Link: <a href="https://meet.google.com/">Join GMeet</a>',
        '25': 'Active Schedule: Follow-up Appointment at 11:00 AM. <br>Link: <a href="https://meet.google.com/">Join GMeet</a>',
        '26': 'Cancelled Schedule. <br>Link: N/A',
        // Add more dates and details as necessary
      };

      const year = new Date().getFullYear();
      const month = String(currentMonthIndex + 1).padStart(2, '0');
      const dayStr = String(day).padStart(2, '0');
      const formattedDate = `${year}-${month}-${dayStr}`;

      const note = scheduleNotes[day] || 'No schedule for this day.';
      const notesContainer = document.getElementById('names-container');
      notesContainer.innerHTML = `<h4>Details for May ${day}, 2025:</h4><p>${note}</p>`;

      fetch(`get_appointments_doc.php?date=${formattedDate}`)
         .then(response => response.json())
         .then(data => {
            const notesContainer = document.getElementById('names-container');
            if (data.length === 0) {
              notesContainer.innerHTML = `<h4>Appointments for ${months[currentMonthIndex]} ${day}, ${year}:</h4><p>No appointments scheduled.</p>`;
            }else{
              let html = `<h4>Appointments for ${months[currentMonthIndex]} ${day}, ${year}:</h4>`;
              data.forEach(appointment => {
                html += `<div class="name-item">
                          <div class="name-info">
                             <strong>Name:</strong> ${appointment.name}<br>
                             <strong>Time:</strong> ${appointment.time}
                           </div>
                         </div>`;
              });
              notesContainer.innerHTML = html;
            }       

            const closeBtn = document.createElement('span');
            closeBtn.innerHTML = 'X';
            closeBtn.classList.add('close-btn');
            closeBtn.onclick = closeNames;
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

      showSchedule();
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
      <h3>Hello, Doc.</h3>
      <div class="button-group">
        <button onclick="showNames('Patient')">Patient</button>
        <button onclick="showSchedule()">Schedule</button>
      </div>
      <div id="names-container"></div>
    </div>
  </div>
</body>
</html>
