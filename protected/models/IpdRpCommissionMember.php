<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 02-11-2014
------------------------*/

class IpdRpCommissionMember extends CFormModel
{
    public $_connection;
    public $member_id;
    
    public function __construct()
    {
        $this->_connection = Yii::app()->db;
    }
    
    public function rules()
    {
        return array(
            array('cutoff_id','required'),
        );
    }
    
    public function attributeLabels() {
        return array('cutoff_id'=>'Cut-Off Date');
    }
    
    public function getIpdRpCommission()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    d.distributor_commission_id,
                    d.member_id,
                    CONCAT(m.last_name, ', ', m.first_name, ' ', m.middle_name) AS member_name,
                    d.commission_amount,
                    d.cutoff_id,
                    d.date_created,
                    DATE_FORMAT(d.date_approved,'%M %d, %Y') AS date_approved,
                    CONCAT(md.last_name, ', ', md.first_name, ' ', md.middle_name) AS approved_by,
                    DATE_FORMAT(d.date_claimed,'%M %d, %Y') AS date_claimed,
                    CONCAT(md2.last_name, ', ', md2.first_name, ' ', md2.middle_name) AS claimed_by,
                    d.status
                  FROM distributor_commissions d
                    INNER JOIN member_details m
                      ON d.member_id = m.member_id
                    LEFT OUTER JOIN member_details md
                      ON d.approved_by_id = md.member_id
                    LEFT OUTER JOIN member_details md2
                      ON d.claimed_by_id = md2.member_id
                  WHERE d.member_id = 5 ORDER BY d.date_created DESC;";
        
