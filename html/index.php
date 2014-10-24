<!DOCTYPE html>

<html>

<head>

	<title>NITH Bibliotek</title>

<link rel="stylesheet" href="../css/index.css" type="text/css" media="screen">
<link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<link rel="icon" type="image/png" href="../IT_favicon.ico" />
<script src="jquery-1.7.1.js"></script>

<script>

$(document).ready(function(){
    //ønsker
    $("#front2").hover(function() {
       $(this).css("background-image", "url('../bilder/onske_tile_hover.png')"); 
    }, function() {
       $(this).css("background-image", "url('../bilder/onske_tile.png')");  
    });
    
    $("#front2").click(function() {
        openWish();
    });
    //internet explorer
    if($.browser.msie)
    {
        $("#wish_name").val("Tittel her...");
        $("#wish_message").html("Din melding her...");
    }
    //bokanbefaling
    $("#box3").hover(function() {
        $(this).css("background-image", "url('../bilder/bokanbefaling_stor_hover.png')"); 
    }, function() {
        $(this).css("background-image", "url('../bilder/bokanbefaling_stor.png')"); 
    });
    $(function() {
    
    	$('#box3').toggle(function() {
    
    		$(this).animate({
    
    			width: '+=165',
    			height: '+=350'
    
    		}, 500, function() {});
    
    		}, function () {
    
    			$('#box3').animate({
    
    			width: '-=165',
    			height: '-=350'
    
    		});
    
    	});
    
    });
    $("#box4").click(function(event){
    	$("#front4").slideToggle();
    });
    //trude hover
    $('#box5').hover(function() {
        if($(this).css("width").indexOf("150") > -1)
        {
            $(this).animate({ width: '+=810'}, 500, function() { });
        }
    }, function() {
        if($(this).css("width").indexOf("960px") > -1)
        {
            $(this).animate({ width: '-=810'}, 500, function() { });
        }
    });
    $('#box5').click(function() {
        if($(this).css("width").indexOf("150") > -1)
        {
            $(this).animate({ width: '+=810'}, 500, function() { });
        }
        else if($(this).css("width").indexOf("960px") > -1)
        {
            $(this).animate({ width: '-=810'}, 500, function() { });
        }
    });
    //Battlestation
    $("#front7").hover(function() {
       $(this).css("background-image", "url('../bilder/bs_tile_hover.png')");
    }, function() {
       $(this).css("background-image", "url('../bilder/bs_tile.png')"); 
    });
    $("#front8").hover(function() {
       $(this).css("background-image", "url('../bilder/brettspill_tile_hover.png')");
    }, function() {
       $(this).css("background-image", "url('../bilder/brettspill_tile.png')"); 
    });
    $("#box9").click(function(event){
    	$("#front9").slideToggle();
    });
    $("#front10").hover(function() {
       $(this).css("background-image", "url('../bilder/utlaan_tile_hover.png')");
    }, function() {
       $(this).css("background-image", "url('../bilder/utlaan_tile.png')"); 
    });
    $("#front11").hover(function(){
       $(this).css("background-image", "url('../bilder/info_tile_hover.png')");
    }, function() {
       $(this).css("background-image", "url('../bilder/info_tile.png')"); 
    });
    $("#front12").hover(function() {
        $(this).css("background-image", "url('../bilder/litteratur_tile_hover.png')");
    }, function() {
        $(this).css("background-image", "url('../bilder/litteratur_tile.png')");
    });
});

</script>
	
</head>

<body>


<div id="divWishContainer"></div>
<div id="divMakeWish">
    <div id="divMakeWishClose"></div>
    <div id="divWishForm">
        <input type="text" placeholder="Ditt navn..." id="wish_name" />
        <textarea placeholder="Dine ønsker(melding)..." id="wish_message" rows="10" cols="25"></textarea>
        <input type="submit" id="wish_send" value="Send inn ønske" />
        <div id="divResult"></div>
    </div>
</div>
<script type="text/javascript">
$("#divMakeWishClose").click(function() {
    closeWish();
});
$("#divWishContainer").click(function() {
   closeWish(); 
});
$("#wish_send").click(function() {
    var name = $("#wish_name").val();
    var message = $("#wish_message").val();
    if(name.length > 0 && message.length > 0)
    {
        $.get('../ajax/sendWish.php', { name:name, msg:message}, function() {
            $("#wish_name").attr("value", "");
            $("#wish_message").val("");
            $("#divResult").css("border", "3px solid lightgreen").html("Ditt ønske har blitt sendt!").show("fast").delay(1000).hide("normal");
            closeWishTimeout();        
        });
    }
    else
    {
        $("#divResult").css("border", "3px solid red").html("Du må fylle inn alle felt før du sender..").show("slow").delay(2000).hide("slow");
    }
});
function closeWishTimeout()
{
    $("#divMakeWish").delay(1500).hide("slow", function() {
        $("#divWishContainer").hide("slow");
        $("#divWishForm").css("display", "");
    });
    
}
function closeWish()
{
    $("#divMakeWish").hide("slow", function() {
        $("#divWishContainer").hide("slow");    
    });
}
function openWish()
{
    $("#divWishContainer").show("fast", function() {
        $("#divMakeWish").show("slow");    
    });
}
</script>

<div id="header">
	<a href="index.html"><div id="headline"></div></a>
</div>
<div id="container">
	<div id="container_content">
	
		<div id="content_top">
			<div id="box1"><script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
				<script>
				new TWTR.Widget({
				  version: 2,
				  type: 'profile',
				  rpp: 2,
				  interval: 30000,
				  width: 465,
				  height: 100,
				  theme: {
					shell: {
					  background: '#00AEDD',
					  color: '#ffffff'
					},
					tweets: {
					  background: '#00AEDD',
					  color: '#ffffff',
					  links: '#024E89'
					}
				  },
				  features: {
					scrollbar: false,
					loop: false,
					live: true,
					behavior: 'all'
				  }
				}).render().setUser('nithbiblioteket').start();
				</script>
			</div>
			
			<div id="box2">
				<div id="front2"></div>
			</div>
				
			<div id="box3"></div>
			
			<div id="box4">
				<div id="front4"></div>
				<div id="back4"></div>
				
			</div>
		</div>
		
		<div id="content_middle">
			<div id="box5"></div>
			<div id="box6"></div>	
			<div id="box7">
				<a href="battlestation.php"><div id="front7"></div></a>
			</div>
			
			<div id="box8">
                <a href="brettspill.php"><div id="front8"></div></a>
			</div>
		</div>
		
		<div id="content_bottom">
			<div id="box9">
				<div id="front9"></div>
				<div id="back9"></div>
			</div>
			
			<div id="box10">
				<div id="front10"></div>
				<div id="back10"></div>
			</div>
			
			<div id="box11">
				<a href="veiledning.php"><div id="front11"></div></a>
			</div>
			
			<div id="box12">
				<a href="litteratur.php"><div id="front12"></div></a>
			</div>
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