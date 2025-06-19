<?php
session_start();
include('../config.php');

// ✅ 1) Role-based access control (only admin/owner)
if (!isset($_SESSION['usermail']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// ✅ 2) Securely Add House
if (isset($_POST['add'])) {
    // Sanitize inputs
    $houseName = htmlspecialchars(trim($_POST['housename']));
    $houseType = htmlspecialchars(trim($_POST['housetype']));
    $bedding   = htmlspecialchars(trim($_POST['bedding']));
    $place     = htmlspecialchars(trim($_POST['place']));

    if ($houseName && $houseType && $bedding && $place) {
        $stmt = $conn->prepare("INSERT INTO house (HouseName, HouseType, Bedding, Place) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $houseName, $houseType, $bedding, $place);

        if ($stmt->execute()) {
            echo "<script>alert('House added successfully');</script>";
        } else {
            echo "<script>alert('Failed to add house');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('All fields are required');</script>";
    }
}

// ✅ 3) Securely Delete House
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Force numeric
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM house WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: house.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Houses | Azifa Homestay</title>
    <link rel="stylesheet" href="css/house.css">
</head>
<body>
    <h1>Manage Houses</h1>

    <!-- ✅ Add House Form -->
    <form method="POST" action="">
        <input type="text" name="housename" placeholder="House Name (e.g. DAHLIA)" required>
        <input type="text" name="housetype" placeholder="House Type (e.g. Family)" required>
        <input type="text" name="bedding" placeholder="Bedding (e.g. Queen Bed)" required>
        <input type="text" name="place" placeholder="Place (e.g. Garden View)" required>
        <button type="submit" name="add">Add House</button>
    </form>

    <!-- ✅ House Table -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>House Name</th>
            <th>House Type</th>
            <th>Bedding</th>
            <th>Place</th>
            <th>Action</th>
        </tr>

        <?php
        $sql = "SELECT * FROM house ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['HouseName']) . "</td>
                <td>" . htmlspecialchars($row['HouseType']) . "</td>
                <td>" . htmlspecialchars($row['Bedding']) . "</td>
                <td>" . htmlspecialchars($row['Place']) . "</td>
                <td><a href='house.php?delete=" . urlencode($row['id']) . "' onclick='return confirm(\"Delete this house?\");'>Delete</a></td>
            </tr>";
        }
        ?>
    </table>

</body>
</html>
