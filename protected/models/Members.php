<?php

/**
 * @author Noel Antonio
 * @date 03-28-2014
 */

class Members extends CFormModel
{
    public $_connection;
    
    
    public function __construct() {
        $this->_connection = Yii::app()->db;
    }
    
    public function rules()
    {
        return array(
                array('member_id, status, username, password, account_type_id', 'required'),
            );
    }
    
    public function selectMemberName($id)
    {
        $connection = $this->_connection;
        
        $sql = "SELECT a.member_id, a.status, b.last_name, b.middle_name, b.first_name
                FROM members a 
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.member_id = :member_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":member_id", $id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function selectMemberDetails($id)
    {
        $connection = $this->_connection;
        
        $sql = "SELECT *
                FROM members a 
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.member_id = :member_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":member_id", $id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public static function checkUsername($username)
    {
        $query = "SELECT * FROM members
                    WHERE username = :username";
        
        $sql = Yii::app()->db->createCommand($query);
        $sql->bindParam(":username",$username);
        $result = $sql->queryAll();
        
        if(count($result)> 0)
            return true;
        else
            return false;
    }
}
?>
