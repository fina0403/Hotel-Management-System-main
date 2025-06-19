<?php
include('../config.php');

// Confirm Booking
if (isset($_GET['confirm'])) {
    $id = $_GET['confirm'];
    $sql = "UPDATE housebook SET Status='Approved' WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: housebook.php');
}

// Cancel Booking
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $sql = "UPDATE housebook SET Status='Cancelled' WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: housebook.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>House Bookings | Azifa Homestay</title>
    <link rel="stylesheet" href="css/housebook.css">
</head>
<body>
    <h1>Manage House Bookings</h1>
    <div class="table-container">
        <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>House</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM housebook ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            // Status badge styling
            $statusClass = strtolower($row['Status']);
            echo "<tr>
            <td>".htmlspecialchars($row['id'])."</td>
            <td>".htmlspecialchars($row['Name'])."</td>
            <td>".htmlspecialchars($row['Phone'])."</td>
            <td>".htmlspecialchars($row['Email'])."</td>
            <td>".htmlspecialchars($row['House'])."</td>
            <td>".htmlspecialchars($row['CheckIn'])."</td>
            <td>".htmlspecialchars($row['CheckOut'])."</td>
            <td><span class='badge $statusClass'>".htmlspecialchars($row['Status'])."</span></td>
            <td>";
            // Only allow confirm/cancel if not already approved/cancelled
            if (strtolower($row['Status']) == 'notconfirm') {
                echo "
                <a class='btn btn-confirm' href='housebook.php?confirm=".urlencode($row['id'])."' onclick='return confirm(\"Confirm this booking?\");'>Confirm</a>
                <a class='btn btn-cancel' href='housebook.php?cancel=".urlencode($row['id'])."' onclick='return confirm(\"Cancel this booking?\");'>Cancel</a>
                ";
            } else {
                echo "<span class='muted'>—</span>";
            }
            echo "</td>
            </tr>";
        }
        ?>
        </table>
    </div>
</body>
</html>
