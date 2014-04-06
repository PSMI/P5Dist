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
        
        $query = "SELECT * FROM distributor_purchased_items dpi
                        INNER JOIN products p ON dpi.product_id = p.product_id
                    WHERE dpi.distributor_id = :distributor_id
                        AND dpi.status = 1;";
        $command = $conn->createCommand($query);
        $command->bindParam(':distributor_id', $this->distributor_id);
        return $command->queryAll();
    }
    
    public function getTotals()
    {
        $conn = $this->_connection;
        
        $query = "SELECT sum(dpi.quantity) AS total_quantity,
                         sum(dpi.total) AS total_amount,
                         sum(dpi.savings) AS total_savings
                    FROM distributor_purchased_items dpi
                    WHERE dpi.distributor_id = :distributor_id
                        AND dpi.status = 1
                   GROUP BY dpi.distributor_id;";
        $command = $conn->createCommand($query);
        $command->bindParam(':distributor_id', $this->distributor_id);
        return $command->queryRow();
    }
}
?>
