<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $conn = new mysqli("localhost", "root", "", "db1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch doctors
$doctors = [];
$sql = "SELECT * FROM doctor";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

$patients = [];
$patient_sql = "SELECT * FROM user";
$patient_result = $conn->query($patient_sql);
if ($patient_result && $patient_result->num_rows > 0) {
    while ($row = $patient_result->fetch_assoc()) {
        $patients[] = $row;
    }
}

$conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>GINHAWA - Notifications</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet" />
  <style>
    /* existing styles unchanged */
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
      margin: 30px 20px 0 20px;
      box-shadow: 0 8px 10px -4px rgba(36, 64, 50, 0.5);
      cursor: pointer;
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
    }

    .main {
      flex: 1;
      padding: 40px;
      background-color: #f9f7d9;
    }

    .main h3 {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .button-group {
      display: flex;
      gap: 20px;
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
    }

    .button-group button:hover {
      background-color: #2f5747;
    }

    .name-box {
      background-color: #fff;
      border: 1px solid #ccc;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      padding: 30px;
      border-radius: 8px;
      margin-top: 20px;
      animation: slideUp 0.4s ease;
      max-width: 800px;
    }

    .name-box h4 {
      margin-bottom: 15px;
      font-size: 18px;
      color: #244032;
    }

    .name-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
      border-bottom: 1px solid #eee;
      padding-bottom: 8px;
    }

    .name-info strong {
      display: block;
      font-size: 15px;
      color: #333;
    }

    .name-info small {
      color: #777;
      font-size: 12px;
    }

    .name-item button {
      background-color: #244032;
      border: none;
      color: #fff;
      margin-left: 5px;
      padding: 5px 12px;
      border-radius: 6px;
      cursor: pointer;
    }

    .close-btn {
      float: left;             
      
      top: 10px;
      right: 10px;
      margin-bottom: 0;
    }

    @keyframes slideUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(36, 64, 50, 0.7);
      backdrop-filter: blur(4px);
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background-color: #fffde4;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 8px 20px rgba(36, 64, 50, 0.5);
      font-weight: 600;
      font-size: 16px;
      color: #244032;
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .modal-buttons {
      margin-top: 20px;
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .modal-btn {
      padding: 8px 24px;
      border: none;
      border-radius: 25px;
      font-weight: 700;
      cursor: pointer;
      font-size: 14px;
    }

    .modal-btn.yes {
      background-color: #244032;
      color: #fffde4;
    }

    .modal-btn.no {
      background-color: #ccc;
      color: #444;
    }

    .modal-btn:hover {
      opacity: 0.9;
    }

    .modal input {
      width: 100%;
      padding: 8px;
      margin-top: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    .modal-content h3 {
      margin-bottom: 20px;
    }

    .modal-content label {
      font-size: 14px;
      margin-top: 10px;
      display: block;
      text-align: left;
    }

    .blur {
      filter: blur(5px);
      pointer-events: none;
    }

    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.4);
      z-index: 999;
    }

    .notification {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 25px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      border-radius: 10px;
      z-index: 1000;
      border-left: 10px solid #4CAF50;
    }

    .notification.error {
      border-left-color: #f44336;
    }

    .btn {
      padding: 10px 20px;
      background: #007bff;
      border: none;
      color: white;
      cursor: pointer;
    }


    .button-link {
  display: inline-block;
  padding: 5px 10px;
  margin: 0 3px;
  background-color: #4CAF50;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  font-size: 14px;
}
.button-link.delete {
  background-color: #f44336;
}
.button-link:hover {
  opacity: 0.8;
}

  </style>
</head>

