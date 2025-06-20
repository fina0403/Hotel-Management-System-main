<?php
include 'config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Azifa Homestay - Login</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSS -->
  <link rel="stylesheet" href="./css/login.css">
  <link rel="stylesheet" href="./css/flash.css">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- AOS -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <!-- Pace loading -->
  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
</head>

<body>

  <!-- Carousel background -->
  <section id="carouselExampleControls" class="carousel slide carousel_section" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active"><img class="carousel-image" src="./image/hotel1.jpg"></div>
      <div class="carousel-item"><img class="carousel-image" src="./image/hotel2.jpg"></div>
      <div class="carousel-item"><img class="carousel-image" src="./image/hotel3.jpg"></div>
      <div class="carousel-item"><img class="carousel-image" src="./image/hotel4.jpg"></div>
    </div>
  </section>

  <!-- Auth Section -->
  <section id="auth_section">
    <div class="logo">
      <img class="azifa-logo" src="./image/bluebirdlogo.png" alt="logo">
      <p>AZIFA HOMESTAY</p>
    </div>

    <div class="auth_container">
      <!-- Log In -->
      <div id="Log_in">
        <h2>Log In</h2>
        <div class="role_btn">
          <div class="btns active">User</div>
          <div class="btns">Admin</div>
        </div>

        <!-- ✅ User Login -->
        <?php 
        if (isset($_POST['user_login_submit'])) {
          $Email = $_POST['Email'];
          $Password = $_POST['Password'];

          $sql = "SELECT * FROM signup WHERE Email = '$Email' AND Password = BINARY '$Password'";
          $result = mysqli_query($conn, $sql);

          if ($result->num_rows > 0) {
            $_SESSION['usermail'] = $Email;
            $_SESSION['role'] = 'customer';
            header("Location: home.php");
          } else {
            echo "<script>swal({ title: 'Invalid credentials', icon: 'error' });</script>";
          }
        }

        if (isset($_GET['signup']) && $_GET['signup'] == "success") {
          echo "<script>swal({ title: 'Account created successfully! Please log in.', icon: 'success' });</script>";
        }
        ?>

        <form class="user_login authsection active" method="POST">
          <div class="form-floating">
            <input type="text" class="form-control" name="Username" placeholder=" ">
            <label>Username</label>
          </div>
          <div class="form-floating">
            <input type="email" class="form-control" name="Email" placeholder=" " required>
            <label>Email</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" name="Password" placeholder=" " required>
            <label>Password</label>
          </div>
          <button type="submit" name="user_login_submit" class="auth_btn">Log in</button>
          <div class="footer_line">
            <h6>Don't have an account? <span class="page_move_btn" onclick="signuppage()">Sign up</span></h6>
          </div>
        </form>

        <!-- ✅ Admin Login -->
        <?php              
        if (isset($_POST['Emp_login_submit'])) {
          $Email = $_POST['Emp_Email'];
          $Password = $_POST['Emp_Password'];

          $sql = "SELECT * FROM emp_login WHERE Emp_Email = '$Email' AND Emp_Password = BINARY '$Password'";
          $result = mysqli_query($conn, $sql);

          if ($result->num_rows > 0) {
            $_SESSION['usermail'] = $Email;
            $_SESSION['role'] = 'admin';
            header("Location: admin/admin.php");
          } else {
            echo "<script>swal({ title: 'Invalid credentials', icon: 'error' });</script>";
          }
        }
        ?> 

        <form class="employee_login authsection" method="POST">
          <div class="form-floating">
            <input type="email" class="form-control" name="Emp_Email" placeholder=" " required>
            <label>Email</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" name="Emp_Password" placeholder=" " required>
            <label>Password</label>
          </div>
          <button type="submit" name="Emp_login_submit" class="auth_btn">Log in</button>
        </form>
      </div>

      <!-- ✅ Sign Up -->
      <?php       
      if (isset($_POST['user_signup_submit'])) {
        $Username = $_POST['Username'];
        $Email = $_POST['Email'];
        $Password = $_POST['Password'];
        $CPassword = $_POST['CPassword'];

        $passPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

        if ($Username == "" || $Email == "" || $Password == "") {
          echo "<script>swal({ title: 'Fill all details', icon: 'error' });</script>";
        } else if (!preg_match($passPattern, $Password)) {
          echo "<script>swal({ title: 'Password must be at least 8 chars with uppercase, lowercase, number & special char.', icon: 'error' });</script>";
        } else {
          if ($Password == $CPassword) {
            $sql = "SELECT * FROM signup WHERE Email = '$Email'";
            $result = mysqli_query($conn, $sql);

            if ($result->num_rows > 0) {
              echo "<script>swal({ title: 'Email already exists', icon: 'error' });</script>";
            } else {
              $sql = "INSERT INTO signup (Username, Email, Password) VALUES ('$Username', '$Email', '$Password')";
              $result = mysqli_query($conn, $sql);

              if ($result) {
                header("Location: index.php?signup=success");
                exit();
              } else {
                echo "<script>swal({ title: 'Something went wrong', icon: 'error' });</script>";
              }
            }
          } else {
            echo "<script>swal({ title: 'Passwords do not match', icon: 'error' });</script>";
          }
        }
      }
      ?>

      <div id="sign_up">
        <h2>Sign Up</h2>
        <form class="user_signup" method="POST" onsubmit="return checkPassword();">
          <div class="form-floating">
            <input type="text" class="form-control" name="Username" placeholder=" " required>
            <label>Username</label>
          </div>
          <div class="form-floating">
            <input type="email" class="form-control" name="Email" placeholder=" " required>
            <label>Email</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" name="Password" id="pass1" placeholder=" " required>
            <label>Password</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" name="CPassword" id="pass2" placeholder=" " required
              oncopy="return false;" onpaste="return false;" oncut="return false;" ondrag="return false;" ondrop="return false;">
            <label>Confirm Password</label>
          </div>
          <!-- 🗒️ Small note to explain -->
          <p style="font-size: 12px; color: #555; margin-top: 4px; margin-bottom: 12px;">
            Note: Please retype your password manually. Copy-paste is disabled for security.
          </p>
          <button type="submit" name="user_signup_submit" class="auth_btn">Sign up</button>
          <div class="footer_line">
            <h6>Already have an account? <span class="page_move_btn" onclick="loginpage()">Log in</span></h6>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- JS -->
  <script src="./javascript/index.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AOS -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>AOS.init();</script>
  <!-- ✅ Client-side password security -->
  <script>
    function checkPassword() {
      const pass1 = document.getElementById('pass1').value;
      const pass2 = document.getElementById('pass2').value;
      const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      if (!pattern.test(pass1)) {
        swal("Password must have uppercase, lowercase, number, special character and at least 8 characters.");
        return false;
      }
      if (pass1 !== pass2) {
        swal("Passwords do not match!");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
