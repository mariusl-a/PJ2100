<?php
if($_GET['title'])
{
    include_once("../inc/mysql.class.php");
    include_once('../inc/games.class.php');
    include_once('../inc/log.class.php');
    
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
    
    $games = new Games($db, new Log($db));
    
    $query = $db->query("SELECT * FROM bib_game_types WHERE title = '".$db->real_escape_string($_GET['title'])."'");
    if($query->num_rows == 1)
    {
        $data = $query->fetch_assoc();
echo '
{
  "title": "'.$data['title'].'",
  "description": "'.nl2br($data['description']).'",
  "category": "'.$data['category'].'",
  "tags": "'.$data['tags'].'"
}';
    }
    else
    {
        $query = $db->query("SELECT title,id FROM bib_game_types WHERE title LIKE '%".$db->real_escape_string($_GET['title'])."%'");
        while($row = $query->fetch_assoc())
        {
            $gamesQuery = $db->query("SELECT shortname FROM bib_games,bib_console_types WHERE bib_games.console_type = bib_console_types.id AND `game_type` = ".$row['id']." GROUP BY name");
            if($gamesQuery->num_rows > 0)
            {
                echo $row['title']." - ";
                while($console = $gamesQuery->fetch_assoc())
                {
                    echo $console['shortname']." ";
                }
                echo "<br/>";
            }
            else
            {
                echo $row['title']."<br/>";
            }
        }
    }    
}
/*/
{
"games": [
{ "title":"John" , "description":"Doe" , "category":"Doe" , "tags":"Doe" },
{ "title":"John" , "description":"Doe" , "category":"Doe" , "tags":"Doe" },
{ "title":"John" , "description":"Doe" , "category":"Doe" , "tags":"Doe" }
]
}
/*/
?>
