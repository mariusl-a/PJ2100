<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('PJ2100/inc/mysql.class.php');
require_once('PJ2100/inc/log.class.php');
require_once('PJ2100/inc/games.class.php');

//database class
$db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
//log
$log = new Log($db);
//games class
$games = new Games($db, $log);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>

<style type="text/css">

body {
	width: 98%;
	min-height: 100%;
	background-color: #232323;
	font-family: Raleway;
}

#logo {
	width: 80%;
}

#leidDiv {
	color: white;
	font-size: 3.5em;
}

.button {
	background-color: #00B0D6;
	font-size: 1em;
	width:60%;
	height:70%;
	color: white;
	border: 3px white solid;
}


</style>

</head>
<body>

	<div id="leidDiv">
	<center>
		<br />
        <?php
        $error = true;
        if(isset($_GET['id']))
        {
            if($gameData = $games->changeAvailableById($_GET['id']))
            {
                $msg = $gameData['available'] == 1 ? "Du har nå leid spillet" : "Du har nå avsluttet leien";
                $buttonValue = $gameData['available'] == 1 ? "Avslutt leie" : "Lei spillet igjen";
        ?>
		<img src="PJ2100/games/images/<?php echo $gameData['image']; ?>" /><br /><br />
		<img src="Tick.png" width="105" height="105" />
		<p><?php echo $msg; ?> "<?php echo $gameData['title']; ?>"</p>
		<form method="get" accept="laan.php"><input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" /><input class="button" type="submit" value="<?php echo $buttonValue; ?>" /></form>	
        <?php
                $error = false;
            }
            else
            {
                echo "Feil...";
            }
        }
            if($error)
            {
            ?>
        <img src="leiseg.png" /><br /><br />
        <img src="Minus.png" width="105" height="105" />
		<p>Det oppsto en alvorlig feil, kontakt administrator med engang! Eller prøv på nytt...</p>
		<input class="button" type="button" onclick="window.location.href=unescape(window.location.pathname)" value="Prøv på nytt" />	
            <?php
            }
            ?>

	</center>
	</div>


</body>



</html>