<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'bankalogin';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Database connection error: ' . $conn->connect_error);
}
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
    // Only logged-in users can access this page
}
$today = date('Y-m-d');
// Prepared statements were used to prevent SQL injection
$stmt = $conn->prepare('SELECT sender, receiver, amount, date, id FROM transfers WHERE amount >= ? AND date = ?');
$min_amount = 100000;
$stmt->bind_param('is', $min_amount, $today);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Büyük Bütçeli Transferler</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6dd5fa, #ffffff);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .transfers-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.17);
            width: 500px;
            text-align: center;
        }
        h1 {
            color: #2980b9;
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #2980b9;
            padding: 10px;
            font-size: 16px;
        }
        th {
            background: #2980b9;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="transfers-container">
        <h1>Transferler</h1>
        <table>
            <tr>
                <th>Gönderen</th>
                <th>Alıcı</th>
                <th>Tutar</th>
                <th>Tarih</th>
                <th>Detay</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    // htmlspecialchars is used to prevent XSS
                    echo '<td>' . htmlspecialchars($row['sender']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['receiver']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['amount']) . ' ₺</td>';
                    echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                    echo '<td><a href="transfer_detail.php?id=' . urlencode($row['id']) . '" style="color:#fff; background:#2980b9; padding:6px 12px; border-radius:6px; text-decoration:none; font-size:14px; display:inline-block;">Detayları Görmek İçin Tıklayınız</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">Bugün için büyük transfer kaydı yok.</td></tr>';
            }
            ?>
        </table>
        <div style="color:#888; font-size:14px;">Bu sayfaya erişen herkes bugünkü büyük transferleri görebilir.</div>
    </div>
</body>
</html> 