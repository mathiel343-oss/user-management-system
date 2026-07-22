<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "sql109.infinityfree.com";
$username   = "if0_42403377";
$password   = "Mathayel1"; 
$dbname     = "if0_42403377_myfrist";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_POST['action']) && $_POST['action'] === 'toggle') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("UPDATE users SET status = 1 - status WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $conn->close();
    exit; 
}

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Users List</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; padding: 30px; }
        h2 { margin-bottom: 20px; color: #1e293b; }
        table { border-collapse: collapse; width: 90%; margin: auto; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: center; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #2563eb; color: white; }
        .btn-toggle { background: #3b82f6; color: white; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        .btn-toggle:hover { background: #1d4ed8; }
        .status-1 { color: #16a34a; font-weight: bold; }
        .status-0 { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

<h2 style="text-align: center;">Registered Users List</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Email</th>
            <th>Password</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr id="row-<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>******</td>
                    <td id="status-<?php echo $row['id']; ?>" class="status-<?php echo $row['status']; ?>">
                        <?php echo $row['status']; ?>
                    </td>
                    <td>
                        <button class="btn-toggle" onclick="toggleStatus(<?php echo $row['id']; ?>)">Toggle</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No records found</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script> 
function toggleStatus(userId) {
    const formData = new FormData();
    formData.append('action', 'toggle');
    formData.append('id', userId);

    fetch('select.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            const statusCell = document.getElementById('status-' + userId);
            let currentStatus = parseInt(statusCell.innerText.trim());
            let newStatus = currentStatus === 1 ? 0 : 1;
         
            
            statusCell.innerText = newStatus;
            statusCell.className = 'status-' + newStatus;
        } else {
            alert('Error updating status!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

</body>
</html>