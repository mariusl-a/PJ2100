<?php
if($_GET['title'])
{
    include_once("../inc/mysql.class.php");    
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
    if($_GET['title'] == "alt" || $_GET['title'] == "alle")
    {
        $SQL = "SELECT id,title,available FROM `bib_boardgames`";
    }
    else
    {
        $SQL = "SELECT id,title,available FROM `bib_boardgames` WHERE `title` LIKE '%".$_GET['title']."%' OR `tags` LIKE '%".$_GET['title']."%'";    
    }
    
    $query = $db->query($SQL);
    if($query->num_rows > 0)
    {
        ?>
[
        <?php
        $first = true;
        while($data = $query->fetch_assoc())
        {
            if($first)
            {
                echo '{ "title":"'.$data['title'].'", "game_id":"'.$data['id'].'", "status":"'.$data['available'].'" }';
                $first = false;
            }
            else
            {
                echo ',{ "title":"'.$data['title'].'", "game_id":"'.$data['id'].'", "status":"'.$data['available'].'" }';
            }
        }
        ?>
]
        <?php
    }   
}
?>
