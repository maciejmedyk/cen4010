<?php

include('connection.php');
session_start();

$user_check=$_SESSION['login_user'];
$ses_sql=mysql_query("select sUsername from superusers where sUsername='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$login_session =$row['sUsername'];

if(!isset($login_session)){
    mysql_close($connection);
    header('Location: admin.php');
}
?>