<?php
include 'db.php';

$action = $_REQUEST['action'] ?? '';

// 1. إضافة البيانات الكاملة
if ($action === 'add') {
    $name     = $_POST['name'] ?? '';
    $age      = $_POST['age'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($name) && !empty($age) && !empty($email) && !empty($password)) {
        // تشفير كلمة المرور للحفاظ على الأمان
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (name, age, email, password, status) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("siss", $name, $age, $email, $hashed_password);
        $stmt->execute();
    }
}

// 2. تبديل الحالة (Toggle Status)
if ($action === 'toggle') {
    $id = $_POST['id'] ?? 0;
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE users SET status = 1 - status WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

// 3. عرض البيانات في الجدول (تلقائياً وبدون تحديث صفحة)
if ($action === 'fetch' || $action === 'add' || $action === 'toggle') {
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>******</td>"; // إخفاء الباسورد للعرض الآمن
        echo "<td>" . ($row['status'] == 1 ? '<span class="status-active">1 (نشط)</span>' : '<span class="status-inactive">0 (غير نشط)</span>') . "</td>";
        echo "<td><button class='toggle-btn' onclick='toggleStatus(" . $row['id'] . ")'>Toggle</button></td>";
        echo "</tr>";
    }
}
?>