<?php
// Hata görüntüleme etkinleştirme
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı Bağlantı Bilgileri
$host = "localhost";      // Sunucu Adı
$username = "root";       // MySQL Kullanıcı Adı
$password = "root";           // MySQL Şifresi (Varsayılan boş)
$database = "chat_app";   // Veritabanı Adı

// Bağlantı kur
$conn = new mysqli($host, $username, $password, $database);

// Bağlantıyı Kontrol Et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
} else {
    echo "";
}
?>