<body>
  <div class="header">
    <div class="logo">
      <img src="cream1.png" alt="Logo" />
      <div><strong></strong><br /><small></small></div>
    </div>
    <div class="nav">
      <div onclick="document.getElementById('profile-modal').style.display='flex'" style="cursor:pointer;">PROFILE</div>
      <div onclick="document.getElementById('create-account-modal').style.display='flex'" style="cursor:pointer;">ADD DOCTOR</div>
      <div id="logout-btn" style="cursor:pointer;">LOG OUT</div>
    </div>
  </div>

  <div class="main-container">
    <div class="sidebar">
      <div><h2>NOTIFICATIONS</h2></div>
      <div class="sidebar-footer">
        <div class="logo-group">
          <img src="PALAYAN.png" />
          <img src="DICT-LOGO.png" />
          <img src="Bagong_Pilipinas_logo.png" />
          <img src="wup.png" />
        </div>
        <p>Copyright © Ginhawa 2025<br />Wesleyan University - Philippines Interns<br />All Rights Reserved</p>
      </div>
    </div>

    <div class="main">
      <h3>Mabuhay!</h3>
      <div class="button-group">
        <button onclick="toggleNames('Patient')">Patient</button>
        <button onclick="toggleNames('Doctor')">Doctor</button>
      </div>
      <div id="names-container">
  <div id="doctor-names" class="name-box" style="display:none;">
    <h4>Doctors</h4>
    <?php foreach ($doctors as $doc): ?>
      <div class="name-item">
        <div class="name-info">
          <strong><?= htmlspecialchars($doc['fullName']) ?></strong>
          <small><?= htmlspecialchars($doc['specialization']) ?></small>
        </div>
        <div>
          <button onclick="viewCalendar()">Calendar</button>
          <button onclick="confirmDelete('<?= $doc['id'] ?>')">Delete</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div id="patient-names" class="name-box" style="display:none;">
  <h4>Patients</h4>
  <?php foreach ($patients as $patient): ?>
    <div class="name-item">
      <div class="name-info">
        <strong><?= htmlspecialchars($patient['firstName'] . ' ' . $patient['lastName']) ?></strong>
        <small><?= htmlspecialchars($patient['phoneNumber']) ?></small>
      </div>
      <div>
          <button onclick="viewCalendar()">Edit</button>
          <button onclick="confirmDeletePatient('<?= $patient['patient_id'] ?>')">Delete</button>
        </div>
    </div>
  <?php endforeach; ?>
