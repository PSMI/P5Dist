<?php

/**
 * @author owliber
 * @date Oct 2, 2012
 * @filename AuditLog.php
 * 
 */

class AuditLog extends CFormModel
{
    public $member_id;
    public $log_message;
    public $status;
    public $job_id;
    public $audit_func_id;
    public $remote_ip;
    public $_connection;
        
    public function __construct() {
        $this->_connection = Yii::app()->db;
    }
    
    public static function log_event()
    {
        $conn = Yii::app()->db;
            
        $this->remote_ip = $_SERVER['REMOTE_ADDR'];
                
        $this->member_id = Yii::app()->user->getId();
        
        $query = "INSERT INTO audit_logs (member_id,audit_function_id,details,remote_ip)
                  VALUE (:member_id,:audit_function_id,:details,:remote_ip)";

        $sql = $conn->createCommand($query);  
        $sql->bindValues(array(
                    ":member_id"=>$this->member_id,
                    ":audit_function_id"=>$this->audit_func_id,
                    ":details"=>$this->log_message,
                    ":remote_ip"=>$this->remote_ip,
        ));
        $sql->execute();
       
    }
        
    public function log_cron()
    {
        $conn = Yii::app()->db;
        $query = "INSERT INTO cronlogs (job_id, log_message, status) 
                    VALUES (:job_id, :log_message, :status)";
        
        if(isset($this->status)) $this->status = 1;
        
        $sql = $conn->createCommand($query);
        $sql->bindValue(":job_id", $this->job_id);
        $sql->bindValue(":log_message", $this->log_message);
        $sql->bindValue(":status", $this->status);
        $sql->execute();
        
    }
    
}
?>
