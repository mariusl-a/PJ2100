<?php
require_once('log.class.php');
class Journals
{
    private $db;
    public function __construct(MySQL $db)
    {
        $this->db = $db;
    }
    public function getJournalTypeById($type_id)
    {
        $query = $this->db->query("SELECT * FROM bib_journal_types WHERE id = ".$this->db->real_escape_string($type_id)." LIMIT 1");
        //if num_rows is 1 it means there is a type with the type_id given.
        if($query->num_rows == 1)
        {
            return $query; //return the query data..
        }
        return false;
    }
    public function getJournals($type = 0)
    {
        if($TypeData = $this->getJournalTypeById($type))
        {
            $Journals = $this->getJournalsByType($type);
            return $Journals;
        }
        
        return false;
    }
    private function getJournalsByType($type)
    {
        if($type == 0)
        {
            $SQL = "SELECT edition FROM bib_journals";
        }
        else
        {
            $SQL = "SELECT edition FROM bib_journals WHERE journal_type = ".$this->db->real_escape_string($type);    
        }
        $query = $this->db->query($SQL);
        if($query->num_rows > 0)
        {
            return $query; //return query with all rows. Can use $query->fetch_assoc()
        }
        return false;
    }
    public function addJournal($type_id, $edition)
    {
        //need to check if theres a edition with the same journal_type and edition
        $query = $this->db->query("SELECT * FROM bib_journals WHERE journal_type = ".$this->db->real_escape_string($type_id)." AND edition = ".$this->db->real_escape_string($edition));
        if($query->num_rows == 0)
        {
            //if theres none, we can add it as a new journal..
            if($this->db->insert("bib_journals", array('journal_type' => $type_id, 'edition' => $edition)))
            {
                return true;
            }
        }
        return false;
    }
    public function editJournal($type_id, $title, $description)
    {
        //check if the type_id exists by using the getJournalTypeById() method
        if($query = $this->getJournalTypeById($type_id))
        {
            if($this->db->update("bib_journal_types", array('id' => $type_id, 'title' => $title, 'description' => $description)))
            {
                return true;
            }
        }
        return false;
    }
}
?>