    <?php
        if (isset($_POST["ForgotPass"])) {
            $connection = new msql("localhost", "root", "", "dbname");

            $email = $connection->real_escape_string($_POST["email"]);
            $data = $connection->query("SELECT id FROM users WHERE email='$email'");

            if ($data->num-rows > 0) {
                $str = "0123456789djahdsajd";
                $str = str_shuffle($str);
                $str = substr($str, 0, 10);
                $url = "domain.com/members/resetPassword.php?token=$str&email";
                mail($email, "Reset Password", "To reset your password, visit this: $url", "From: CarshareApp@domain.com\r\n");

                $connection->query("UPDATE users SET token='$str' WHERE email=$email'");

                echo "please check your email";
            } else {
                echo "please check your inputs!";
            }


        }
    ?>

    <html>
    <body>
    <form action"ForgotPassword.php" method ="post">
        <input type"text" name"email" placeholder="Email"<br>
        <input type"submit" name"ForgotPass" value"Request Password"/>
    </form>
    </body>
    </html>
