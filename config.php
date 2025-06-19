<?php

$server = "localhost";
$username = "root";  // ✅ new DB user
$password = "";    // ✅ same as you set in the SQL
$database = "azifahomestay"; // ✅ new DB name

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("<script>alert('Connection failed.')</script>");
}
?>
