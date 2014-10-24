<?php
require_once('log.class.php');
class Users
{
    private $db;
    private $log;
    private $tableName = "bib_users";
    
    public $Data;
    public $error = null;
    
    public function __construct(MySQL $db)
    {
        $this->db = $db;
        $this->log = new Log($this->db);
    }
    private function pwd($pass)
    {
        return md5("t5151g-asya_)=25!".$pass."315-_adf=");
    }
    public function NewUser($username, $password, $email, $name)
    {
        //check if username exists (if its taken or not)
        $checkQuery = $this->db->query("SELECT id FROM `".$this->tableName."` WHERE `username` = '".$username."'");
        if($checkQuery->num_rows == 0)
        {
            //username is not already in use, so we can add the new user..
            if($this->db->insert($this->tableName, array('username' => $username, "password" => $this->pwd($password), "email" => $email, "name" => $name)))
            {
                return true;
            }
            $this->error = "SQL feil, kontakt administrator.";
        }
        else
        {
            $this->error = "Brukernavnet er allerede i bruk.";
        }
        return false;
    }
    public function UserData($session)
    {
        $query = $this->db->query("SELECT id,username FROM `".$this->tableName."` WHERE `login_session` = '".$session."'");
        if($query->num_rows == 1)
        {
            setCookie("bib_sess", $session, time()+3600);
            $this->Data = $query->fetch_assoc();
            $this->db->update('bib_users', array('id' => $this->Data['id'], 'last_seen' => $this->getTime()), 'id');
            return true;
        } 
        return false;
    }
    public function getUserById($id)
    {
        $query = $this->db->query("SELECT username FROM bib_users WHERE id = ".$id." LIMIT 1");
        if($query->num_rows == 1)
        {
            $data = $query->fetch_assoc();
            return $data['username'];
        }
        return false;
    }
    public function LoginUser($username, $password)
    {
        $query = $this->db->query("SELECT username,id FROM `".$this->tableName."` WHERE `password` = '".$this->pwd($password)."' AND `username` = '".$this->db->real_escape_string($username)."' LIMIT 1");
        if($query->num_rows == 1)
        {
            $data = $query->fetch_assoc();
            $session = md5("1356".time()."sagd_-5hj_=65)i".$data['username']."yteg087asdfqdf".time());
            if($this->db->update($this->tableName, array('id' => $data['id'], 'login_session' => $session), 'id'))
            {
                setCookie("bib_sess", $session, time()+3600);
                $this->UserData($session);
                $this->log->addLogEntry($this->Data['username']." logget inn.", 3);
                return true;
            }
            $this->error = "SQL feil.";
        }
        else
        {
            $this->error = "Feil brukernavn eller passord!";
        }
        return false;
    }
    private function getTime()
    {
        $sqltime = date("Y-m-d H:i:s", time());
        return $sqltime;
    }
}
?>