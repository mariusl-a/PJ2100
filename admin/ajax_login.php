<?php
//"debug mode", show all errors..
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(isset($_POST['login_username']) && isset($_POST['login_password']))
{
    //includes
    include_once('../inc/mysql.class.php');
    include_once('../inc/users.class.php');
    include_once('../inc/log.class.php');
    //database class
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
    //log
    $log = new Log($db);
    //user class
    $user = new Users($db);
    
    $username = htmlspecialchars($_POST['login_username']);
    $passwod = htmlspecialchars($_POST['login_password']);
    
    if($user->LoginUser($username, $passwod))
    {
        echo "true";
    }
    else
    {
        echo "false";
    }
}
else
{
    echo "false";
}
?>