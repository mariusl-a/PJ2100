<?php
class Games
{
    private $db;
    private $log;
    public $error = "";
    public function __construct(MySQL $db, Log $log)
    {
        $this->db = $db;
        $this->log = $log;
    }
    //add a new console on a existing console type
    public function addConsole($type)
    {
        if($this->db->insert('bib_consoles', array('type_id' => $type)))
        {
            $this->log->addLogEntry("En ny konsoll ble lagt til!", 1);
            return true;
        }
        return false;
    }
    //delete a console (broken/removed)
    public function deleteConsole($id)
    {
        if($this->db->query("DELETE FROM `bib_consoles` WHERE id = ".$id))
        {
            $this->log->addLogEntry("Konsoll #".$id." ble slettet.".$status, 1);
            return true;
        }
        return false;
    }
    //add a new type of console (xbox 720?)
    public function addConsoleType($name, $shortname)
    {
        if($this->db->insert('bib_console_types', array('name' => $name, 'shortname' => $shortname)))
        {
            $this->log->addLogEntry("En ny konsoll type ble lagt til!", 1);
            return true;
        }
        return false;
    }
    public function consoleAvailableToggle($id)
    {
        //check if the console id is in the db
        $query = $this->db->query("SELECT available,name,shortname FROM `bib_consoles`,`bib_console_types` WHERE  `bib_consoles`.`type_id` = `bib_console_types`.`id` AND `bib_consoles`.`id` = ".$id." LIMIT 1");
        if($query->num_rows == 1)
        {
            //there is a console with this id, get SQL data as consoleData
            $consoleData = $query->fetch_assoc(); //to get the currently availability status
            
            $availabilty = $consoleData['available'] == 1 ? 0 : 1;
            $status = $consoleData['available'] == 1 ? "er nå ikke tilgjengelig." : " er nå satt til tilgjengelig.";
            //update bib_games set correct availability status
            if($this->db->update('bib_consoles', array('id' => $id, 'available' => $availabilty), 'id'))
            {
                //return true if it was a successfull query
                $this->log->addLogEntry($consoleData['name']." #".$id." ".$status);
                return true;
            }
        }
        return false;
    }
    public function updateGame($title, $description, $tags, $game_type)
    {
        $query = $this->db->query("SELECT * FROM `bib_game_types` WHERE `id` = ".$game_type." LIMIT 1");
        if($query->num_rows == 1)
        {
            if($this->db->update('bib_game_types', array('id' => $game_type, 'title' => $title, 'description' => $description, 'tags' => $tags), 'id'))
            {
                return true;
            }
        }
        
        return false;
    }
    public function addGame($title, $description, $console_type, $tags, $imagePath, $category, $available)
    {
        //check if the game title is already in database..
        $query = $this->db->query("SELECT id FROM bib_game_types WHERE title = '".$this->db->real_escape_string($title)."'");
        if($query->num_rows == 0)
        {
            //none found, new game..
            if($this->db->insert("bib_game_types", array('title' => $title, 'description' => $description, 'category' => $category, 'image' => $imagePath, 'tags' => $tags)))
            {
                $game_typeQuery = $this->db->query("SELECT id FROM `bib_game_types` WHERE `title` = '".$this->db->real_escape_string($title)."' LIMIT 1");
                $typeData = $game_typeQuery->fetch_assoc();
                if($this->db->insert('bib_games', array('game_type' => $typeData['id'], 'console_type' => $console_type, 'game_type' => $typeData['id'], 'added_at' => $this->getTime())))
                {
                    $this->log->addLogEntry(trim($gameData['title'])." ble lagt til!".$status, 1);
                    return true;
                }
                else
                {
                    $this->error = "SQL Failure.";
                    return false;
                }
            }
            else
            {
                $this->error = "SQL Failure.";
                return false;
            }
        }
        else
        {
            //already have this game added, we only need to update current game type and add the game on the list (maybe different console?)
            $typeData = $query->fetch_assoc();
            if($this->db->insert('bib_games', array('console_type' => $console_type, 'game_type' => $typeData['id'], 'added_at' => $this->getTime())))
            {
                return true;
            }
            else
            {
                $this->error = "SQL Failure.";
                return false;    
            }
        }
        return false;
    }
    public function removeGame($game_id, $type = 1)
    {
        $gameData = $this->getGameData($game_id);
        if($gameData !== null)
        {
            //valid game, we can try and delete it
            if($db->query("DELETE FROM `".$this->getTable($type)."` WHERE `id` = ".$game_id." LIMIT 1"))
            {
                $this->log->addLogEntry($gameData['title']." har blitt slettet fra systemet", $gameData['type']);
                return true; //game successfully deleted from db
            }
        }
        return false;
    }
    public function getGameData($game_id, $type = 1)
    {
        //check if the game is in the db
        $query = $this->db->query("SELECT * FROM `bib_games`,`bib_game_types` WHERE `bib_games`.`game_type` = `bib_game_types`.`id` AND `bib_games`.`id` = ".$this->db->real_escape_string($game_id)." LIMIT 1");
        if($query->num_rows == 1)
        {
            //there is a game with this id, get SQL data as gameData
            $gameData = $query->fetch_assoc(); //to get the currently availability status
            return $gameData;
        }
        return null;
    }
    private function getTable($type)
    {
        switch($type)
        {
            case 1:
                return "bib_games";
            case 2:
                return "bib_boardgames";
            default:
                return "bib_games";
         }       
    }
    //QR code function
    public function changeAvailableById($id, $type = 1)
    {
        //check if the game is in the db
        //$query = $this->db->query("SELECT available,title,type FROM `".$this->getTable($type)."` WHERE `id` = ".$id." LIMIT 1");
        if($gameData = $this->getGameData($id))
        {
            //there is a game with this id, get SQL data as gameData
            //$query->fetch_assoc(); //to get the currently availability status
            
            $availabilty = $gameData['available'] == 1 ? 0 : 1;
            //update bib_games set correct availability status
            if($this->db->update($this->getTable($type), array('id' => $id, 'available' => $availabilty), 'id'))
            {
                //return true if it was a successfull query
                $status = $availabilty == 1 ? "ble satt til tilgjengelig" : "ble satt til utilgjengelig";
                $this->log->addLogEntry(trim($gameData['title'])." ".$status, 1);
                return $gameData;
            }
        }
        return false;
    }
    public function getGames()
    {
        $query = $this->db->query("SELECT bib_games.id,available,last_changed,title,description,image,name as console,shortname FROM bib_games,bib_game_types,bib_console_types WHERE bib_games.game_type = bib_game_types.id AND bib_games.console_type = bib_console_types.id ORDER BY title ASC");
        return $query;
    }
    public function getNumGames()
    {
        $query = $this->db->query("SELECT count(*) as Antall FROM `bib_games`");
        $data = $query->fetch_assoc();
        return $data['Antall'];
    }
    private function getTime()
    {
        $sqltime = date("Y-m-d H:i:s", time());
        return $sqltime;
    }
    public function getTvStatus($id)
    {
        $query = $this->db->query("SELECT available FROM bib_TVs WHERE `id` = ".$this->db->real_escape_string($id)." LIMIT 1");
        if($query->num_rows == 1)
        {
            $data = $query->fetch_assoc();
            return $data['available']; 
        }
        return false;
    }
}
?>