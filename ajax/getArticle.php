<?php
session_start();
header('Content-Type: text/html; charset=utf-8'); 
if($_GET['id'])
{
    function stripz($text)
    {
        $text = str_replace("\n", "", $text);
        $text = str_replace("\r", "", $text);
        $text = str_replace("<br />", "\\n", $text);
        $text = str_replace('"', '\"', $text);
        return $text;
    }
    include_once("../inc/mysql.class.php");
    include_once('../inc/articles.class.php');
    include_once('../inc/log.class.php');
    include_once('../inc/users.class.php');
        
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information  
    $article = new Articles($db);
    $user = new Users($db);
        
    if($user->UserData($_SESSION['bib_sess']))
    {
        if($data = $article->getArticle($_GET['id']))
        {
            $admin = $data['user_id'] == $user->Data['id'] ? "deg." : $user->getUserById($data['user_id']);
            $protected = $data['protected'] == 1 ? "<strong>Denne artikkelen er beskyttet</strong>" : "Denne artikkelen er <strong>ikke</strong> beskyttet";
            $protection_img = $data['protected'] == 1 ? "images/protected.png" : "images/unprotected.png";
            //echo '{ "title":"'.$data['title'].'", "text":"'.$data['text'].'" }';
echo '
{
  "title": "'.$data['title'].'",
  "txt": "'.addslashes($data['text']).'",
  "admin": "'.$admin.'",
  "protection": "'.$protected.'",
  "protectionImage": "'.$protection_img.'",
  "timestamp": "'.$data['edited_at'].'"
}';
        }
        else
        {
            echo "Failed.\n";
        }
    }
    else
    {
        echo "Not authenticated\n";
    } 

}
else
{
    echo "wat";
}
?>
