<?php
class Log
{
    private $db;
    
    public function __construct(MySQL $db)
    {
        $this->db = $db;
    }
    private function getTime()
    {
        $sqltime = date("Y-m-d H:i:s", time());
        return $sqltime;
    }
    //adds a log entry with data and type
    public function addLogEntry($text, $type = 1)
    {
        if($this->db->insert("bib_sys_log", array("text" => $text, "created_at" => $this->getTime(), "type" => $type)))
        {
            return true;
        }
        return false;
    }
    
    //get log by type
    public function getLogsByType($type)
    {
        if(is_numeric($type))
        {
            $query = $this->db->query("SELECT * FROM `bib_sys_log`   WHERE `type` = ".$type." ORDER BY `id`");
            if($query->num_rows > 0)    
            {
                //we have rows by this type.
                while($row = $query->fetch_assoc())
                {
                    echo $row['created_at']." - ".$row['text']." (".$row['type'].")<br/>\n";
                }
                return true;
                //return $query->fetch_array();
            }
        }
        return false;
    }
    
    //get all log entries
    public function getLog()
    {
        $query = $this->db->query("SELECT * FROM bib_sys_log,bib_sys_logTypes WHERE `bib_sys_log`.`type` = `bib_sys_logTypes`.`id` ORDER BY created_at DESC");
        if($query->num_rows > 0)
        {
            //we have rows by this type.
            /*/
            while($row = $query->fetch_assoc())
            {
                echo $row['created_at']." - ".$row['text']." (".$row['name'].")<br/>\n";
            }
            /*/
            return $query;
        }
    }
}
?>