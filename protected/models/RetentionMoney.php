<?php

/*
 * @author : owliber
 * @date : 2014-04-07
 */

class RetentionMoney extends CFormModel
{
    public $_connection;
    public $member_id;
    
    public function __construct()
    {
        $this->_connection = Yii::app()->db;
    }
    
    public function getSavings()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    DATE_FORMAT( dr.date_created, '%b %d, %Y') AS transaction_date,
                    dr.purchase_retention,
                    dr.other_retention,
                    (dr.purchase_retention + dr.other_retention) as total_retention,
                    CASE dr.status WHEN 0 THEN 'Pending' WHEN 1 THEN 'Withdrawn' END status
                  FROM distributor_retentions dr
                    INNER JOIN member_details md ON dr.member_id = md.member_id
                  WHERE dr.member_id = :member_id
                  ORDER BY dr.date_created DESC;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        return $command->queryAll();
    }
    
    public function getTotals()
    {
        $conn = $this->_connection;
        
        $query = "SELECT sum(dr.purchase_retention) AS total_purchase_retention,
                         sum(dr.other_retention) AS total_other_retention,
                         (sum(dr.purchase_retention) + sum(dr.other_retention)) as total_retention
                    FROM distributor_retentions dr
                  WHERE dr.member_id = :member_id  
                   GROUP BY dr.member_id;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        return $command->queryRow();
    }
    public function getMemberName($member_id)
    {
        $conn = $this->_connection;
        $query = "SELECT
                    CONCAT(md.last_name, ', ', md.first_name, ' ', md.middle_name) AS member_name
                  FROM members m
                    INNER JOIN member_details md
                        ON m.member_id = md.member_id
                  WHERE m.member_id = :member_id;";
        $command =  $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        return $result;
    }
}
?>
