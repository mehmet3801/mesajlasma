<?php
include 'db_connect.php'; // Veritabanı bağlantısını dahil et

session_start();

if (isset($_SESSION['message'])) {
  echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
  unset($_SESSION['message']);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kullanıcıyı veritabanında ara
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Şifre doğrulama
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            header("Location: chat.php"); 
            // Başarılı girişte sohbet sayfasına yönlendir
            exit();
        } else {
            $error = "Hatalı şifre!";
        }
        
    } else {
        $error = "Kullanıcı bulunamadı!";
    }
    

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Registration Form</title>
  <link rel="stylesheet" href="dene.css">
</head>
<body>
  <div class="container">
    <div class="orangeBG">
      <div class="box signin">
        <h2>Zaten bir hesabınız var mı?</h2>
        <button class="signinbtn" onclick="toggleForms()">Giriş Yap</button>
      </div>
      <div class="box signup">
        <h2>Hemen Kayıt Ol!</h2>
        <button class="signupbtn" onclick="toggleForms()">Kayıt Ol</button>
      </div>
    </div>

    <div class="form-box">
      <!-- Giriş Formu -->
      <div class="form signinform">
        <header>Login</header>
        <form action="login.php" method="POST">
          <input type="text" name="username" placeholder="Kullanıcı Adı" required>
          <input type="password" name="password" placeholder="Şifre" required>
          <a href="#">Şifrenizi mi unuttunuz?</a>
          <input type="submit" class="button" name="login" value="Giriş Yap">
        </form>
        <div class="signup">
          <span class="signup">Hesabınız yok mu?
            <button type="button" onclick="toggleForms()">Kayıt Ol</button>
          </span>
        </div>
      </div> 

      <!-- Kayıt Formu -->
      <div class="form signupform">
        <header>Signup</header>
        <form action="register.php" method="POST">
          <input type="text" name="username" placeholder="Kullanıcı Adı" required>
          <input type="password" name="password" placeholder="Şifre" required>
          <input type="password" name="confirm_password" placeholder="Şifre Tekrar" required>
          <input type="submit" class="button" name="register" value="Kayıt Ol">
        </form>
        <?php if(isset($error)) echo "<p>$error</p>"; ?>
        <div class="signup">
          <span class="signup">Zaten bir hesabınız var mı?
            <button type="button" onclick="toggleForms()">Giriş Yap</button>
          </span>
        </div>
      </div>
    </div>
  </div>

  <script>
       const signinbtn = document.querySelector('.signinbtn');
const signupbtn = document.querySelector('.signupbtn');
const formbox = document.querySelector('.form-box');
const body = document.querySelector('body');

signupbtn.onclick = function() {
    formbox.classList.add('active');
    body.classList.add('active');
};

signinbtn.onclick = function() {
    formbox.classList.remove('active');
    body.classList.remove('active');
};
  </script>
</body>
</html>
