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
    <title>NITH Brettspill</title>
    
    <link rel="stylesheet" href="../css/brettspill.css" type="text/css" media="screen" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css' />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
    <link rel="icon" type="image/png" href="../IT_favicon.ico" />
    <script src="jquery-1.7.1.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $("#search_title").keyup(function() {
            if($(this).val().length == 0) {
                $("#resultat").hide("slow");
            }
            else {
                $.get("../ajax/search_boardgames.php", { title: $(this).val()}, function(data) {
                    var string = "<table id=\"tableResults\">\n<tr>\n<th>Spill tittel</th>\n<th>Tilgjengelig</th>\n</tr>\n";
                    var results = $.parseJSON(data);
                    if(results)
                    {
                        $.each(results, function(i, item) {
                            var img = "Minus.png";
                            if(item.status == 1)
                            {
                                img = "Tick.png";
                            }
                            string += "<tr>\n<td>"+item.title+"</td>\n<td><img height=\"20px\" src=\"../bilder/"+img+"\" /></td>\n</tr>\n";    
                        });
                        string += "</table>\n";    
                    }
                    else
                    {
                        string += "<tr>\n<td>Ingen funnet</td>\n<td><img height=\"20px\" src=\"../bilder/Minus.png\" /></td>\n</tr>\n";
                    }
                    $("#resultat").html(string);
                    $("#resultat").show("slow");
                });
            }
        });
    });
    </script>	
</head>

<body>

<div id="header">
	<div id="headline"></div>
	<div id="btnbackToHome" onclick="location.href='index.php';"></div>
</div>

<div id="container">
	<div id="container_content">
		<div id="content_top">
			<div id="box1">
				<div id="text_head">
				<p>
					Brettspillsiden NITH <br/> 
				</p>
				</div>
				<div id="text_content">
					<p>
                    <?php $article->showArticle(22); ?>
					</p>
				</div>
			</div>
			<div id="box2"></div>
		</div>
		<div id="content_bottom">
			<div id="box3">
				<div id="event_head"><p>Aktuelle arrangementer</p></div>
				<div id="event_content">
                <?php $article->showArticle(21); ?>
<!--
					<p><a href="">TG</a></p>
					<p><a href="">LAN</a></p>
					<p><a href="">Magic-turnering</a></p>
					<p><a href="">Starcraft 2 brettspillaften</a></p>
-->
				</div>
				
			</div>
			<div id="box4">
				<div id="box4Head"><p>Brettspillsøk:</p></div>
				<div id="tittel">Tittel:</div>
				<div id="sokefelt1">
				    <input type="text" id="search_title" name="title" /><br />
				</div>
			</div>
		</div>
		<div id="resultat"></div>
	</div>
</div>
<div id="footer">
	<p id="pRaleway">Eksamensprosjekt "NITH Bibliotek" @ Gruppe 11 
	- 2012 <br /> Norges Informasjonsteknologiske Høgskole, NITH . Schweigaardsgate 14, 
	Oslo</p>
</div>
</body>


</html>