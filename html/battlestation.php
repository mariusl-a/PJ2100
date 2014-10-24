<?php
header('Content-Type: text/html; charset=utf-8'); 
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
    <title>NITH Battlestation</title>
    
    <link rel="stylesheet" href="../css/battlestation.css" type="text/css" media="screen" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css' />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="icon" type="image/png" href="../IT_favicon.ico" />
    <script src="jquery-1.7.1.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function() {
    	$('#btnLesMer').toggle(function() {
    
    		$('#box3').animate({
    
    			width: '+=390',
    			height: '+=600'
    
    		}, 500, function() {});
    
    		}, function () {
    
    			$('#box3').animate({
    			width: '-=390',
    			height: '-=600'
    		});
    
    	});
        $("#search_title").keyup(function() {
            $.get("../ajax/search_games.php", { title: $(this).val()}, function(data) {
                var string = "<table id=\"tableResults\">\n<tr>\n<th>Spill tittel</th>\n<th>Konsoll</th>\n</tr>\n";
                var results = $.parseJSON(data);
                $.each(results, function(i, item) {
                    string += "<tr>\n<td><a href=\"battlestation_sub.php?game-id="+item.game_id+"\">"+item.title+"</a></td>\n<td>"+item.console+"</td>\n</tr>\n";    
                });
                string += "</table>\n";    
                $("#resultat").html(string);
                $("#resultat").show("slow");
            });
        });
        $("#search_select").change(function() {
            $.get("../ajax/search_games.php", { title: $(this).val()}, function(data) {
                var string = "<table id=\"tableResults\">\n<tr>\n<th>Spill tittel</th>\n<th>Konsoll</th>\n</tr>\n";
                var results = $.parseJSON(data);
                $.each(results, function(i, item) {
                    string += "<tr>\n<td><a href=\"battlestation_sub.php?game-id="+item.game_id+"\">"+item.title+"</a></td>\n<td>"+item.console+"</td>\n</tr>\n";    
                });
                string += "</table>\n";    
                $("#resultat").html(string);
                $("#resultat").show("slow");
            });
        });
        $('#btnLesMer').click(function() {
            if($(this).css("background-image").indexOf("next_left") > 0)
            {
                $(this).css("background-image", "url('../bilder/next_right.png')");
                $("#resultat").show("slow");
            }
            else
            {
                $(this).css("background-image", "url('../bilder/next_left.png')");
                $("#resultat").hide("slow");
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
					Spillrommet - Battlestation NITH<br/> 
				</p>
				</div>
				<div id="text_content">
					<p>
						- er tilgjengelig for studier og avkobling 
						for studenter mandag til torsdag. Se informasjon under rundt bruken av rommet!
					</p>
				</div>
			</div>
			<div id="box2"></div>
		</div>
		<div id="content_bottom">
			<div id="box3">
				<div id="btnLesMer"></div>
				<div id="regler_left">
					
					<div id="regler_head"><p>Regelverk for Battlestation NITH</p></div>
					<p>
						<br/><br/><br/><br/><br/>
						Utlån av nøkkelsett til spillrommet og konsollskapene skjer via biblioteket på samme måte som med bøker. 
						(Husk at biblioteket stenger 15:00.)<br/><br/><br/><br/>
						Lånetiden er fra utlånstidspunkt til skolens stengetid.<br/><br/>
						Innlevering skjer i boksen ved døren på spillrommet. NB! Ikke ta med nøkkelen hjem.<br/><br/>
						For å få lånt nøkkelsett MÅ studentkort fremvises.<br/><br/>
						Det er kun mulig å låne ett nøkkelsett pr person. (Ett sett gir tilgang til ett konsollskap.)<br/><br/>
						Den som blir registrert som låner av nøkkelsettet blir holdt ansvarlig for rommets innhold. I de fleste tilfeller vil det si: 
						Konsoll, kontroller(e), spill og headsett (se liste i skapet). Skapenes innhold vil bli kontrollert.
					</p>
				</div>
				<div id="regler_right">
					<p>
						Ved tap av materiell påløper erstatningsansvar etter faste satser.
						Erstatningsbeløp:<br/><br/>
						   * Spill: kr 500,-<br/>
							* Kontroller: kr 500,-<br/>
							* Headsett: kr 400,-<br/>
							* Konsoll: kr 4000,-<br/><br/>
						Biblioteket forbeholder seg retten til å endre satsene på erstatningsbeløp på spesielle gjenstander.<br/><br/>
						Låntaker som ikke overholder utlånsreglene, kan bli nektet framtidige lån.<br/><br/>
						Fredager/over helgen er rommet reservert for grupper og utvalg.<br/><br/>
						Rommet kan reserveres for spesielle arrangementer. Kontakt biblioteket!<br/><br/>
						GAME ON!
					</p>
				</div>
			</div>
			<div id="box4">
				<div id="box4Head"><p>Spillsøk</p></div>
				<div id="tittel">Tittel:</div>
				<div id="sokefelt1">
				    <input type="text" id="search_title" name="title" /><br />
				</div>
				<div id="konsoll">Konsoll:</div>
				<div id="sokefelt2">
						<select id="search_select"> 
						  <option></option>
						  <option value="alle">Alle</option>
						  <option value="2">PS3</option>
						  <option value="1">Xbox</option>
						  <option value="3">Wii</option>
						</select>
				</div>
			</div>
		</div>
		<div id="content_right">
			<div id="box5">
				<div class="tv"></div>
				<div class="tvText">Status</div>
				<div class="tvStatus"<?php if($games->getTvStatus(1) == 0) { echo " style=\"background-color:red;\"";} ?>></div>
			</div>
			<div id="box6">
				<div class="tv"></div>
				<div class="tvText">Status</div>
				<div class="tvStatus"<?php if($games->getTvStatus(2) == 0) { echo " style=\"background-color:red;\"";} ?>></div>
			</div>
			<div id="box7">
				<div class="tv"></div>
				<div class="tvText">Status</div>
				<div class="tvStatus"<?php if($games->getTvStatus(3) == 0) { echo " style=\"background-color:red;\"";} ?>></div>
			</div>
			<div id="box8">
				<div class="tv"></div>
				<div class="tvText">Status</div>
				<div class="tvStatus"<?php if($games->getTvStatus(4) == 0) { echo " style=\"background-color:red;\"";} ?>></div>
			</div>
			<div id="box9">
				<div class="tv"></div>
				<div class="tvText">Status</div>
				<div class="tvStatus"<?php if($games->getTvStatus(5) == 0) { echo " style=\"background-color:red;\"";} ?>></div>
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