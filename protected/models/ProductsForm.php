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
    
    public function selectProductByPackageType($package_type_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM products a 
                INNER JOIN product_groups b ON a.product_group_id = b.product_group_id
                WHERE b.product_group_id = :product_group_id AND a.status = 1";
        $command = $conn->createCommand($query);
        $command->bindParam(':product_group_id', $package_type_id);
        $result = $command->queryAll();
        
        return $result;
    }
}
?>
