<?php

/*
 * @author : owliber
 * @date : 2014-04-07
 */

class RetentionMoney extends CFormModel
{
    public $_connection;
    public $distributor_id;
    
    public function __construct()
    {
        $this->_connection = Yii::app()->db;
    }
    
    public function getSavings()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    ps.purchase_summary_id,
                    CONCAT(md.last_name, ', ', md.first_name, ' ', md.middle_name) AS member_name,
                    ps.receipt_no,
                    ps.quantity,
                    ps.total,
                    ps.savings,
                    pt.payment_type_name,
                    DATE_FORMAT(ps.date_purchased,'%M %d, %Y') AS date_purchased,
                    ps.status
                  FROM purchased_summary ps
                    INNER JOIN member_details md
                      ON ps.member_id = md.member_id
                    LEFT OUTER JOIN ref_paymenttypes pt
                      ON ps.payment_type_id = pt.payment_type_id
                  WHERE ps.member_id = :distributor_id
                  AND ps.status = 1
                  ORDER BY ps.date_purchased DESC;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':distributor_id', $this->distributor_id);
        
        return $command->queryAll();
    }
    
    public function getTotals()
    {
        $conn = $this->_connection;
        
        $query = "SELECT sum(ps.quantity) AS total_quantity,
                         sum(ps.total) AS total_amount,
                         sum(ps.savings) AS total_savings
                    FROM purchased_summary ps
                    WHERE ps.member_id = :distributor_id
                        AND ps.status = 1
                   GROUP BY ps.member_id;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':distributor_id', $this->distributor_id);
        
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
