<?php
session_start();
include '../config.php';

// ✅ Secure: only logged-in admin can access
if (!isset($_SESSION['usermail']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Azifa Homestay</title>
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="stylesheet" href="../css/flash.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .dashboard-container {
      max-width: 500px;
      margin: 100px auto;
      background: #f5fbff;
      border-radius: 18px;
      box-shadow: 0 6px 20px rgba(44, 62, 80, 0.12);
      text-align: center;
      padding: 45px 30px 35px 30px;
    }
    .dashboard-container h1 {
      color: #2365b7;
      font-size: 2.5rem;
      margin-bottom: 10px;
      letter-spacing: 2px;
    }
    .dashboard-message {
      font-size: 1.18rem;
      color: #232323;
      margin-top: 10px;
    }
    @media (max-width: 600px) {
      .dashboard-container {
        margin: 30px 5px;
        padding: 18px 8px 18px 8px;
      }
      .dashboard-container h1 { font-size: 1.5rem; }
      .dashboard-message { font-size: 1rem; }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h1>Welcome, Admin!</h1>
    <p class="dashboard-message">
      You are logged in as <b><?php echo htmlspecialchars($_SESSION['usermail']); ?></b>.<br>
      Use the sidebar to manage house bookings and homestays.
    </p>
    <img src="../image/bluebirdlogo.png" alt="Azifa Homestay Logo" style="width:120px;margin-top:30px;">
  </div>
</body>
</html>
