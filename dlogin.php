<?php
session_start();
$error='';

if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    }
    else
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        $active = 1;

        include('connection.php');

        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysql_real_escape_string($username);
        $password = mysql_real_escape_string($password);


        $query = mysql_query("select * from drivers where dPassword='$password' AND dUsername='$username' AND dActive=1", $connection);
        $rows = mysql_num_rows($query);
        if ($rows == 1)
        {
            $_SESSION['login_user']=$username;
            header("Location: dhome.php");
        } else
        {
            $error = "Username or Password is invalid";
        }
        mysql_close($connection);
    }
}
?>