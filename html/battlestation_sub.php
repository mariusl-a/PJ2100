<?php
require_once('../inc/mysql.class.php');
require_once('../inc/games.class.php');
require_once('../inc/log.class.php');

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
	<title>NITH Spilldetaljer</title>

<link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="../css/battlestation_sub.css" type="text/css" media="screen">

<script src="jquery-1.7.1.js"></script>
<script>

</script>
 
</head>

<body>

<div id="header">
	<div id="headline"></div>
	<div id="btnBack" onclick="location.href='battlestation.php';"></div>
</div>
<?php
if(isset($_GET['game-id']))
{
    if($data = $games->getGameData($_GET['game-id']))
    {
        
    }
    else
    {
        die("Ingen spill funnet.");
    }
}
else
{
    die("Ingen spill funnet.");
}
?>
<div id="container">
	<div id="container_content">
		<div id="boxTop">
			<div id="top_text">
				<p><?php echo $data['title']; ?></p>
			</div>
		</div>
		<div id="boxLeft">
		  <div id="imgCenter"><img height="100%" src="../games/images/<?php echo $data['image']; ?>" /></div>
		</div>
		<div id="boxRight">
			<div id="right_head">
				<p>Beskrivelse</p>
			</div>
			<div id="right_content">
				<p><?php echo $data['description']; ?></p>
			</div>
		</div>
		<div id="boxBottom">
		
		</div>

	</div>


</body>

</html>