<?php
include('../config.php');

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $sql = "UPDATE housebook SET Status='$status' WHERE id = $id";
    mysqli_query($con, $sql);
    header('Location: housebook.php');
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM housebook WHERE id = $id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
} else {
    header('Location: housebook.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking | Azifa Homestay</title>
</head>
<body>
    <h1>Edit Booking Status</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label>Status:</label>
        <select name="status">
            <option value="Pending" <?php if($row['Status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Approved" <?php if($row['Status']=='Approved') echo 'selected'; ?>>Approved</option>
            <option value="Confirmed" <?php if($row['Status']=='Confirmed') echo 'selected'; ?>>Confirmed</option>
        </select>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
