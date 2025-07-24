# Güvenlik Zafiyetleri Raporu

## İçindekiler
- [1. SQL Injection (SQLi)](#1-sql-injection-sqli)
- [2. XSS (Cross-Site Scripting)](#2-xss-cross-site-scripting)
- [3. Zafiyetli Dosya Yükleme](#3-zafiyetli-dosya-yükleme)
- [4. Command Injection](#4-command-injection)

---

## 1. SQL Injection (SQLi)

### Senaryo
Kullanıcı adı ve şifre doğrudan SQL sorgusuna ekleniyor.

### Zafiyetli Kod
```php
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

### Exploit/Test Adımları
1. Postman/curl ile şu istek gönderildi:
   ```
   curl -X POST -d "username=admin' OR '1'='1&password=123" http://localhost/bankalogin/login.php
   ```
2. Giriş başarılı oldu.

![Açık öncesi](rapor/img/sqli_before.png)

### Fix Adımları
- Prepared statement kullanıldı:
```php
$stmt = $conn->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
$stmt->bind_param('ss', $username, $password);
```

### Sonuç
- Aynı payload ile giriş başarısız oldu.

![Açık sonrası](rapor/img/sqli_after.png)

---

## 2. XSS (Cross-Site Scripting)

### Senaryo
Kullanıcıdan alınan veri doğrudan HTML'e yazılıyor.

### Zafiyetli Kod
```php
<div class="welcome">Hoş geldiniz, <?php echo $username; ?>!</div>
```

### Exploit/Test Adımları
1. Kullanıcı adı olarak `<script>alert(1)</script>` girildi.
2. Sayfa açıldığında tarayıcıda uyarı çıktı.

![Açık öncesi](rapor/img/xss_before.png)

### Fix Adımları
- `htmlspecialchars` fonksiyonu ile çıktı filtrelendi:
```php
<div class="welcome">Hoş geldiniz, <?php echo htmlspecialchars($username); ?>!</div>
```

### Sonuç
- Artık script çalışmıyor.

![Açık sonrası](rapor/img/xss_after.png)

---

## 3. Zafiyetli Dosya Yükleme

### Senaryo
Dosya türü ve uzantısı kontrol edilmeden dosya yükleniyor.

### Zafiyetli Kod
```php
if (move_uploaded_file($file['tmp_name'], $target)) {
    // ...
}
```

### Exploit/Test Adımları
1. `.php` uzantılı bir dosya yüklendi.
2. Sunucuda çalıştırıldı.

![Açık öncesi](rapor/img/upload_before.png)

### Fix Adımları
- Dosya uzantısı ve MIME tipi kontrolü eklendi:
```php
$allowed = ['jpg', 'png', 'pdf'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
if (in_array($ext, $allowed)) { ... }
```

### Sonuç
- Artık zararlı dosya yüklenemiyor.

![Açık sonrası](rapor/img/upload_after.png)

---

## 4. Command Injection

### Senaryo
Kullanıcıdan gelen veri shell_exec ile birleşiyor.

### Zafiyetli Kod
```php
$log_command = "echo '$content' > /tmp/$filename";
shell_exec($log_command);
```

### Exploit/Test Adımları
1. Dosya adı olarak `test.txt; ls` girildi.
2. Sunucuda beklenmeyen komutlar çalıştı.

![Açık öncesi](rapor/img/command_before.png)

### Fix Adımları
- Komut parametreleri güvenli hale getirildi:
```php
$filename = escapeshellarg($filename);
$log_command = "echo '$content' > /tmp/$filename";
```

### Sonuç
- Artık komut enjekte edilemiyor.

![Açık sonrası](rapor/img/command_after.png)

---

# Sonuç
Her zafiyet için önce/sonra çıktıları ve düzeltme adımları yukarıda gösterilmiştir. 