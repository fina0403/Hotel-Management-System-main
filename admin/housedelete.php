<?php
include('../config.php');

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM house WHERE id = $id";
    mysqli_query($con, $sql);
    header('Location: house.php');
} else {
    header('Location: house.php');
}
?>
