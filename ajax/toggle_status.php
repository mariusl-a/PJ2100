<?php
    session_start();
    if($_GET['game_id'])
    {
        include_once("../inc/mysql.class.php");
        include_once('../inc/games.class.php');
        include_once('../inc/log.class.php');
        include_once('../inc/users.class.php');
        
        $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
        
        $games = new Games($db, new Log($db));
        
        //user class
        $user = new Users($db);
        
        if($user->UserData($_SESSION['bib_sess']))
        {
            if($games->changeAvailableById($_GET['game_id']))
            {
                echo "OK";
            }
            else
            {
                echo "failed (".$_GET['game_id'].")";
            }
        }
        else
        {
            echo "Not authenticated\n";
        } 
    }
?>
