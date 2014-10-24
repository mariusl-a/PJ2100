<?php
require_once('log.class.php');
class Articles
{
    private $db;
    private $log;
    public function __construct(Mysql $db)
    {
        $this->db = $db;
        $this->log = new Log($this->db);
    }
    //inserts or updates article in db
    public function setArticle($title, $text, $userid, $id = -1)
    {
        if($id > -1 && strlen($title) > 1 && strlen($text) > 0 && $userid > 0)
        {
            //exisiting article, update it..
            //str_replace("\r\n",'',$text);
            //nl2br(str_replace('"', '\"', $text))
            $this->log->addLogEntry("Artikkel #".$id." ble oppdatert.", 5);
            return $this->db->update("bib_articles", array("id" => $id, "title" => $title, "text" => str_replace(array("\r\n", "<br />"), array("", "_br_"), addslashes(nl2br($text))), "user_id" => $userid), "id");
        }
        else
        {
            //new article, insert..
            $this->log->addLogEntry($title." ble opprettet!", 5);
            return $this->db->insert("bib_articles", array("title" => $title, "text" => str_replace(array("\r\n", "<br />"), array("", "_br_"), addslashes(nl2br($text))), "user_id" => $userid));
        }
        return false;
    }
    //delete article by ArticleID
    public function delArticle($id)
    {
        if($query = $this->getArticle($id))
        {
            //protected
            if($query['protected'] == 0)
            {
                $this->log->addLogEntry("Artikkel #".$id." ble slettet.", 5);
                return $this->db->query("DELETE FROM bib_articles WHERE id = ".$id." LIMIT 1");
            }
            else
            {
                return null;
            }
        }
        return false;
    }
    public function showArticle($id)
    {
        if($data = $this->getArticle($id))
        {
            
            echo $this->bb2html(stripslashes($data['text'])); 
        }
        return true;
    }
    //get article by articleID
    public function getArticle($id)
    {
        $id = $this->db->real_escape_string($id);
        $query = $this->db->query("SELECT * FROM bib_articles WHERE id = ".$id." LIMIT 1");
        if($query->num_rows == 1)
        {
            return $query->fetch_assoc();
        }
        return false;
    }
    //get all articles in db by ArticleTypeID
    public function getArticles()
    {
        
    }
    public function strip($text)
    {
        return str_replace(array("_br_", '\\'), array("\r\n", ""), stripslashes($text));
    }
    public function toggleProtection($id)
    {
        //if this return true we know theres an article with this id.
        if($data = $this->getArticle($id))
        {
            $protStatus = $data['protected'] == 1 ? 0 : 1;
            if($this->db->update("bib_articles", array('id' => $id, 'protected' => $protStatus), 'id'))
            {
                $status = $protStatus == 1 ? "beskyttet" : "ubeskyttet";
                $this->log->addLogEntry("Artikkel #".$id." ble satt til ".$status, 5);
                return true;
            }
        }
        return false;
    }
    public function bb2html($text)
    {
        $bbcode = array("<", ">",
                    "[list]", "[*]", "[/list]",
                    "[img]", "[/img]",
                    "[b]", "[/b]",
                    "[u]", "[/u]",
                    "[i]", "[/i]",
                    '[color="', "[/color]",
                    '[size="', "[/size]",
                    '[url="', "[/url]",
                    '[mail="', "[/mail]",
                    "[code]", "[/code]",
                    "[quote]", "[/quote]",
                    '"]', "_br_");
      $htmlcode = array("&lt;", "&gt;",
                    "<ul>", "<li>", "</ul>",
                    '<img src="', '">',
                    "<b>", "</b>",
                    "<u>", "</u>",
                    "<i>", "</i>",
                    '<span style="color:', '</span>',
                    '<span style="font-size:', "</span>",
                    '<a href="', "</a>",
                    '<a href="mailto:', "</a>",
                    "<code>", "</code>",
                    '<table bgcolor="lightgray"><tr><td bgcolor="white">', '</td></tr></table>',
                    '">', "<br />");
      $newtext = str_replace($bbcode, $htmlcode, $text);
      $newtext = nl2br($newtext);//second pass
      return $newtext;
    }
}
?>