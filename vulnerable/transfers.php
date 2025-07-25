<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'bankalogin';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}

$today = date('Y-m-d');
$query = "SELECT * FROM transfers WHERE amount >= 100000 AND date = '$today'";
$result = $conn->query($query);
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
                    echo '<td>' . $row['sender'] . '</td>';
                    echo '<td>' . $row['receiver'] . '</td>';
                    echo '<td>' . $row['amount'] . ' ₺</td>';
                    echo '<td>' . $row['date'] . '</td>';
                    echo '<td><a href="transfer_detail.php?id=' . $row['id'] . '" style="color:#fff; background:#2980b9; padding:6px 12px; border-radius:6px; text-decoration:none; font-size:14px; display:inline-block;">Detayları Görmek İçin Tıklayınız</a></td>';
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