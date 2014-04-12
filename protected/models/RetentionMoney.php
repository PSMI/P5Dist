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
                    p.product_name,
                    pi.quantity,
                    pi.srp,
                    pi.discount,
                    pi.net_price,
                    pi.total,
                    pi.savings,
                    DATE_FORMAT(pi.date_created,'%M %d, %Y') AS date_created
                  FROM purchased_items pi
                    INNER JOIN purchased_summary ps
                      ON pi.purchase_summary_id = ps.purchase_summary_id
                    INNER JOIN products p
                      ON pi.product_id = p.product_id
                  WHERE ps.member_id = :member_id
                    AND ps.status = 1
                  ORDER BY ps.date_purchased DESC;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        return $command->queryAll();
    }
    
    public function getTotals()
    {
        $conn = $this->_connection;
        
        $query = "SELECT sum(ps.quantity) AS total_quantity,
                         sum(ps.total) AS total_amount,
                         sum(ps.savings) AS total_savings
                    FROM purchased_summary ps
                  WHERE ps.member_id = :member_id 
                    AND ps.status = 1 
                   GROUP BY ps.member_id;";
        
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
