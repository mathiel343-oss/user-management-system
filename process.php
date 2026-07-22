<?php
include 'db.php';

if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    $sql = "SELECT * FROM users ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $statusText = $row['status'] == 1 ? '<span class="status-active">1 (Active)</span>' : '<span class="status-inactive">0 (Inactive)</span>';
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['email']}</td>
                    <td>******</td>
                    <td>{$statusText}</td>
                    <td><button class='toggle-btn' onclick='toggleStatus({$row['id']})'>Toggle</button></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No records found</td></tr>";
    }
    exit;
}

if ((isset($_GET['action']) && $_GET['action'] == 'add') || isset($_POST['signup'])) {
    $name     = trim($_POST['name']);
    $age      = trim($_POST['age']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "INSERT INTO users (name, age, email, password, status) VALUES ('$name', '$age', '$email', '$password', 0)";

    if ($conn->query($sql) === TRUE) {
        if (isset($_POST['signup'])) {
            header("Location: select.php");
        } else {
            echo "Success";
        }
    } else {
        echo "Error: " . $conn->error;
    }
    exit;
}

if ((isset($_GET['action']) && $_GET['action'] == 'login') || isset($_POST['signin'])) {
    $name     = trim($_POST['name']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE TRIM(name) = '$name' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        if (isset($_POST['signin'])) {
            header("Location: select.php");
        } else {
            echo "Login Successful! Welcome back.";
        }
    } else {
        if (isset($_POST['signin'])) {
            echo "<script>alert('User not found or invalid credentials!'); window.location.href='index.php';</script>";
        } else {
            echo "User not found or invalid credentials!";
        }
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'toggle') {
    $id = $_POST['id'];

    $sql = "UPDATE users SET status = 1 - status WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Status Updated";
    } else {
        echo "Error: " . $conn->error;
    }
    exit;
}
?>