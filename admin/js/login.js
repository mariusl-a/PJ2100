$(document).ready(function(){
    $("#submit").click(function(event){+
        $("#h2Error").css("display","none");
            $.post("ajax_login.php", $("#formLogin").serialize(), function(data)
            {
                if(data == "true")
                {
                    //true we can redirect
                    window.location.href = "index.php";
                }
                else if(data == "false")
                {
                    //false, wrong login..
                    $("#h2Error").css("display","inline");
                }
            });
        event.preventDefault();
   });
});