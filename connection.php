<?php

    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'todotask';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Database connection proble, please try again in 5 minutes." . $conn->connect_error);
    }

?>