        $command =  $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        
        return $result;
    }
    
    public function getPayoutTotal()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    sum(u.amount) as total_amount,
                    sum(u.ibo_count) as total_ibo
                  FROM unilevel u
                  WHERE u.cutoff_id = :cutoff_id";
        
        $command =  $conn->createCommand($query);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function getUnilevelDetails()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    u.unilevel_id,
                    u.cutoff_id,
                    u.member_id,
                    CONCAT(md.last_name, ', ', md.first_name, ' ', md.middle_name) AS member_name,
                    u.ibo_count,
                    u.amount,
                    u.date_created,
                    DATE_FORMAT(u.date_approved, '%M %d, %Y') AS date_approved,
                    CONCAT(md1.last_name, ', ', md1.first_name, ' ', md1.middle_name) AS approved_by,
                    DATE_FORMAT(u.date_claimed, '%M %d, %Y') AS date_claimed,
                    CONCAT(md2.last_name, ', ', md2.first_name, ' ', md2.middle_name) AS claimed_by,
                    u.status
                  FROM unilevel u
                    INNER JOIN members m
                      ON u.member_id = m.member_id
                    INNER JOIN member_details md
                      ON u.member_id = md.member_id
                    LEFT OUTER JOIN member_details md1
                      ON u.approved_by_id = md1.member_id
                    LEFT OUTER JOIN member_details md2
                      ON u.claimed_by_id = md2.member_id
                  WHERE u.cutoff_id = :cutoff_id
                  AND u.member_id = :member_id";
        
        $command =  $conn->createCommand($query);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
        $command->bindParam(':member_id', $this->member_id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function updateUnilevelStatus($unilevel_id, $status, $userid)
    {
        $conn = $this->_connection;
        
        $trx = $conn->beginTransaction();
        
        if ($status == 1)
        {
            $query = "UPDATE unilevel
                        SET date_approved = NOW(),
                            status = :status,
                            approved_by_id = :userid
                        WHERE unilevel_id = :unilevel_id;";
        }
        else if ($status == 2)
        {
            $query = "UPDATE unilevel
                        SET date_claimed = NOW(),
                            status = :status,
                            claimed_by_id = :userid
                        WHERE unilevel_id = :unilevel_id;";
        }   
        
        $command = $conn->createCommand($query);
        
        $command->bindParam(':unilevel_id', $unilevel_id);
        $command->bindParam(':status', $status);
        $command->bindParam(':userid', $userid);

        $result = $command->execute();
        
        try
        {
            if(count($result)>0)
            {
                $trx->commit();
                return true;
            }
            else
            {
                $trx->rollback();
                return false;
            }
        }
        catch(PDOException $e)
        {
            $trx->rollback();
            return false;
        }
    }
    
    public function get_running_account()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    ra.member_id,
                    ra.direct_endorse,
                    ra.total_member,
                    ra.date_first_five_completed,
                    ra.with_unilevel_trx,
                    TIMESTAMPDIFF(MONTH,m.date_joined,date_first_five_completed) AS num_of_months
                  FROM running_accounts ra
                    INNER JOIN members m
                      ON ra.member_id = m.member_id
                  WHERE m.member_id = :member_id";
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->upline_id);
        return $command->queryRow();
    }
    
    public function update_transaction()
    {
        $conn = $this->_connection;
        
        $payout_rate = ReferenceModel::get_payout_rate(TransactionTypes::UNILEVEL);
        
        $query = "UPDATE unilevel 
                    SET ibo_count = ibo_count + 1, 
                        amount = amount + :payout_rate,
                        date_last_updated = now()
                  WHERE cutoff_id = :cutoff_id
                    AND member_id = :member_id
                    AND status = 0";
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->upline_id);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
        $command->bindParam(':payout_rate', $payout_rate);
        $result = $command->execute();     
        return $result;
    }
    
    public function insert_first_transaction()
    {
        $conn = $this->_connection;
        
        $payout_rate = ReferenceModel::get_payout_rate(TransactionTypes::UNILEVEL);
        $payout = $this->total_direct_endorse * $payout_rate;
        
        $query = "INSERT INTO unilevel (member_id, cutoff_id, ibo_count, amount)
                   VALUES (:member_id, :cutoff_id, :total_direct_endorse, :payout)";        
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->upline_id);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
        $command->bindParam(':total_direct_endorse', $this->total_direct_endorse);
        $command->bindParam(':payout', $payout);
        $result = $command->execute();        
        
        if(count($result)>0)
        {
            //Update running account
            $query2 = "UPDATE running_accounts
                        SET with_unilevel_trx = 1
                        WHERE member_id = :member_id";
            $command2 = $conn->createCommand($query2);
            $command2->bindParam(':member_id', $this->upline_id);
            $result2 = $command2->execute();
            
            if(count($result2)>0)
                return true;
            else
                return false;
        }
        else
        {
            return false;
        }
        
    }
    
    public function insert_first_transaction_with_flushout()
    {
        $conn = $this->_connection;
        
        $payout_rate = ReferenceModel::get_payout_rate(TransactionTypes::UNILEVEL);
          
        $payout = $this->total_members * $payout_rate;
        
        $query = "INSERT INTO unilevel (member_id, cutoff_id, ibo_count, amount)
                   VALUES (:member_id, :cutoff_id, :total_ibo, :payout_rate)";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->upline_id);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
        $command->bindParam(':total_ibo', $this->total_members);
        $command->bindParam(':payout', $payout);
        $result = $command->execute();        
        
        if(count($result)>0)
        {
            //Update running account
            $query2 = "UPDATE running_accounts
                        SET with_unilevel_trx = 1
                        WHERE member_id = :member_id";
            $command2 = $conn->createCommand($query2);
            $command2->bindParam(':member_id', $this->upline_id);
            $result2 = $command2->execute();
            
            if(count($result2)>0)
                return true;
            else
                return false;
        }
        else
        {
            return false;
        }
        
    }
    
    public function new_transaction()
    {
        $conn = $this->_connection;
        
        $payout_rate = ReferenceModel::get_payout_rate(TransactionTypes::UNILEVEL);
        
        $query = "INSERT INTO unilevel (member_id, cutoff_id, ibo_count, amount)
                   VALUES (:member_id, :cutoff_id, 1, :payout_rate)";
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->upline_id);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
                $command->bindParam(':payout_rate', $payout_rate);
        $result = $command->execute();        
        return $result;
        
    }
    
    public function check_transaction()
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM unilevel
                   WHERE cutoff_id = :cutoff_id
                        AND member_id = :member_id
                        AND status = 0";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':cutoff_id', $this->cutoff_id);
        $command->bindParam(':member_id', $this->upline_id);
        $result = $command->queryAll();
        return $result;
    }
    
    public function is_first_transaction()
    {
        $conn = $this->_connection;
        
        $query = "SELECT count(*) as total FROM unilevel 
                    WHERE member_id = :member_id";
        
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
