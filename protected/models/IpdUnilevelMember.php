<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 02-20-2014
------------------------*/

class IpdUnilevelMember extends CFormModel
{
    public $_connection;
    public $member_id;
    public $endorser_id;
    public $upline_id;
    public $cutoff_id;
    public $total_direct_endorse;
    
    public function __construct()
    {
        $this->_connection = Yii::app()->db;
    }
    
    public function getUnilevel($member_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    u.unilevel_id,
                    u.cutoff_id,
                    u.distributor_id,
                    CONCAT(m.last_name, ', ', m.first_name, ' ', m.middle_name) AS member_name,
                    u.ipd_count,
                    u.amount,
                    DATE_FORMAT(u.date_created, '%M %d, %Y') AS date_created,
                    DATE_FORMAT(u.date_approved,'%M %d, %Y') AS date_approved,
                    CONCAT(md.last_name, ', ', md.first_name, ' ', md.middle_name) AS approved_by,
                    DATE_FORMAT(u.date_claimed,'%M %d, %Y') AS date_claimed,
                    CONCAT(md2.last_name, ', ', md2.first_name, ' ', md2.middle_name) AS claimed_by,
                    u.status
                  FROM distributor_unilevel u
                    INNER JOIN member_details m
                      ON u.distributor_id = m.member_id
                    LEFT OUTER JOIN member_details md
                      ON u.approved_by_id = md.member_id
                    LEFT OUTER JOIN member_details md2
                      ON u.claimed_by_id = md2.member_id
                  WHERE u.distributor_id = :member_id ORDER BY u.date_created DESC;";
        
        $command =  $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        
        return $result;
    }
    public function getUnilevelDetails()
    {
        $conn = $this->_connection;
        $query = "SELECT
                    u.unilevel_id,
                    u.cutoff_id,
                    u.distributor_id,
                    CONCAT(md.last_name, ', ', md.first_name, ' ', md.middle_name) AS member_name,
                    u.ipd_count,
                    u.amount,
                    u.date_created,
                    DATE_FORMAT(u.date_approved, '%M %d, %Y') AS date_approved,
                    CONCAT(md1.last_name, ', ', md1.first_name, ' ', md1.middle_name) AS approved_by,
                    DATE_FORMAT(u.date_claimed, '%M %d, %Y') AS date_claimed,
                    CONCAT(md2.last_name, ', ', md2.first_name, ' ', md2.middle_name) AS claimed_by,
                    u.status
                  FROM distributor_unilevel u
                    INNER JOIN members m
                      ON u.distributor_id = m.member_id
                    INNER JOIN member_details md
                      ON u.distributor_id = md.member_id
                    LEFT OUTER JOIN member_details md1
                      ON u.approved_by_id = md1.member_id
                    LEFT OUTER JOIN member_details md2
                      ON u.claimed_by_id = md2.member_id
                  WHERE u.cutoff_id = :cutoff_id
                  AND u.distributor_id = :member_id";
        $command =  $conn->createCommand($query);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
        $command->bindParam(':member_id', $this->member_id);
        $result = $command->queryRow();
        return $result;
    }
    public function is_first_transaction()
    {
        $conn = $this->_connection;
        $query = "SELECT count(*) as total FROM distributor_unilevel 
                    WHERE distributor_id = :member_id";
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        $result = $command->queryRow();
        $trx_count = $result['total'];
        if($trx_count == 1)
            return true;
        else
            return false; 
    }
}
?>
