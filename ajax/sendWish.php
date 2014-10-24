<?php
if(isset($_GET['name']) && isset($_GET['msg']))
{
    require_once('../inc/mysql.class.php');
    require_once('../inc/log.class.php');
    
    //database class
    $db = new MySQL("mysql.nith.no", "larmar10", "larmar10", "larmar10"); //default db login information
    //log
    $log = new Log($db);
    
    $headers = "From: BibAdmin \n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\n\n";
    
    if(mail("marius_larsen_asp@hotmail.com", utf8_decode("Ønske fra ".$_GET['name']), $_GET['msg'], $headers))
    {
        $log->addLogEntry($_SERVER['REMOTE_ADDR']." har sendt et ønske.", 6);
    }
}
?>