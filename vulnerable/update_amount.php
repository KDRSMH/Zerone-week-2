<?php
$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// When the file is uploaded, process it
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $target = $upload_dir . basename($file['name']);
    // vulnerable: no file type or extension check!
    if (move_uploaded_file($file['tmp_name'], $target)) {
        echo "<div style='color:green;'>Dosya başarıyla yüklendi: <a href='uploads/" . htmlspecialchars($file['name']) . "' target='_blank'>" . htmlspecialchars($file['name']) . "</a></div>";
    } else {
        echo "<div style='color:red;'>Yükleme hatası!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Güncel Tutarın tablosunu yükleyiniz</title>
    <style>
        body { background: #f2f6fc; font-family: 'Roboto', sans-serif; }
        .form-container { background: #fff; margin: 40px auto; padding: 32px 40px; border-radius: 16px; box-shadow: 0 8px 32px 0 rgba(31,38,135,0.17); width: 400px; }
        h2 { color: #2980b9; }
        label { display:block; margin-top:16px; margin-bottom:6px; font-weight:bold; }
        input[type=file] { width:100%; margin-bottom:16px; }
        input[type=submit] { background: #2980b9; color: #fff; padding: 10px 22px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; width:100%; }
        input[type=submit]:hover { background: #6dd5fa; color: #222; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Güncel Tutarın tablosunu yükleyiniz</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Dosya Seç:</label>
            <input type="file" name="file" required>
            <input type="submit" value="Yükle">
        </form>
    </div>
</body>
</html> 