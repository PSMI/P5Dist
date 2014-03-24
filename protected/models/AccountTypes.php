<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AccountTypes extends CFormModel
{
    public $_connection;
    
    public function __construct() {
        $this->_connection = Yii::app()->db;
    }
    
    public function selectAllAccountTypes()
    {
        $connection = $this->_connection;
        
        $sql = "SELECT * FROM ref_account_types WHERE account_type_id != 3 AND account_type_id != 1;";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        
        return $result;
    }
}
?>
