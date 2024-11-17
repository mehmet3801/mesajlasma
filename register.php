<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Şifreler eşleşmiyor!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kullanıcı adı kontrolü
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Bu kullanıcı adı zaten alınmış!";
        } else {
            // Kullanıcıyı ekleme
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Kayıt başarılı, lütfen giriş yapın!";
                header("Location: login.php");
                exit();
            } else {
                $error = "Kayıt sırasında bir hata oluştu!";
            }
            $stmt->close();
        }
        $stmt_check->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="dene.css">
</head>
<body>
  <div class="container">
    <div class="form signupform">
      <header>Signup</header>
      <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <input type="password" name="confirm_password" placeholder="Şifre Tekrar" required>
        <input type="submit" class="button" name="register" value="Kayıt Ol">
      </form>
      <?php if(isset($error)) echo "<p>$error</p>"; ?>
    </div>
  </div>
</body>
</html>
