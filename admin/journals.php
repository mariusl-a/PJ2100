<?php
session_start();
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
require_once('include_files.php');

//database class
$db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
//log
$log = new Log($db);
//games class
$games = new Games($db, $log);
//user class
$user = new Users($db);
//journals
$journals = new Journals($db);

//if the user session is found in the database, then its authed correctly
if($user->UserData($_COOKIE['bib_sess']))
{
    $_SESSION['bib_sess'] = $_COOKIE['bib_sess'];
?>  

<!DOCTYPE html>
<html>
<head>
    <title>Bibmin | Tidsskrifter</title>
    
    <link rel="stylesheet" href="./css/index.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/forms.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/common.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/journals.css" type="text/css" media="screen" />
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
<?php require_once('global/menu.html'); ?>
        
        <div id="divContent">
            <div class="divContentBox">
                <div class="divContentBoxAction"><h2>Legg til Tidsskrift</h2></div>
                <div class="divBoxContent">
                    <div class="divForm">
                        <form method="post">
                            Tittel<input type="text" />
                        </form>
                    </div>
                </div>
            </div>
            <div class="divContentBox">
            <h2>Legg til Tidsskrift</h2>
            <table class="tableGames">
                <tr>
                    <th>Tittel</th>
                    <th>Antall utgaver</th>
                </tr>
                <?php
                $query = $db->query("SELECT title as Tittel, COUNT(journal_type) AS Antall FROM `bib_journal_types`, `bib_journals` WHERE bib_journal_types.id = bib_journals.journal_type GROUP BY bib_journals.journal_type");
                while($row = $query->fetch_assoc())
                {
                    echo "<tr>";
                    echo "    <td>".$row['Tittel']."</td>";
                    echo "    <td>".$row['Antall']."</td>";
                    echo "</tr>  ";
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