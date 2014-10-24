$(document).ready(function(){
    $(".userMsg").delay(2500).hide("slow");
    $(".divContentBoxAction").click(function() {
       $(this).next().toggle("slow"); 
       
        if($(this).css("background-image").indexOf("open.png") != -1)
        {
            $(this).css("background-image", "url('images/close.png')");
        }       
        else
        {
            $(this).css("background-image", "url('images/open.png')");
        }
    });
	$(".imgGames").hover(function() {
	   //hover over
       var id = $(this).attr("id");
       $("#image-"+id).show("fast");
	}, function() {
	   //hover out
       var id = $(this).attr("id");
       $("#image-"+id).hide("slow");
	});
    $('#game_title').keyup(function() {
        if($("#game_title").val().length > 0)
        {
            $.get("../ajax/title_search.php", { title: $('#game_title').val()}, function(data)
            {
                if(data.indexOf('description') > 0)
                {
                    var obj = $.parseJSON(data);
                    $("#game_image").prop('disabled', true);
                    $("#game_title").val(obj.title);
                    $("#game_desc").val(obj.description);
                    $("#game_tags").val(obj.tags); 
                                       
                }
                else
                {
                    if(data.length > 0)
                    {
                        $("#divSearchSuggestions").show('normal');
                        $("#divSearchSuggestions").html("<h3>Eksisterende spill funnet:</h3>"+data);
                    }
                    else
                    {
                        $("#divSearchSuggestions").html("Ingen funnet.");
                    }
                }        
            });
        }
        else
        {
            $("#game_title").val("");
            $("#game_desc").val("");
            $("#divSearchSuggestions").hide('slow');
            $("#game_tags").val(""); 
            $("#game_image").prop('disabled', false);     
        }            
    });
});
    function toggleStatus(id)
    {
        $.get("../ajax/toggle_status.php", { game_id: id}, function(data)
        {    
            if(data.indexOf("OK") != -1)
            {
            var imgSrc = $("#status_"+id).attr("src");
            if(imgSrc === "images/Minus.png")
            {
                $("#status_"+id).attr("src", "images/Tick.png");   
            }
            else
            {
                $("#status_"+id).attr("src", "images/Minus.png");
            }
            }
        });
    }
    function edit(gameid)
    {
        $.get("../ajax/getGame.php", { id : gameid}, function(data) {
            if(data.indexOf('description') > 0)
            {
                var obj = $.parseJSON(data);
                $("#update_game_title").val(obj.title);
                $("#update_game_description").val(obj.description);
                $("#update_game_tags").val(obj.tags);   
                $("#update_game_type").val(obj.type);
                   
                $("#game_edit").show("slow");               
            } 
        });
        
    }