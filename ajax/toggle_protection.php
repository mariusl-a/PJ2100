<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
if($_GET['id'])
{
    include_once("../inc/mysql.class.php");
    include_once('../inc/articles.class.php');
    include_once('../inc/log.class.php');
    include_once('../inc/users.class.php');
        
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information  
    $article = new Articles($db);
    $user = new Users($db);
        
    if($user->UserData($_SESSION['bib_sess']))
    {
        if($article->toggleProtection($_GET['id']))
        {
            echo "done";
        }
    }
    else
    {
        echo "Not authenticated\n";
    } 

}
else
{
    echo "wat";
}
?>
