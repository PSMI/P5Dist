<?php

/**
 * @author : owliber
 * @date : 2014-01-14
 */

class Distributors extends CActiveRecord
{
    public $member_id;
    public $address1;
    public $mobile_no;
    public $telephone_no;
    public $email;
    public $spouse_contact_no;
    public $beneficiary_name;
    public $relationship;
    public $tin_no;
    public $Password;
    
    public function rules()
    {
        return array(
                array('email, address1, mobile_no, beneficiary_name', 'required'),
                array('email', 'email'),
                array('spouse_contact_no, telephone_no, tin_no, relationship, member_id', 'safe')
            );
    }
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
            return 'members';
    }
    
    // perform one-way encryption on the password before we store it in the database
    protected function afterValidate()
    {
        parent::afterValidate();
        $this->Password = $this->hashPassword($this->Password);
    }
    
    public function getUserStatus($username)
    {
        $query = "SELECT status FROM members 
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
        $query = "SELECT * FROM members
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
                    FROM member_details
                    WHERE member_id = :member_id";
        $command = Yii::app()->db->createCommand($query);
        $command->bindParam(':member_id', $id);
        $result = $command->queryRow();
        return $result['distributor_name'];
    }
        
    public function getAccountType($username)
    {
        $query = "SELECT account_type_id FROM members 
                  WHERE username = :username";
        $sql = Yii::app()->db->createCommand($query);
        $sql->bindParam(":username",$username);
        $result = $sql->queryRow();
        
        if(count($result)> 0)
        {
            return $result['account_type_id'];
        }
    }
    
    public function selectDistributorNameById($id)
    {        
        $sql = "SELECT a.member_id, a.status, b.last_name, b.middle_name, b.first_name
                FROM members a 
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.member_id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":id", $id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function getProfileInfo($id)
    {        
        $sql = "SELECT a.member_id, a.date_created, a.username, a.password, b.last_name, b.first_name, b.middle_name, 
                CASE b.gender WHEN 1 THEN 'Male' WHEN 2 THEN 'Female' END AS gender,
                CASE b.civil_status WHEN 1 THEN 'Single' WHEN 2 THEN 'Married' WHEN 3 THEN 'Divorced'
                WHEN 4 THEN 'Separated' WHEN 5 THEN 'Widow' END AS civil_status,
                b.birth_date, b.spouse_name, b.spouse_contact_no, b.beneficiary_name,
                b.company, b.tin_no, b.email, b.address1, b.telephone_no, b.mobile_no, b.occupation,
                b.relationship, a.endorser_id, a.ipd_endorser_id
                FROM members a
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.member_id = :member_id AND a.account_type_id = 5";
        
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':member_id', $id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function changePassword($id, $new_pass)
    {
        $connection = Yii::app()->db;
        
        $beginTrans = $connection->beginTransaction();
        
        try
        {
            $sql = "UPDATE members SET password = :password
                    WHERE member_id = :member_id";
            $command = $connection->createCommand($sql);
            
            $hashedPassword = md5($new_pass);
            
            $command->bindValue(':member_id', $id);
            $command->bindValue(':password', $hashedPassword);
            $rowCount = $command->execute();
            
            if ($rowCount > 0) {
                    $beginTrans->commit();
                    return true;
            } else {
                $beginTrans->rollback();  
                return false;
            }
        }
        catch (CDbException $e)
        {
            $beginTrans->rollback();  
            return false;
        }
    }
    
    public function getContactInfo($id)
    {
        $connection = Yii::app()->db;
        
        $sql = "SELECT a.member_id, b.spouse_contact_no, b.email, b.address1, b.telephone_no, b.mobile_no,
                b.tin_no, b.relationship, b.beneficiary_name
                FROM members a
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.member_id = :member_id";
        
        $command = $connection->createCommand($sql);
        $command->bindParam(':member_id', $id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    
    public function updateContactInfo()
    {
        $connection = Yii::app()->db;
        $beginTrans = $connection->beginTransaction();
        
        try
        {
            $sql = "UPDATE member_details SET email = :email, spouse_contact_no = :spouse_contact_no,
                    address1 = :address1, telephone_no = :telephone_no, mobile_no = :mobile_no,
                    beneficiary_name = :beneficiary_name, relationship = :relationship, tin_no = :tin_no
                    WHERE member_id = :member_id";
            $command = $connection->createCommand($sql);
            $command->bindValue(':member_id', $this->member_id);
            $command->bindValue(':email', $this->email);
            $command->bindValue(':address1', $this->address1);
            $command->bindValue(':telephone_no', $this->telephone_no);
            $command->bindValue(':mobile_no', $this->mobile_no);
            $command->bindValue(':spouse_contact_no', $this->spouse_contact_no);
            $command->bindValue(':beneficiary_name', $this->beneficiary_name);
            $command->bindValue(':relationship', $this->relationship);
            $command->bindValue(':tin_no', $this->tin_no);
            $rowCount = $command->execute();
            
            if ($rowCount > 0) {
                    $beginTrans->commit();
                    return true;
            } else {
                $beginTrans->rollback();  
                return false;
            }
        }
        catch (CDbException $e)
        {
            $beginTrans->rollback();  
            return false;
        }
    }
    
}
?>
