<?php

/*
 * @author : owliber
 * @date : 2014-01-14
 */

class Distributors extends CActiveRecord
{
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
            return 'distributors';
    }
    
    //perform one-way encryption on the password before we store it in the database
    protected function afterValidate()
    {
        parent::afterValidate();
        $this->Password = $this->hashPassword($this->Password);
    }
    
    public function getUserStatus($username)
    {
        $query = "SELECT status FROM distributors 
                  WHERE username = :username";
        $sql = Yii::app()->db->createCommand($query);
        $sql->bindParam(":username",$username);
        $result = $sql->queryRow();
        
        if(count($result)> 0)
        {
            return $result['status'];
        }
    }
    
    public function hashPassword($value)
    {
        return md5($value);
    }
    
    public static function checkUsername($username)
    {
        $query = "SELECT * FROM distributors
                    WHERE username = :username";
        
        $sql = Yii::app()->db->createCommand($query);
        $sql->bindParam(":username",$username);
        $result = $sql->queryAll();
        
        if(count($result)> 0)
            return true;
        else
            return false;
    }
    
    public function getDistributorName($id)
    {
        $query = "SELECT CONCAT(last_name, ' ', first_name) as distributor_name 
                    FROM distributor_details
                    WHERE distributor_id = :distributor_id";
        $command = Yii::app()->db->createCommand($query);
        $command->bindParam(':distributor_id', $id);
        $result = $command->queryRow();
        return $result['distributor_name'];
    }
        
    public function getAccountType($username)
    {
        $query = "SELECT account_type_id FROM distributors 
                  WHERE username = :username";
        $sql = Yii::app()->db->createCommand($query);
        $sql->bindParam(":username",$username);
        $result = $sql->queryRow();
        
        if(count($result)> 0)
        {
            return $result['account_type_id'];
        }
    }
    
}
?>
