<?php
session_start();
header('Content-Type: text/html; charset=utf-8'); 

if(!isset($_COOKIE['bib_sess']))
{
    header("Location: login.php");
}
else
{
//includes
include_once('include_files.php');

//database class
$db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
//log
$log = new Log($db);
//games class
$games = new Games($db, $log);
//user class
$user = new Users($db);
//articles
//$articles = new Articles($db);

//if the user session is found in the database, then its authed correctly
if($user->UserData($_COOKIE['bib_sess']))
{
    $_SESSION['bib_sess'] = $_COOKIE['bib_sess'];
    //$user->NewUser("u", "p", "epost@epost.no", "Spel");
?>  

<!DOCTYPE html>
<html>
<head>
    <title>BibAdmin | Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="./css/index.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/forms.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/common.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/games.css" type="text/css" media="screen" />
    <link rel="icon" type="image/png" href="../IT_favicon.ico" />
    <script src="js/jquery-1.7.1.js"></script>
    <script src="js/games.js"></script>
</head>

<body>
<div id="header">
	<div id="headline"></div>
</div>

<div id="container">
	<div id="container_content">
<?php include_once('global/menu.html'); ?>
        
        <div id="divContent">
        <?php
    if(isset($_POST['new_game_title']))
    {
        $filePath = "";
        $valid = true;
        if(isset($_FILES['new_game_image']))
        {
            if($_FILES["new_game_image"]["error"] > 0)
            {
                echo "<div class=\"userMsg error\">Error when uploading: " . $_FILES["new_game_image"]["error"]."</div>";
                $valid = false;
            }
            else
            {
                if(!file_exists("../games/".$_FILES["new_game_image"]["name"]))
                {
                    $fileType = explode(".", $_FILES["new_game_image"]["name"]);
                    $filename = str_replace(" ","_", str_replace("'", "", $_POST['new_game_title'])).".".$fileType[1];
                    move_uploaded_file($_FILES["new_game_image"]["tmp_name"], "../games/images/" . $filename);
                }
                $filePath = $_FILES["new_game_image"]["name"];
            }
        }
        if($valid)
        {
            if($games->addGame($_POST['new_game_title'], $_POST['new_game_desc'], $_POST['new_game_type'], $_POST['new_game_tags'], $filePath, 1, 1))
            {
                echo "<div class=\"userMsg\">".$db->real_escape_string($_POST['new_game_title'])." er lagt til.</div>";
            }
        }
    }
    elseif(isset($_POST['game_update']))
    {
        $filePath = "";
        $valid = true;
        if(isset($_FILES['update_game_image']))
        {
            if($_FILES["update_game_image"]["error"] > 0)
            {
                if($_FILES["update_game_image"]["error"] != 4)
                {
                    echo "<div class=\"userMsg error\">Error when uploading: " . $_FILES["update_game_image"]["error"]."</div>";
                }
                $valid = false;
            }
            else
            {
                if(!file_exists("../games/".$_FILES["update_game_image"]["name"]))
                {
                    $fileType = explode(".", $_FILES["update_game_image"]["name"]);
                    
                    $replace = array(":", " ", "'");
                    $with = array("", "_", "");
                    
                    $filePath = str_replace($replace, $with, $_POST['update_game_title']).".".$fileType[1];
                    move_uploaded_file($_FILES["update_game_image"]["tmp_name"], "../games/images/" . $filename);
                }
                $db->update("bib_game_types", array('image' => $filePath, 'id' => $_POST['update_game_type']), 'id');
            }
        }
        if($games->updateGame($_POST['update_game_title'], $_POST['update_game_description'], $_POST['update_game_tags'], $_POST['update_game_type']))
        {
            echo "<div class=\"userMsg\">".$db->real_escape_string($_POST['update_game_title'])." er oppdatert til siste versjon!</div>";
        }
        
        
    }
        ?>
<!--
        <div class="divContentBox">
            <div class="divContentBoxAction"><h2>Legg til konsoll</h2></div>
            <div class="divBoxContent">
                <div class="divForm">
                    <table>
                    <tr>
                    	<td>Konsoll type</td>
                    	<td></td>
                    </tr>
                    <tr>
                    	<td></td>
                    	<td></td>
                    </tr>
                    </table>
                </div>
            </div>
            
        </div>
        <div class="divContentBox">
            <div class="divContentBoxAction"><h2>Legg til konsoll type</h2></div>
            <div class="divBoxContent">
                <div class="divForm">
                    <input class="inputBig solo" type="text" placeholder="Navn på konsollen. Eks: Playerstation 3" name="new_console_name" />
                    <input class="inputBig solo" type="text" placeholder="Kortnavnet på konsollen. Eks: PS3" name="new_console_sname" />
                    <input type="submit" value="Legg til" /><input type="reset" value="Reset" />
                </div>
            </div>
            
        </div>
-->
        <div class="divContentBox">
            <h2>TV Skap</h2>
            <table class="tableGames">
                <tr>
                    <th>#ID</th>
                    <th>Status</th>
                    <th>Informasjon</th>
                </tr>
                <?php
                $query = $db->query("SELECT * FROM bib_TVs ORDER BY id");
                while($row = $query->fetch_assoc())
                {
                    $status = $row['available'] == 1 ? "Tick.png" : "Minus.png";
                    echo "<tr>\n";
                    echo "    <td>Skap ".$row['id']."</td>\n";
                    echo "    <td><img class=\"actionIcons\" alt=\"Status på TV-skapet\" onclick=\"toggleTVstatus(".$game['id'].")\" id=\"tv".$row['id']."\" src=\"images/".$status."\" height=\"25px\" /></td>\n";
                    echo "      <td></td>\n";
                    echo "</tr>\n";
                }
                ?>
            </table>
        </div>
        <div class="divContentBox">
            <div class="divContentBoxLeft">
                <h2>Legg til spill</h2>
                    <form method="post" id="new_game" enctype="multipart/form-data">
                        <table class="tableGame">
                        <tr>
                        	<td>Tittel</td>
                        	<td><input id="game_title" tabindex="1" class="width" type="text" name="new_game_title" /></td>
                        </tr>
                        <tr>
                        	<td>Beskrivelse</td>
                        	<td><textarea rows="10" tabindex="2" id="game_desc" class="width" name="new_game_desc"></textarea></td>
                        </tr>
                        <tr>
                        	<td>Tags</td>
                        	<td><input id="game_tags" tabindex="3" class="width" type="text" name="new_game_tags" /></td>
                        </tr>
                        <tr>
                        	<td>Type</td>
                        	<td>
                            <select class="width" tabindex="4" name="new_game_type" size="1">
                            <?php
                            $typeQuery = $db->query("SELECT * FROM `bib_console_types`");
                            while($row = $typeQuery->fetch_assoc())
                            {
                               echo "<option value=\"".$row['id']."\">".$row['name']."</option>\n"; 
                            }
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                        	<td>Bilde</td>
                        	<td><input id="game_image" tabindex="5" type="file" name="new_game_image" size="20" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input id="game_add" tabindex="6" type="submit" value="Legg til" /><input type="reset" value="Tilbakestill" /></td>
                        </tr>
                        </table>
                    </form>
                
            </div>
            <div class="divContentBoxRight">
                <div id="divSearchSuggestions"></div>
            </div>
        </div>
        <div class="divContentBox hidden" id="game_edit">
            <h2>Endre spill</h2>
            <form method="post" enctype="multipart/form-data">
            <table class="tableGame">
                <tr>
                    <td>Tittel</td>
                    <td><input class="inputBig" type="text" id="update_game_title" name="update_game_title" value="" /><input type="hidden" name="update_game_type" id="update_game_type" value="" /></td>
                </tr>
                <tr>
                    <td>Beskrivelse</td>
                    <td><textarea class="inputBig" rows="10" id="update_game_description" name="update_game_description"></textarea></td>
                </tr>
                <tr>
                    <td>Tags</td>
                    <td><input class="inputBig" type="text" id="update_game_tags" name="update_game_tags" value="" /></td>
                </tr>
                <tr>
                    <td>Console</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Bilde</td>
                    <td><input class="inputBig" type="file" name="update_game_image" id="update_game_image" accept="image/png" size="20" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Oppdater" name="game_update" /></td>
                </tr>
            </table>
            </form>
        </div>
        <div class="divContentBox">
            <h2>Viser <?php echo $games->getNumGames(); ?> spill...</h2>
            <table class="tableGames">
            <tr>
            	<th width="40%">Tittel</th>
            	<th width="15%">Konsoll</th>
                <th>Status</th>
                <th>Handlinger</th>
            	<th style="text-align: right;">Sist Oppdatert</th>
            </tr>
            <?php
            $query = $games->getGames();
            while($game = $query->fetch_assoc())
            {
                // <a target=\"_new\" href=\"../games/images/".$game['image']."\"><img height=\"25px\" src=\"images/Photos.png\" /></a>
                $status = $game['available'] == 1 ? "Tick.png" : "Minus.png";
                echo "<tr>\n";
                echo "	<td>".$game['title']."</td>\n";
                echo "	<td>".$game['console']."</td>\n";
                echo "	<td><img class=\"actionIcons\" alt=\"Status på spillet\" onclick=\"toggleStatus(".$game['id'].")\" id=\"status_".$game['id']."\" src=\"images/".$status."\" height=\"25px\" /> </td>\n";
                echo "	<td><img class=\"actionIcons\" onclick=\"edit(".$game['id'].")\" src=\"images/edit.png\" alt=\"Endre\" /> <img class=\"actionIcons imgGames\" id=\"".$game['id']."\" src=\"images/picture.png\" alt=\"Bildet\" />  <img class=\"actionIcons\" src=\"images/delete.png\" alt=\"Slett\" /> <img class=\"imgGamePreview\" id=\"image-".$game['id']."\" alt=\"Bilde!\" src=\"../games/images/".$game['image']."\" /></td>\n";
                echo "	<td style=\"text-align: right;\">".$game['last_changed']."</td>\n";
                echo "</tr>\n";
            }
            ?>
            </table>
            <script type="text/javascript">
            	$(".tableGames tr").hover(function() {
            	  $(this).css("background-color", "#0096FF"); 
            	}, function() {
            	  $(this).css("background-color", "transparent"); 
            	});
            </script>
        </div>
        </div>
	</div>
</div>
<?php
include_once('global/footer.php');
?>

</body>

</html>
<?php
}
else
{
    echo "Din sesjon har utløpt, vennligst <a href=\"login.php\">login på nytt</a>!\n";
}
}
?>