<?php

    session_start();
    include_once('navbar.php'); 
    require_once('connection.php')

?>

<link rel="stylesheet" href="assets/css/register.css">

<form action="" method="post">

    <h1>Make your account</h1>
    <input type="text" name="register_name" placeholder="Enter name" required><br>
    <input type="email" name="register_email" placeholder="Enter email" required><br>
    <input type="password" name="register_password" placeholder="Enter password (Min. 8)" required><br>
    <input type="submit" name="register_button" value="Register"><br>
    <p>U already have an account? Go <a href="login.php">here</a></p>

</form>

<?php

if (isset($_POST['register_button'])) {
    $name = $_POST["register_name"];
    $email = $_POST['register_email'];
    $password = $_POST['register_password'];

    if (strlen($password) < 8) {
        die("Lozinka mora imati najmanje 8 karaktera, molimo pokušajte ponovo.");
    }

    $user_exists_sql = "SELECT user_email FROM userss WHERE user_email = ?";
    $user_exists_sql_prep = $conn->prepare($user_exists_sql);
    $user_exists_sql_prep->bind_param('s', $email);

    $user_exists_sql_prep->execute();
    $user_exists_sql_result = $user_exists_sql_prep->get_result();

    if ($user_exists_sql_result->num_rows > 0) {
        die("Korisnik sa tim e-mailom već postoji. Molimo pokušajte sa drugim e-mailom.");
    }

    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    $register_users_sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
    $register_users_sql_prep = $conn->prepare($register_users_sql);
    $register_users_sql_prep->bind_param('sss', $name, $email, $password_hashed);

    $register_users_sql_prep->execute();

    if ($register_users_sql_prep->affected_rows > 0) {
        // Dobij ID korisnika nakon što se uspešno registruje
        $idKorisnika = $conn->insert_id;

        // Postavi ID korisnika u sesiju
        $_SESSION['user_id'] = $idKorisnika;

        // Preusmeri korisnika na stranicu za registraciju
        header('Location: create_list.php');
    } else {
        echo 'Nešto je pošlo po zlu, pokušajte kasnije.';
    }
}


?>

