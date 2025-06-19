<?php
include('../config.php');

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM housebook WHERE id = $id";
    mysqli_query($con, $sql);
    header('Location: housebook.php');
} else {
    header('Location: housebook.php');
}
?>
