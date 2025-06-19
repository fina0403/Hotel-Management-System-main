<?php
include 'config.php';
session_start();

if (!isset($_SESSION['usermail'])) {
  header("Location: index.php");
  exit;
}

// Handle booking submission
if (isset($_POST['guestdetailsubmit'])) {
  $Name = htmlspecialchars(trim($_POST['Name']));
  $Email = $_SESSION['usermail'];
  $Phone = htmlspecialchars(trim($_POST['Phone']));
  $HouseType = htmlspecialchars(trim($_POST['HouseType']));
  $cin = $_POST['cin'];
  $cout = $_POST['cout'];

  if (strtotime($cin) >= strtotime($cout)) {
    echo "<script>swal({ title: 'Check-in must be before Check-out!', icon: 'error' });</script>";
    exit;
  }

  if ($Name && $Email && $Phone && $HouseType && $cin && $cout) {
    $stmt = $conn->prepare("SELECT * FROM housebook WHERE House=? AND Status!='Cancelled' AND (CheckIn < ? AND CheckOut > ?)");
    $stmt->bind_param("sss", $HouseType, $cout, $cin);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      echo "<script>swal({ title: 'This house is already booked for these dates!', icon: 'error' });</script>";
    } else {
      $Status = "NotConfirm";
      $stmt2 = $conn->prepare("INSERT INTO housebook (Name, Email, Phone, House, HouseType, CheckIn, CheckOut, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt2->bind_param("ssssssss", $Name, $Email, $Phone, $HouseType, $HouseType, $cin, $cout, $Status);
      if ($stmt2->execute()) {
        echo "<script>swal({ title: 'Reservation Successful!', icon: 'success' });</script>";
      } else {
        echo "<script>swal({ title: 'Something went wrong!', icon: 'error' });</script>";
      }
      $stmt2->close();
    }
    $stmt->close();
  } else {
    echo "<script>swal({ title: 'Please fill in all required details!', icon: 'error' });</script>";
  }
}

// Handle search
$search_results = [];
if (isset($_POST['searchsubmit'])) {
  $search_name = htmlspecialchars(trim($_POST['search_name']));
  $search_cin = $_POST['search_cin'];
  $search_cout = $_POST['search_cout'];
  $search_person = intval($_POST['search_person']);

  $where = "WHERE 1";
  if ($search_name != "") {
    $where .= " AND HouseName LIKE '%$search_name%'";
  }

  $houses = mysqli_query($conn, "SELECT * FROM house $where");
  while ($h = mysqli_fetch_assoc($houses)) {
    $housename = $h['HouseName'];
    $booked = mysqli_query($conn, "SELECT * FROM housebook WHERE House='$housename' AND Status!='Cancelled' AND (CheckIn < '$search_cout' AND CheckOut > '$search_cin')");
    if (mysqli_num_rows($booked) == 0) {
      $search_results[] = $h;
    }
  }
}

