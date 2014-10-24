<?php
header('Content-type: text/html; charset=utf-8');
if($_GET['id'])
{
    include_once("../inc/mysql.class.php");
    include_once('../inc/games.class.php');
    include_once('../inc/log.class.php');
    
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
    
    $games = new Games($db, new Log($db));
    
    if($data = $games->getGameData($_GET['id']))
    {
        echo '{';
        echo '  "title": "'.$data['title'].'",';
        echo '  "description": "'.$data['description'].'",';
        echo '  "category": "'.$data['category'].'",';
        echo '  "console": "'.$data['console_type'].'",';
        echo '  "image": "'.$data['image'].'",';
        echo '  "type": "'.$data['game_type'].'",';
        echo '  "tags": "'.$data['tags'].'"';
        echo '}';
    }
    else
    {
        echo "Ingen funnet.";
    }    
}
?>
