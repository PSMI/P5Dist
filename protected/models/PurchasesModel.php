<?php

/*
 * @author : owliber
 * @date : 2014-02-03
 */

class PurchasesModel extends CFormModel
{
    public $_connection;
    public $autocomplete_name;
    public $distributor_id;
    public $product_id;
    public $product_code;
    public $product_name;
    public $quantity;
    public $payment_type_id;
        
    public function __construct() {
        $this->_connection = Yii::app()->db;
    }
    
    public function rules()
    {
        return array(
            array('autocomplete_name','required'),
            array('distributor_id','safe'),
        );
    }
    public function insertPurchased($product)
    {
        $conn = $this->_connection;
        
        $member_id = $product['member_id'];
        $product_code = $product['product_code'];
        //$product_name = $product['product_name'];
        $date_purchase = $product['date_purchased'];
        $payment_mode = $product['payment_mode_id'];
        
        /* Insert purchased products */
        $query = "INSERT INTO purchases (member_id, product_id, date_purchased, payment_type_id)
                    VALUES (:member_id, :product_code, :date_purchased, :payment_mode_id)";

        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $command->bindParam(':product_code', $product_code);
        //$command->bindParam(':product_name', $product_name);
        $command->bindParam(':date_purchased', $date_purchase);
        $command->bindParam(':payment_mode_id', $payment_mode);
        $result = $command->execute();
        return $result;
    }
    public function insertIPDPurchased($product)
    {
        $conn = $this->_connection;
        $distributor_id = $product['member_id'];
        $product_id = $product['product_id'];
        $srp = $product['srp'];
        $date_purchase = $product['date_purchased'];
        $payment_mode = $product['payment_mode_id'];
        /* Insert purchased products */
        $query = "INSERT INTO distributor_purchased_items (distributor_id, product_id, srp, date_purchased, quantity, payment_type_id, status)
                    VALUES (:distributor_id, :product_id, :srp, :date_purchased, 1, :payment_mode_id, 1)";
        $command = $conn->createCommand($query);
        $command->bindParam(':distributor_id', $distributor_id);
        $command->bindParam(':product_id', $product_id);
        $command->bindParam(':srp', $srp);
        $command->bindParam(':date_purchased', $date_purchase);
        $command->bindParam(':payment_mode_id', $payment_mode);

        $result = $command->execute();
        
        return $result;
    }
    public function selectAll()
    {
        $conn = $this->_connection;
        $query = "SELECT p.product_code,
                         p.product_name,
                         pi.srp,
                         pi.quantity,
                         date_format(pi.date_purchased,'%b %d, %Y') AS date_purchased,
                         format((pi.srp * pi.quantity),2) AS total
                    FROM distributor_purchased_items pi
                    INNER JOIN products p ON pi.product_id = p.product_id";
        $command = $conn->createCommand($query);
        $result = $command->queryAll();
        return $result;
    }
    public function add_purchased_item()
    {
        $conn = $this->_connection;
        $trx = $conn->beginTransaction();
        $model = new ProductsForm();
        $product = $model->selectProductById($this->product_id);
        $srp = $product['srp'];
        $query = "INSERT INTO distributor_purchased_items (distributor_id, product_id, srp, date_purchased, quantity, payment_type_id)
                    VALUES (:distributor_id, :product_id, :srp, now(), :quantity, :payment_type_id)";
        $command = $conn->createCommand($query);
        $command->bindParam(':distributor_id', $this->distributor_id);
        $command->bindParam(':product_id', $this->product_id);
        $command->bindParam(':srp', $srp);
        $command->bindParam(':quantity', $this->quantity);
        $command->bindParam(':payment_type_id', $this->payment_type_id);
        $command->execute();
        try
        {
            $trx->commit();
        }
        catch(PDOException $e)
        {
            $trx->rollback();
        }
    }
    
    /**
     * @author Noel Antonio
     * @date 04-12-2014
     */
    public function insertPurchasedItem($params)
    {
        $conn = $this->_connection;
        
        $member_id = $params['member_id'];
        $product_id = $params['product_code'];
        // $date_purchase = $params['date_purchased'];
        $payment_mode = $params['payment_mode_id'];
        
        $product_info = ProductsForm::selectProductById($product_id);
        $product_amount = $product_info['amount'];
        
        /* Insert purchased summary */
        $query = "INSERT INTO purchased_summary (member_id, quantity, total, payment_type_id, status)
                VALUES (:member_id, 1, :total, :payment_mode_id, 1)";
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $command->bindParam(':total', $product_amount);
        $command->bindParam(':payment_mode_id', $payment_mode);
        $result = $command->execute();
            
        try
        {
            if ($result > 0)
            {
                $last_inserted_id = $conn->getLastInsertID();
                
                /* Insert purchased items */
                $query2 = "INSERT INTO purchased_items (purchase_summary_id, product_id, quantity, total)
                    VALUES (:purchased_summary_id, :product_id, 1, :total)";
                $command2 = $conn->createCommand($query2);
                $command2->bindParam(':purchased_summary_id', $last_inserted_id);
                $command2->bindParam(':product_id', $product_id);
                $command2->bindParam(':total', $product_amount);
                $result2 = $command2->execute();
                
                if ($result2 > 0)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        catch (PDOException $e)
        {
            return false;
        }
    }
}
?>