if (isset($_POST['cancelbooking']) && !empty($_POST['cancelid'])) {
  $cid = intval($_POST['cancelid']);
  $mymail = $_SESSION['usermail'];
  mysqli_query($conn, "UPDATE housebook SET Status='Cancelled' WHERE id=$cid AND Email='$mymail' AND Status='NotConfirm'");
  echo "<script>location.href=location.href;</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Azifa Homestay</title>
  <link rel="stylesheet" href="./css/home.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <style>
    /* Extra style for search bar */
    .search-section {
      background: #f4f8ff;
      padding: 60px 20px;
      text-align: center;
    }
    .search-section h2 {
      font-size: 36px;
      margin-bottom: 30px;
      font-weight: bold;
    }
    .search-section form {
      max-width: 1000px;
      margin: 0 auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .search-section .form-control {
      border-radius: 10px;
    }
    .search-section button {
      border-radius: 10px;
    }
    .mybookingstable {
      width: 92%; margin: 24px auto 48px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px #eee;
    }
    .mybookingstable th, .mybookingstable td { text-align:center; padding:11px; }
    .status-Approved { color: green; font-weight: bold; }
    .status-NotConfirm { color: orange; font-weight: bold; }
    .status-Cancelled { color: #999; }
  </style>
</head>
<body>

<nav>
  <div class="logo">
    <img class="azifa-logo" src="./image/bluebirdlogo.png" alt="logo">
    <p>AZIFA HOMESTAY</p>
  </div>
  <ul>
    <li><a href="#searchbar">Search</a></li>
    <li><a href="#secondsection">Houses</a></li>
    <li><a href="#mybookingsection">My Bookings</a></li>
    <li><a href="#contactus">Contact Us</a></li>
    <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
  </ul>
</nav>

<!-- ✅ Beautiful Search Section -->
<section id="searchbar" class="search-section">
  <h2>Find Your Perfect Homestay</h2>
  <form method="POST" action="#secondsection">
    <div class="row g-3">
      <div class="col-md-3">
        <input type="text" class="form-control" name="search_name" placeholder="Homestay Name" value="<?php echo isset($_POST['search_name']) ? htmlspecialchars($_POST['search_name']) : ''; ?>">
      </div>
      <div class="col-md-3">
        <input type="date" class="form-control" name="search_cin" required value="<?php echo isset($_POST['search_cin']) ? $_POST['search_cin'] : ''; ?>">
      </div>
      <div class="col-md-3">
        <input type="date" class="form-control" name="search_cout" required value="<?php echo isset($_POST['search_cout']) ? $_POST['search_cout'] : ''; ?>">
      </div>
      <div class="col-md-2">
        <input type="number" class="form-control" name="search_person" placeholder="No. of Persons" min="1" required value="<?php echo isset($_POST['search_person']) ? $_POST['search_person'] : '1'; ?>">
      </div>
      <div class="col-md-1 d-grid">
        <button type="submit" name="searchsubmit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>
</section>

<!-- ✅ Carousel remains -->
<section id="firstsection" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active"><img class="carousel-image" src="./image/hotel1.jpg"></div>
    <div class="carousel-item"><img class="carousel-image" src="./image/hotel2.jpg"></div>
    <div class="carousel-item"><img class="carousel-image" src="./image/hotel3.jpg"></div>
    <div class="carousel-item"><img class="carousel-image" src="./image/hotel4.jpg"></div>
    <div class="welcomeline"><h1>Welcome to Azifa Homestay</h1></div>
  </div>
</section>

<!-- ✅ Homestays Listing -->
<section id="secondsection">
  <img src="./image/homeanimatebg.svg">
  <div class="ourroom">
    <h1 style="text-align:center;">≼ Our Homestays ≽</h1>
    <div class="roomselect">
    <?php
    if (isset($_POST['searchsubmit'])) {
      if (empty($search_results)) {
        echo "<p style='text-align:center;width:100%;font-weight:bold;'>No results found. Please try other dates or name.</p>";
      } else {
        foreach ($search_results as $r) {
          echo "<div class='roombox'><div class='roomdata'>
          <h2>{$r['HouseName']}</h2><p>Type: {$r['HouseType']}</p>
          <p>Bedding: {$r['Bedding']}</p><p>Place: {$r['Place']}</p>
          <button class='btn btn-primary bookbtn' onclick='openbox()'>Book</button></div></div>";
        }
      }
    } else {
      $q = mysqli_query($conn, "SELECT * FROM house");
      while ($r = mysqli_fetch_assoc($q)) {
        echo "<div class='roombox'><div class='roomdata'>
        <h2>{$r['HouseName']}</h2><p>Type: {$r['HouseType']}</p>
        <p>Bedding: {$r['Bedding']}</p><p>Place: {$r['Place']}</p>
        <button class='btn btn-primary bookbtn' onclick='openbox()'>Book</button></div></div>";
      }
    }
    ?>
    </div>
  </div>
</section>

<!-- ✅ My Bookings -->
<section id="mybookingsection">
  <h1 style="text-align:center;margin-top:20px;">≼ My Bookings ≽</h1>
  <table class="mybookingstable">
    <tr>
      <th>No</th>
      <th>House</th>
      <th>Check-In</th>
      <th>Check-Out</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    <?php
      $mymail = $_SESSION['usermail'];
      $q = mysqli_query($conn, "SELECT * FROM housebook WHERE Email='$mymail' ORDER BY CheckIn DESC");
      $i = 1;
      while ($r = mysqli_fetch_assoc($q)) {
        $statclass = "status-" . $r['Status'];
        echo "<tr>
          <td>{$i}</td>
          <td>{$r['House']}</td>
          <td>{$r['CheckIn']}</td>
          <td>{$r['CheckOut']}</td>
          <td class='$statclass'>{$r['Status']}</td>
          <td>";
        if ($r['Status'] == "NotConfirm") {
          echo "<form method='post' style='display:inline'><input type='hidden' name='cancelid' value='{$r['id']}'><button class='btn btn-sm btn-danger' name='cancelbooking' onclick=\"return confirm('Cancel this booking?')\">Cancel</button></form>";
        } else {
          echo "-";
        }
        echo "</td></tr>";
        $i++;
      }
    ?>
  </table>
</section>

<!-- ✅ Reservation Modal -->
<div id="guestdetailpanel">
  <form method="POST" class="guestdetailpanelform">
    <div class="head">
      <h3>RESERVATION</h3>
      <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
    </div>
    <div class="middle">
      <div class="guestinfo">
        <h4>Guest Information</h4>
        <input type="text" name="Name" placeholder="Full Name" required>
        <input type="email" name="Email" value="<?php echo $_SESSION['usermail']; ?>" readonly style="background:#eee">
        <input type="text" name="Phone" placeholder="Phone Number" required>
      </div>
      <div class="line"></div>
      <div class="reservationinfo">
        <h4>Reservation Info</h4>
        <select name="HouseType" required>
          <option value="">House Type</option>
          <?php
            $hq = mysqli_query($conn, "SELECT HouseName FROM house");
            while ($hr = mysqli_fetch_assoc($hq)) {
              echo "<option value='{$hr['HouseName']}'>{$hr['HouseName']}</option>";
            }
          ?>
        </select>
        <div class="datesection">
          <span><label>Check-In</label><input name="cin" type="date" required></span>
          <span><label>Check-Out</label><input name="cout" type="date" required></span>
        </div>
      </div>
    </div>
    <div class="footer"><button class="btn btn-success" name="guestdetailsubmit">Submit</button></div>
  </form>
</div>

<section id="contactus">
  <div class="social"><i class="fa-brands fa-instagram"></i><i class="fa-brands fa-facebook"></i><i class="fa-solid fa-envelope"></i></div>
  <div class="createdby"><h5>&copy; 2025 Azifa Homestay</h5></div>
</section>

<script>
  var box = document.getElementById("guestdetailpanel");
  function openbox() { box.style.display = "flex"; }
  function closebox() { box.style.display = "none"; }
</script>

</body>
</html>
