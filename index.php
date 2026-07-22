<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up & Database</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background-color: #080811; color: #fff; display: flex; flex-direction: column; align-items: center; min-height: 100vh; padding: 40px 20px; }

        .container {
            background-color: #0f0f1a;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.5), 0 10px 10px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
            width: 850px;
            max-width: 100%;
            min-height: 520px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        form {
            background-color: #0f0f1a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 35px;
            height: 100%;
            text-align: center;
        }

        h1 { font-size: 28px; background: linear-gradient(90deg, #f472b6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 5px; }
        p.subtitle { font-size: 9px; color: #71717a; letter-spacing: 1.2px; margin-bottom: 15px; text-transform: uppercase; font-weight: bold; }

        input {
            background-color: #ffffff;
            border: none;
            padding: 12px 16px;
            margin: 6px 0;
            width: 100%;
            border-radius: 10px;
            outline: none;
            color: #333;
            font-size: 13px;
        }

        .btn-primary {
            border-radius: 10px;
            border: none;
            background: linear-gradient(90deg, #a855f7, #3b82f6);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: bold;
            padding: 12px;
            width: 100%;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(168, 85, 247, 0.4);
        }

        .btn-outline {
            border-radius: 20px;
            border: 2px solid #FFFFFF;
            background-color: transparent;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 10px 35px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: linear-gradient(135deg, #7e22ce, #2563eb);
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }

        .overlay-panel h1 { -webkit-text-fill-color: initial; color: #fff; font-size: 30px; margin-bottom: 15px; }
        .overlay-panel p { font-size: 12px; font-weight: 300; line-height: 18px; margin-bottom: 25px; opacity: 0.9; }

        /* الجدول */
        .table-section {
            width: 850px;
            max-width: 100%;
            margin-top: 30px;
            background: #0f0f1a;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .table-section h2 { margin-bottom: 15px; font-size: 18px; color: #a1a1aa; text-align: center; }

        table { width: 100%; border-collapse: collapse; text-align: center; }
        th, td { padding: 10px; border-bottom: 1px solid #1f1f33; font-size: 13px; }
        th { color: #a1a1aa; text-transform: uppercase; font-size: 11px; }

        .status-active { color: #22c55e; font-weight: bold; }
        .status-inactive { color: #ef4444; font-weight: bold; }

        .toggle-btn {
            background: #3b82f6;
            border: none;
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .toggle-btn:hover { background: #8b5cf6; }
    </style>
</head>
<body>

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form id="signUpForm">
            <h1>Create Account</h1>
            <p class="subtitle">OR USE YOUR EMAIL FOR REGISTRATION</p>
            <input type="text" id="regName" placeholder="Full Name" required />
            <input type="number" id="regAge" placeholder="Age" required />
            <input type="email" id="regEmail" placeholder="Email" required />
            <input type="password" id="regPassword" placeholder="Password" required />
            <button type="submit" class="btn-primary">SIGN UP</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form action="#">
            <h1>Sign In</h1>
            <p class="subtitle">OR USE YOUR ACCOUNT</p>
            <input type="text" placeholder="Full Name" />
            <input type="number" placeholder="Age" />
            <button type="button" class="btn-primary">SIGN IN</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="btn-outline" id="signInBtn">SIGN IN</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start your journey with us</p>
                <button class="btn-outline" id="signUpBtn">SIGN UP</button>
            </div>
        </div>
    </div>
</div>

<div class="table-section">
    <h2>جدول قاعدة البيانات (Database Records)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>Password</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="dataTable">
            </tbody>
    </table>
</div>

<script>
    const signUpButton = document.getElementById('signUpBtn');
    const signInButton = document.getElementById('signInBtn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => container.classList.add("right-panel-active"));
    signInButton.addEventListener('click', () => container.classList.remove("right-panel-active"));

    // جلب البيانات من السيرفر
    function loadData() {
        fetch('process.php?action=fetch')
            .then(res => res.text())
            .then(data => {
                document.getElementById('dataTable').innerHTML = data;
            });
    }

    // إرسال الحقول الكاملة (الاسم، العمر، الإيميل، الباسورد) لمركبات قاعدة البيانات
    document.getElementById('signUpForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('name', document.getElementById('regName').value);
        formData.append('age', document.getElementById('regAge').value);
        formData.append('email', document.getElementById('regEmail').value);
        formData.append('password', document.getElementById('regPassword').value);

        fetch('process.php?action=add', {
            method: 'POST',
            body: formData
        }).then(() => {
            document.getElementById('regName').value = '';
            document.getElementById('regAge').value = '';
            document.getElementById('regEmail').value = '';
            document.getElementById('regPassword').value = '';
            loadData(); // تحديث الجدول فوراً
        });
    });

    // دالة زر Toggle لتحديث حالة الـ Status بضغطة زر
    function toggleStatus(id) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('process.php?action=toggle', {
            method: 'POST',
            body: formData
        }).then(() => {
            loadData();
        });
    }

    loadData();
</script>

</body>
</html>