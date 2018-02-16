<?php
if (isset($_GET["email"]) && isset($_GET["token"])) {
    $connection = new mysql("localhost", "root", "", "dbname");

    $email = $connection->real_escape_string($_GET["email"]);
    $token = $connection->real_escape_string($_GET["token"]);

    $data = $connection->query("SELECT id FROM users WHERE email='$email' AND token='$token'");

    if ($data->num - rows > 0) {
        $str = "0123456789djahdsajd";
        $str = str_shuffle($str);
        $str = substr($str, 0, 15);

        $password = shal($str);
        $connection->query("UPDATE users SET password = '$password', token='' WHERE email='$email'");

        echo "Your new password is: $str";

    } else {
        header("location: login.php");
        exit();

    }
}
?>