</div>

    </div>
  </div>

  <!-- Modals -->
  <div id="logout-modal" class="modal">
    <div class="modal-content">
      <p>ARE YOU SURE YOU WANT TO LOG OUT?</p>
      <div class="modal-buttons">
        <a href="logout.php" class="modal-btn yes">YES</a>
        <a href="admindashboard.html" class="modal-btn no">NO</a>      
      </div>
    </div>
  </div>

  <div id="profile-modal" class="modal">
    <div class="modal-content">
      <h3>Edit Profile</h3>
      <label>Username:</label>
      <input type="text" id="profile-contact" value="Justine" />
      <label>Name:</label>
      <input type="text" id="profile-contact" value="Justine Tabor" />
      <div class="modal-buttons">
        <button class="modal-btn yes" onclick="saveProfile()">Save Changes</button>
        <button class="modal-btn no" onclick="document.getElementById('profile-modal').style.display='none'">Cancel</button>
      </div>
    </div>
  </div>

  <div id="create-account-modal" class="modal">
    <div class="modal-content">
      <h3>ADD DOCTOR</h3>
      <form method="POST" action="create_account.php">
        <label>Username:</label>
        <input type="text" id="user_name" name="user_name" required>
        <label>Full Name:</label>
        <input type="text" id="fullName" name="fullName" required/>
        <label>Specialization:</label>
        <input type="text" id="specialization" name="specialization" required/>
        <label>Password:</label>
        <input type="password" id="password" name="password" required/>
        <div class="modal-buttons">
          <button type="submit" class="modal-btn yes">Create</button>
          <button type="button" class="modal-btn no" onclick="document.getElementById('create-account-modal').style.display='none'">Cancel</button>
        </div>
      </form>     
    </div>
  </div>

    <!-- Notification Popup -->
  <div id="overlay" class="overlay" style="display:none;"></div>
  <div id="notification" class="notification" style="display:none;">
    <p id="notif-message"></p>
    <button class="btn" onclick="closeNotif()">Close</button>
  </div>

  <div id="confirm-delete-modal" class="modal">
    <div class="modal-content">
      <p>Are you sure you want to delete this entry?</p>
      <div class="modal-buttons">
        <button class="modal-btn yes" id="confirm-delete-btn">Yes</button>
        <button class="modal-btn no" id="cancel-delete-btn">No</button>
      </div>
    </div>
  </div>

  <div id="calendar-modal" class="modal">
    <div class="modal-content">
      <h3>Doctor's Calendar</h3>
      <p>Here you can view or add appointments.</p>
      <div class="modal-buttons">
        <button class="modal-btn no" onclick="document.getElementById('calendar-modal').style.display='none'">Close</button>
      </div>
    </div>
  </div>

  <!-- NEW Edit Entry Modal -->
  <div id="edit-entry-modal" class="modal">
    <div class="modal-content">
      <h3>Edit Entry</h3>
      <label>Name:</label>
      <input type="text" id="edit-name" />
      <label>Status:</label>
      <input type="text" id="edit-status" />
      <label>Time:</label>
      <input type="text" id="edit-time" />
      <div class="modal-buttons">
        <button class="modal-btn yes" onclick="saveEdit()">Save</button>
        <button class="modal-btn no" onclick="closeEditModal()">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    
    let currentType = null; // Tracks which list is currently open
    let currentEditIndex = null;

    const namesContainer = document.getElementById('names-container');

    function toggleNames(type) {
      if (currentType === type) {
        // If already open, close it
        namesContainer.innerHTML = '';
        currentType = null;
        return;
      }
      currentType = type;
      let list = type === 'Patient' ? patients : doctors;

      let html = `<button class="close-btn" onclick="closeNames()"></button>`;
      html += `<div class="name-box"><h4>${type} List</h4>`;
      list.forEach((item, index) => {
        html += `
          <div class="name-item">
            <div class="name-info">
              <strong>${item.name}</strong>
              <small>Status: ${item.status}</small>
              <small>Time: ${item.time}</small>
            </div>
            <div>
              <button onclick="openEditModal('${type}', ${index})">Edit</button>
              <button onclick="openDeleteModal('${type}', ${index})">Delete</button>
              ${type === 'Doctor' ? `<button onclick="openCalendarModal()">Calendar</button>` : ''}
            </div>
          </div>`;
      });
      html += `</div>`;
      namesContainer.innerHTML = html;
    }

    function closeNames() {
      namesContainer.innerHTML = '';
      currentType = null;
    }

    function openEditModal(type, index) {
      currentEditIndex = { type, index };
      const entry = type === 'Patient' ? patients[index] : doctors[index];
      document.getElementById('edit-name').value = entry.name;
      document.getElementById('edit-status').value = entry.status;
      document.getElementById('edit-time').value = entry.time;
      document.getElementById('edit-entry-modal').style.display = 'flex';
    }

    function closeEditModal() {
      document.getElementById('edit-entry-modal').style.display = 'none';
    }

    function saveEdit() {
      if (!currentEditIndex) return;
      const { type, index } = currentEditIndex;
      const list = type === 'Patient' ? patients : doctors;
      list[index].name = document.getElementById('edit-name').value;
      list[index].status = document.getElementById('edit-status').value;
      list[index].time = document.getElementById('edit-time').value;

      closeEditModal();
      toggleNames(type); // refresh list
    }

    let deleteTarget = null;

    function openDeleteModal(type, index) {
      deleteTarget = { type, index };
      document.getElementById('confirm-delete-modal').style.display = 'flex';
    }

    document.getElementById('confirm-delete-btn').onclick = () => {
      if (!deleteTarget) return;
      const { type, index } = deleteTarget;
      const list = type === 'Patient' ? patients : doctors;
      list.splice(index, 1);
      document.getElementById('confirm-delete-modal').style.display = 'none';
      toggleNames(type); // refresh list
    };

    document.getElementById('cancel-delete-btn').onclick = () => {
      document.getElementById('confirm-delete-modal').style.display = 'none';
    };

    function openCalendarModal() {
      document.getElementById('calendar-modal').style.display = 'flex';
    }

    // Profile Modal save
    function saveProfile() {
      alert('Profile changes saved (simulation).');
      document.getElementById('profile-modal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
  const doctorSelect = document.getElementById('doctorSelect');

  fetch('get_doctors.php')
    .then(response => response.json())
    .then(doctors => {
      doctors.forEach(name => {
        const option = document.createElement('option');
        option.value = name;
        option.textContent = name;
        doctorSelect.appendChild(option);
      });
    })
    .catch(err => {
      console.error('Error loading doctors:', err);
    });
});

    // Create account
    function createAccount() {
      alert('Account created (simulation).');
      document.getElementById('create-account-modal').style.display = 'none';
    }

    // Logout modal and button
    const logoutBtn = document.getElementById('logout-btn');
    const logoutModal = document.getElementById('logout-modal');
    const confirmLogoutBtn = document.getElementById('confirm-logout');
    const cancelLogoutBtn = document.getElementById('cancel-logout');

    logoutBtn.onclick = () => {
      logoutModal.style.display = 'flex';
    };

    confirmLogoutBtn.onclick = () => {
      logoutModal.style.display = 'none';
      alert('Logged out (simulation).');
    };

    cancelLogoutBtn.onclick = () => {
      logoutModal.style.display = 'none';
    };

    // Close modals on outside click
    window.onclick = function(event) {
      const modals = document.querySelectorAll('.modal');
      modals.forEach(modal => {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      });
    };


    window.onload = function() {
      fetch('session_status.php')
        .then(response => response.json())
        .then(data => {
          if (data && data.message) {
            document.getElementById("notif-message").innerText = data.message;
            document.getElementById("notification").classList.add(data.type === 'error' ? 'error' : 'success');
            document.getElementById("notification").style.display = "block";
            document.getElementById("overlay").style.display = "block";

            // Blur main content
            document.body.classList.add('blur');
          }
        });
    };

    function closeNotif() {
        document.getElementById("notification").style.display = "none";
        document.getElementById("overlay").style.display = "none";
        document.body.classList.remove('blur');
    }
    function toggleNames(type) {
    document.getElementById('doctor-names').style.display = type === 'Doctor' ? 'block' : 'none';
    // You can also hide the patient list if added later
  }

  function confirmDelete(id) {
    const modal = document.getElementById('confirm-delete-modal');
    modal.style.display = 'flex';
    document.getElementById('confirm-delete-btn').onclick = function () {
      // Redirect to delete script (create this PHP file yourself)
      window.location.href = 'delete_doctor.php?id=' + id;
    };
    document.getElementById('cancel-delete-btn').onclick = function () {
      modal.style.display = 'none';
    };
  }

    function confirmDeletepatient(id) {
    const modal = document.getElementById('confirm-delete-modal');
    modal.style.display = 'flex';
    document.getElementById('confirm-delete-btn').onclick = function () {
      // Redirect to delete script (create this PHP file yourself)
      window.location.href = 'patient_delete_FINALE.php?id=' + id;
    };
    document.getElementById('cancel-delete-btn').onclick = function () {
      modal.style.display = 'none';
    };
  }

  function viewCalendar() {
    document.getElementById('calendar-modal').style.display = 'flex';
  }

function toggleNames(type) {
  document.getElementById("doctor-names").style.display = (type === "Doctor") ? "block" : "none";
  document.getElementById("patient-names").style.display = (type === "Patient") ? "block" : "none";
}

  function confirmDeletePatient(id) {
    if (confirm("Are you sure you want to delete this patient?")) {
      window.location.href = `patient_delete_FINALE.php?id=${id}`;
    }
  }

  </script>
</body>
</html>
