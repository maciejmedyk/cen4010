<?php

include('connection.php');
session_start();

$user_check=$_SESSION['login_user'];
$ses_sql=mysql_query("select * from drivers where dUsername='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$login_session =$row['dUsername'];
$login_name = $row['dFirstName'] . " ". $row['dLastName'];
if(!isset($login_session)){

    mysql_close($connection);
    header('Location: index.php');
}
?>