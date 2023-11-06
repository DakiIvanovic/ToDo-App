<?php
    session_start();
    include_once('navbar.php');
    require_once('connection.php');

?>

<link rel="stylesheet" href="assets/css/login.css">

<form action="" method="post">
    <h1>Log-in into your account</h1>
    <!-- <label>Email</label><br> -->
    <input type="text" name="login_email" placeholder="Email"><br>

    <!-- <label>Password</label><br> -->
    <input type="password" name="login_password" placeholder="Password"><br>
    <input type="submit" name="login" value="Login">
    <p>You dont have an account? Go <a href="register.php">here</a></p>
</form>

<?php

if (isset($_POST['login'])) {
    require_once('connection.php');

    $login_email = $_POST['login_email'];
    $login_password = $_POST['login_password'];

    // Prvo dobij korisnika na osnovu email adrese
    $login_sql = "SELECT user_id, user_password FROM users WHERE user_email = ?";
    $login_result = $conn->prepare($login_sql);
    $login_result->bind_param('s', $login_email);

    $login_result->execute();
    $login_result->store_result();

    if ($login_result->num_rows > 0) {
        $login_result->bind_result($user_id, $db_password);
        $login_result->fetch();

        if (password_verify($login_password, $db_password)) {
            // Postavi ID korisnika u sesiju nakon uspešnog logovanja
            $_SESSION['user_id'] = $user_id;

            // Preusmeri korisnika na željenu stranicu nakon prijave
            header("Location: create_list.php");
        } else {
            echo '<br>Lozinka nije tacna<br>';
        }
    } else {
        echo '<br>Korisnik ne postoji.';
    }
}

?>
