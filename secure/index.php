<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'bankalogin';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    /// Prepared statements were used to prevent SQL injection.
    $stmt = $conn->prepare('SELECT password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        if ($password === $row['password']) {
            // session_regenerate_id was used against session fixation attacks
            session_regenerate_id(true);
            $_SESSION['user'] = $username;
            header('Location: transfers.php');
            exit;
        } else {
            $error = 'Kullanıcı adı veya şifre yanlış.';
        }
    } else {
        $error = 'Kullanıcı adı veya şifre yanlış.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Banka Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2980b9, #6dd5fa, #ffffff);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            width: 350px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 24px;
            color: #2980b9;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border: 1px solid #2980b9;
            border-radius: 8px;
            font-size: 16px;
        }
        .login-container button {
            background: linear-gradient(90deg, #2980b9, #6dd5fa);
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-container button:hover {
            background: linear-gradient(90deg, #6dd5fa, #2980b9);
        }
        .error {
            color: #e74c3c;
            margin-bottom: 16px;
            font-weight: bold;
        }
        .logo {
            width: 60px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="https://img.icons8.com/ios-filled/100/2980b9/bank-building.png" class="logo" alt="Banka Logo"/>
        <h1>PaparaX</h1>
        <?php if ($error) { echo '<div class="error">' . htmlspecialchars($error) . '</div>'; } // XSS'e karşı htmlspecialchars kullanıldı ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required autofocus><br>
            <input type="password" name="password" placeholder="Şifre" required><br>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>
</body>
</html> 