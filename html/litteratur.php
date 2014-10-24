<?php
header('Content-Type: text/html; charset=utf-8'); 
require_once('../inc/mysql.class.php');
require_once('../inc/articles.class.php');
//database class
$db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
$article = new Articles($db);
?>
<!DOCTYPE html>

<html>

<head>
	<title>NITH Litteratur</title>
    <link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="../css/litteratur.css" type="text/css" media="screen" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
    <link rel="icon" type="image/png" href="../IT_favicon.ico" />
    <script src="jquery-1.7.1.js"></script>

<script>
$(function() {

	$('#btnTop').toggle(function() {

		$('#top_dropdown').animate({

			height: '+=350',

		}, 500, function() {});

		}, function () {

			$('#top_dropdown').animate({

			height: '-=350',

		});

	});

});

$(document).ready(function(){
    $("#btnTop").click(function() {
        if($(this).css("background-image").indexOf("next_down.png") > 0) {
            $(this).css("background-image", "url('../bilder/next_up.png')");
        }  
        else {
            $(this).css("background-image", "url('../bilder/next_down.png')");
        }
    });  
    $("#journals_search_select").change(function() {
        $.get("../ajax/getJournals.php", {title: $(this).val()}, function(data) {
            var obj = $.parseJSON(data);
            $("#resultat").html(obj.description);
        });
    }); 
});
</script>
</head>
<body>
<div id="header">
	<div id="headline"></div>
	<div id="btnBack" onclick="location.href='index.php';"></div>
</div>

<div id="container">
	<div id="container_content">
		<div id="boxTop">
			<div id="textTopLeft"><p>Magasiner og tidsskrifter rettet mot:</p></div>
			<div id="top_dropdown">
				<div id="drop_down_content">
					<p><a href="">- Digital markedsføring</a></p>
					<p><a href="">- Mobil apputvikling</a></p>
					<p><a href="">- Spillprogrammering</a></p>
					<p><a href="">- Spilldesign</a></p>
					<p><a href="">- Programmering</a></p>
					<p><a href="">- 3D-grafikk</a></p>
					<p><a href="">- Interaktivt design</a></p>
					<p><a href="">- E-buisness</a></p>
					<p><a href="">- Industribachelor</a></p>		
				</div>
			</div>
			<div id="btnTop"></div>
		</div>
		<div id="boxMid">
			<div id="box1">
				<p><?php $article->showArticle(20); ?></p>
			</div>
			<div id="box2"></div>
			<div id="box3">
				<div id="box3Head"><p>Tidsskrifter</p></div>
				<div id="sokefelt">
						<select id="journals_search_select"> 
						  <option></option>
                          <?php
                          $query = $db->query("SELECT * FROM bib_journal_types");
                          if($query->num_rows > 0)
                          {
                            while($row = $query->fetch_assoc())
                            {
                                echo "<option value=\"".$row['id']."\">".$row['title']."</option>\n";
                            }
                          }
                          ?>
						</select>
				</div>
				<div id="resultat"></div>
			</div>
		</div>
		<div id="boxBottom">
		
		</div>
	</div>
</div>
<div id="footer">
	<p id="pRaleway">Eksamensprosjekt "NITH Bibliotek" @ Gruppe 11 
	- 2012 <br /> Norges Informasjonsteknologiske Høgskole, NITH . Schweigaardsgate 14, 
	Oslo</p>
</div>
</body>

</html>

