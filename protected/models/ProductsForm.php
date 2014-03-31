<?php

/**
 * @author Noel Antonio
 * @date 03-26-2014
 */

class ProductsForm extends CFormModel
{
    public $_connection;
    
    public function __construct() {
        $this->_connection = Yii::app()->db;
    }
    
    public function selectAll()
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM products WHERE status = 1";
        $command = $conn->createCommand($query);
        $result = $command->queryAll();
        
        return $result;
    }
    
    public function selectProductById($product_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM products WHERE product_id = :product_id AND status = 1";
        $command = $conn->createCommand($query);
        $command->bindParam(':product_id', $product_id);
        $result = $command->queryRow();
        
        return $result;
    }
}
?>
