<?php
// إظهار الأخطاء لتشخيص أي مشكلة إن وجدت
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. بيانات الاتصال بقاعدة البيانات
$servername = "sql109.infinityfree.com";
$username   = "if0_42403377";
$password   ="Mathayel1"
$dbname     = "if0_42403377_myfrist";

// 2. إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// 3. التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// ضبط ترميز اللغة العربية
$conn->set_charset("utf8");

// 4. استعلام الاستعلام السليكت (SELECT)
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض البيانات</title>
    <style>
        body { font-family: Arial, sans-serif; background: #080811; color: #fff; padding: 30px; text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: #0f0f1a; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px; border: 1px solid #1f1f33; text-align: center; }
        th { background-color: #2563eb; color: white; }
        .active { color: #22c55e; font-weight: bold; }
        .inactive { color: #ef4444; font-weight: bold; }
    </style>
</head>
<body>

<h2>جدول عرض البيانات من قاعدة البيانات (users)</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>الاسم الكامل</th>
            <th>العمر</th>
            <th>البريد الإلكتروني</th>
            <th>كلمة المرور</th>
            <th>الحالة (Status)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["age"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>******</td>";
                echo "<td>" . ($row["status"] == 1 ? '<span class="active">1 (نشط)</span>' : '<span class="inactive">0 (غير نشط)</span>') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>لا توجد بيانات مسجلة في الجدول حالياً.</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>