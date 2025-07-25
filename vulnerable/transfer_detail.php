<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'bankalogin';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Veritabanı bağlantı hatası: ' . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM transfers WHERE id = $id";
$result = $conn->query($query);
if (!$result || $result->num_rows == 0) {
    die('Transfer bulunamadı.');
}
$row = $result->fetch_assoc();

if (isset($_GET['download']) && $_GET['download'] == '1') {
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename=transfer_'.$id.'.txt');
    echo "Gönderen: {$row['sender_name']}\n";
    echo "Gönderen Kimlik: {$row['sender_id']}\n";
    echo "Alıcı: {$row['receiver_name']}\n";
    echo "Alıcı Kimlik: {$row['receiver_id']}\n";
    echo "Tutar: {$row['amount']} ₺\n";
    echo "Tarih: {$row['date']}\n";
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Transfer Detayı</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body { background: #f2f6fc; font-family: 'Roboto', sans-serif; }
        .detail-container { background: #fff; margin: 40px auto; padding: 32px 40px; border-radius: 16px; box-shadow: 0 8px 32px 0 rgba(31,38,135,0.17); width: 400px; }
        h2 { color: #2980b9; }
        .info { text-align:left; margin-bottom: 18px; }
        .info strong { width: 120px; display: inline-block; }
        .download-btn { background: #2980b9; color: #fff; padding: 10px 22px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; text-decoration:none; }
        .download-btn:hover { background: #6dd5fa; color: #222; }
    </style>
</head>
<body>
    <div class="detail-container">
        <h2>Transfer Detayı</h2>
        <div class="info"><strong>Gönderen:</strong> <?php echo $row['sender_name']; ?></div>
        <div class="info"><strong>Gönderen Kimlik:</strong> <?php echo $row['sender_id']; ?></div>
        <div class="info"><strong>Alıcı:</strong> <?php echo $row['receiver_name']; ?></div>
        <div class="info"><strong>Alıcı Kimlik:</strong> <?php echo $row['receiver_id']; ?></div>
        <div class="info"><strong>Tutar:</strong> <?php echo $row['amount']; ?> ₺</div>
        <div class="info"><strong>Tarih:</strong> <?php echo $row['date']; ?></div>
        <a class="download-btn" href="?id=<?php echo $id; ?>&download=1">TXT olarak indir</a>
        <br><br>
        <a class="download-btn" href="../bankalogin/update_amount.php?transfer_id=<?php echo $id; ?>">Mevcut Tutarı Güncelle</a>
    </div>
</body>
</html> 