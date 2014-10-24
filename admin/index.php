<?php
//"debug mode", show all errors..
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(!isset($_COOKIE['bib_sess']))
{
    header("Location: login.php");
}
else
{
header('Content-type: text/html; charset=utf-8');
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
$articles = new Articles($db);

//if the user session is found in the database, then its authed correctly
if($user->UserData($_COOKIE['bib_sess']))
{
?>  

<!DOCTYPE html>
<html>
<head>
    <title>BibAdmin | Home</title>
    
    <link rel="stylesheet" href="./css/index.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/forms.css" type="text/css" media="screen" />
    <link rel="icon" type="image/png" href="../IT_favicon.ico" />
</head>

<body>

<script src="jquery-1.7.1"></script>

<div id="header">
	<div id="headline"></div>
</div>

<div id="container">
	<div id="container_content">
<?php include_once('global/menu.html'); ?>
        
        <div id="divContent">
        <h2>Hovedsiden</h2>
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