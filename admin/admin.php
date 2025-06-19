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
  <title>Azifa Homestay - Admin Panel</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Admin CSS -->
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="stylesheet" href="../css/flash.css">

  <!-- Pace loading bar -->
  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>

<body>

  <!-- Mobile view block -->
  <div id="mobileview">
    <h5>Admin panel is not available on mobile view</h5>
  </div>

  <!-- Top Navbar -->
  <nav class="uppernav">
    <div class="logo">
      <img class="azifa-logo" src="../image/bluebirdlogo.png" alt="logo">
      <p>AZIFA HOMESTAY</p>
    </div>
    <div class="logout">
      <a href="../logout.php"><button class="btn btn-primary">Logout</button></a>
    </div>
  </nav>

  <!-- Side Navbar -->
  <nav class="sidenav">
    <ul>
      <li class="pagebtn active"><img src="../image/icon/dashboard.png">&nbsp;&nbsp; Dashboard</li>
      <li class="pagebtn"><img src="../image/icon/bed.png">&nbsp;&nbsp; House Bookings</li>
      <li class="pagebtn"><img src="../image/icon/bedroom.png">&nbsp;&nbsp; Houses</li>
      <!-- Removed Payment and Staff menu items -->
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="mainscreen">
    <iframe class="frames frame1 active" src="./dashboard.php" frameborder="0"></iframe>
    <iframe class="frames frame2" src="./housebook.php" frameborder="0"></iframe>
    <iframe class="frames frame3" src="./house.php" frameborder="0"></iframe>
    <!-- Removed Payment and Staff iframes -->
  </div>

  <script src="./javascript/script.js"></script>

</body>
</html>
