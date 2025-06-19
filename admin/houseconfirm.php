<?php
include('../config.php');

if (isset($_GET['confirm'])) {
    $id = $_GET['confirm'];
    $sql = "UPDATE housebook SET Status='Confirmed' WHERE id = $id";
    mysqli_query($con, $sql);
    header('Location: housebook.php');
} else {
    header('Location: housebook.php');
}
?>
