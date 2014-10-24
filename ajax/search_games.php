<?php
if($_GET['title'])
{
    include_once("../inc/mysql.class.php");
    include_once('../inc/games.class.php');
    
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
    
    if($_GET['title'] == "alle")
    {
        $SQL = "SELECT bib_games.id,title,available,bib_console_types.shortname,bib_console_types.name FROM bib_game_types,bib_games,bib_console_types WHERE  bib_game_types.id = bib_games.game_type AND bib_console_types.id = bib_games.console_type";
    }
    elseif(is_numeric($_GET['title']))
    {
        //SELECT bib_games.id as ID, title as Tittel, console_type as Konsoll FROM `bib_games`,`bib_game_types` WHERE bib_game_types.id = bib_games.game_type AND console_type = 1
        $SQL = "SELECT bib_games.id,title,available,bib_console_types.shortname,bib_console_types.name FROM bib_game_types,bib_games,bib_console_types WHERE  bib_game_types.id = bib_games.game_type AND bib_console_types.id = bib_games.console_type AND console_type = ".$db->real_escape_string($_GET['title']);
    }
    else
    {
        $SQL = "SELECT bib_games.id,title,available,bib_console_types.shortname,bib_console_types.name FROM bib_game_types,bib_games,bib_console_types WHERE  bib_game_types.id = bib_games.game_type AND bib_console_types.id = bib_games.console_type AND title LIKE '%".$db->real_escape_string($_GET['title'])."%'";
        
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
                echo '{ "title":"'.$data['title'].'", "game_id":"'.$data['id'].'", "console":"'.$data['shortname'].'" }';
                $first = false;
            }
            else
            {
                echo ',{ "title":"'.$data['title'].'", "game_id":"'.$data['id'].'", "console":"'.$data['shortname'].'" }';
            }
        }
        ?>
]
        <?php
    }   
}
?>
