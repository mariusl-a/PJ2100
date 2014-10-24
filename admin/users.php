<?php
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
//user class
$user = new Users($db);
//articles
//$articles = new Articles($db);

//if the user session is found in the database, then its authed correctly
if($user->UserData($_COOKIE['bib_sess']))
{
    $_SESSION['bib_sess'] = $_COOKIE['bib_sess'];
?>  

<!DOCTYPE html>
<html>
<head>
    <title>BibAdmin | Logg</title>
    
    <link rel="stylesheet" href="./css/index.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/forms.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/log.css" type="text/css" media="screen" />
    <link rel="icon" type="image/png" href="../IT_favicon.ico" />
    <script src="js/jquery-1.7.1.js"></script>
    <script src="js/games.js"></script>
</head>

<body>

<script type="text/javascript">
$(document).ready(function() {
    $("tr[class!=trHeader]").hover(function() {
       $(this).css("background-color",  "rgb(0,150,255)"); 
    }, function(){
       $(this).css("background-color",  ""); 
    });
});
</script>

<div id="header">
	<div id="headline"></div>
</div>

<div id="container">
	<div id="container_content">
<?php include_once('global/menu.html'); ?>
        
        <div id="divContent">
            <div class="divContentBox">
            <h2>Brukeradministrasjon</h2>
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