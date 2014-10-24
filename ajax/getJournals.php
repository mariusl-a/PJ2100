<?php
if($_GET['title'])
{
    include_once("../inc/mysql.class.php");
    
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
    
    if(is_numeric($_GET['title']))
    {
        //SELECT bib_games.id as ID, title as Tittel, console_type as Konsoll FROM `bib_games`,`bib_game_types` WHERE bib_game_types.id = bib_games.game_type AND console_type = 1
        $SQL = "SELECT * FROM bib_journal_types WHERE id = ".$db->real_escape_string($_GET['title']." LIMIT 1"); 
        $query = $db->query($SQL);
        if($query->num_rows == 1)
        {
            while($data = $query->fetch_assoc())
            {
                echo '{ "title":"'.$data['title'].'", "description":"'.utf8_encode($data['description']).'" }';
            }
        } 
    }  
}
?>
