<?php
session_start();
header('Content-Type: text/html; charset=utf-8'); 
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
//user class
$user = new Users($db);
//articles
$articles = new Articles($db);

//if the user session is found in the database, then its authed correctly
if($user->UserData($_COOKIE['bib_sess']))
{
    $_SESSION['bib_sess'] = $_COOKIE['bib_sess'];
    //$user->NewUser("u", "p", "epost@epost.no", "Spel");
?>  

<!DOCTYPE html>
<html>
<head>
    <title>BibAdmin | Info</title>
    
    <link rel="stylesheet" href="./css/index.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/forms.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/articles.css" type="text/css" media="screen" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="icon" type="image/png" href="../IT_favicon.ico" />
    <script src="js/jquery-1.7.1.js"></script>
</head>

<body>
<script type="text/javascript">
$(document).ready(function(){
    $('#article_protection_img').click(function(){
        $.get("../ajax/toggle_protection.php", { id: $('#article_id').val()}, function(data) {
            if(data.indexOf("done") != -1)
            {
                loadArticle($('#article_id').val());
            }  
        })
    });
    $("#article_information").click(function() {
       $("#divBBcodes").toggle("slow");
    });
    //article_information
    $('#article_id').change(function() {
        if($('#article_id').val() == -1)
        {
            $("#article_title").val("");
            $("#article_text").text("");
            //$("#last_updated").val("");
            $("#article_protection").hide("slow");
            $("#article_info").hide("slow");
            $("#article_protection_img").hide("slow");
            $("#article_submit").attr("value", "Legg til Artikkel");
        }
        else
        {
            loadArticle($('#article_id').val());
        }
        
    });
    function loadArticle(id)
    {
        $.get("../ajax/getArticle.php", { id: $('#article_id').val()}, function(data)
        {
            $("#article_submit").attr("value", "Oppdater artikkel");

            var obj = $.parseJSON(data);    
            $("#article_title").val(obj.title);
            $("#article_text").text(stripslashes(obj.txt).replace(/_br_/g, "\r\n"));
            $("#last_updated").html(obj.timestamp);
            $("#article_protection").html(obj.protection);
            $("#article_admin").html(obj.admin);
            $("#article_protection_img").attr("src", obj.protectionImage);
            $("#article_protection").show("slow");
            $("#article_protection_img").show("fast");
            $("#article_info").show("slow");
            

        });
    }
    function stripslashes(str) {
    str=str.replace(/\\'/g,'\'');
    str=str.replace(/\\"/g,'"');
    str=str.replace(/\\0/g,'\0');
    str=str.replace(/\\\\/g,'\\');
    return str;
    }
    <?php
    if(isset($_POST['article_update']))
    {
    ?>
            $("#article_protection").show("fast");
            $("#article_info").show("fast");
    <?php
    }
    ?>
    $(".userMsg").delay(2500).hide("slow");
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
        <?php
        //add article?
        $title = "";
        $txt = "";
        $timestamp = "";
        $admin = "";
        $protected = "";
        $id = -1;
        
        if(isset($_POST['article_update']))
        {
            if($articles->setArticle($_POST['article_title'], $_POST['article_text'], $user->Data['id'], $_POST['article_id']))
            {
                if($_POST['article_id'] > -1)
                {
                    echo "<div class=\"userMsg\">Artikkelen er nå oppdatert til siste versjon.</div>";
                    $data = $articles->getArticle($_POST['article_id']);
                    $title = $data['title'];
                    $txt = $articles->strip($data['text']);
                    $timestamp = $data['edited_at'];
                    $admin = $data['user_id'] == $user->Data['id'] ? "deg." : $user->getUserById($data['user_id']);
                    $protected = $data['protected'] == 1 ? "<strong>Denne artikkelen er beskyttet</strong>" : "Denne artikkelen er <strong>ikke</strong> beskyttet";
                    $id = $data['id'];
                }
                else
                {
                    echo "<div class=\"userMsg\">Artikkelen opprettet.</div>";
                }
            }

        }
        elseif(isset($_POST['article_del']) && isset($_POST['article_id']))
        {
            //delete article by article id.
            $delete = $articles->delArticle($_POST['article_id']);
            if($delete == null)
            {
                echo "<div class=\"userMsg error\">Denne artikkelen er beskyttet og kan ikke slettes!</div>";
            }
        }
        ?>
                <h2>Opprette/Endre/Slett Artikler</h2>
                <form method="post">
                <table>
                <tr>
                	<td><input tabindex="1" value="<?php echo $title; ?>" placeholder="Tittel..." id="article_title" name="article_title" style="width: 400px;" type="text" /></td>
                	<td>
                        <select size="1" id="article_id" name="article_id">
                        	<option value="-1">Ny artikkel</option>
                            <?php
$query = $db->query("SELECT title,id FROM bib_articles ORDER BY id ASC");
while($row = $query->fetch_assoc())
{
    if($row['id'] == $id)
    {
        echo "<option value=\"".$row['id']."\" selected>".$row['title']."</option>\n";
    }
    else
    {
        echo "<option value=\"".$row['id']."\">".$row['title']."</option>\n";
    }
}
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                	<td colspan="2">
                    <textarea tabindex="2" placeholder="Teksten skal inn her..." name="article_text" id="article_text" wrap="ON"><?php echo $txt; ?></textarea>
                    </td>
                </tr>
                <tr>
                	<td id="article_info">Sist endret <span id="last_updated"><?php echo $timestamp; ?></span> av <span id="article_admin"><?php echo $admin; ?></span></td>
                    <td title="Du kan beskytte artikler fra å bli slettet!"><span id="article_protection"><?php echo $protected; ?></span></td>
                </tr>
                <tr>
                	<td><input tabindex="3" type="submit" name="article_update" id="article_submit" value="Legg til artikkel" /><input type="submit" value="Slett artikkel" name="article_del" /></td>
                	<td><img id="article_information" style="float: right;margin-left:10px;" src="images/info.png" /><img id="article_protection_img" style="display: none;float: right;" src="images/protected.png" /></td>
                </tr>
                </table>
                </form>
            </div>
            <div class="divContentBox" id="divBBcodes" style="display: none;">
                <h2>BBCodes</h2>
                <div>
                <table>
                <tr>
                    <td>Kode</td>
                    <td>Forklaring</td>
                </tr>
                <tr>
                	<td>[list][/list]</td>
                	<td>For å opprette lister.</td>
                </tr>
                <tr>
                	<td>[img][/img]</td>
                	<td>For å vise bilder. Eks: [img]http://minebilder.no/bilde.png[/img]</td>
                </tr>
                <tr>
                	<td>[b][/b]</td>
                	<td>For å få <strong>bold</strong> tekst.</td>
                </tr>
                <tr>
                	<td>[u][/u]</td>
                	<td>For å få <ins>underline</ins> tekst.</td>
                </tr>
                <tr>
                	<td>[i][/i]</td>
                	<td>For å få <em>kursiv</em> tekst</td>
                </tr>
                <tr>
                	<td>[color=""][/color]</td>
                	<td>Endre farge på tekst. Eks: [color="red"]Rød[/color] <span style="color: red;">Rød</span></td>
                </tr>
                <tr>
                	<td>[size=""][/size]</td>
                	<td>Endre størrelsen på teksten. Eks: [size="12px"]Liten[/size] <span style="font-size: 12px;">Liten</span></td>
                </tr>
                <tr>
                	<td>[url=""][/url]</td>
                	<td>For å legge inn URL. Eks: [url="http://vg.no"]VerdensGang[/url] <a href="http://vg.no">VerdensGang</a></td>
                </tr>
                <tr>
                	<td>[mail=""][/mail]</td>
                	<td>For å vise mailto lenke. Eks: [mail="larmar10@nith.no"]Marius Larsen-Asp[/mail] <a href="mailto:larmar10@nith.no">Marius Larsen-Asp</a></td>
                </tr>
                <tr>
                	<td>[code][/code]</td>
                	<td>For å bruke HTML koden <code>Code</code></td>
                </tr>
                <tr>
                	<td>[quote][/quote]</td>
                	<td>For å bruke Quote <table width="100%" bgcolor="lightgray"><tr><td bgcolor="white">Quote</td></tr></table></td>
                </tr>
                </table>
                
                </div>
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