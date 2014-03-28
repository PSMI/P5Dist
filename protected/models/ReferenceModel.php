<?php

/**
 * @author Noel Antonio
 * @date 03-28-2014
 */

class ReferenceModel extends CFormModel
{
    public $_connection;
    public $current_date;
    public $last_cutoff_date;
    public $next_cutoff_date;
            
    public function __construct() {
        $this->_connection = Yii::app()->db;
        $this->current_date = date('Y-m-d');
    }
    
    public function get_variable_value($param)
    {
        $conn = $this->_connection;
        $query = "SELECT variable_value FROM ref_variables WHERE variable_name = :param";
        $command = $conn->createCommand($query);
        $command->bindParam(':param', $param);
        $result = $command->queryRow();
        return $result['variable_value'];
    }
    
    public function get_message_template($template_id)
    {
        $conn = $this->_connection;
        $query = "SELECT * FROM ref_message_template WHERE message_template_id = :template_id";
        $command = $conn->createCommand($query);
        $command->bindParam(':template_id', $template_id);
        $result = $command->queryRow();
        return $result['message_template'];
    }
    
    public function get_cutoff_dates($trans_type_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM ref_cutoffs 
                    WHERE transaction_type_id = :trans_type_id 
                    AND status = 1
                  ORDER BY cutoff_id DESC
                  LIMIT 1";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':trans_type_id', $trans_type_id);
        $result = $command->queryRow();
        return $result;
    }
    
    public function get_cutoff($trans_type_id)
    {
        $conn = $this->_connection;
        
        $result = ReferenceModel::check_valid_cutoff($trans_type_id);
        $goc_cutoff = ReferenceModel::get_variable_value('GOC_CUTOFF_INTERVAL');
        $unilevel_cutoff = ReferenceModel::get_variable_value('UNILEVEL_CUTOFF_INTERVAL');
        $direct_cutoff = ReferenceModel::get_variable_value('DIRECT_CUTOFF_INTERVAL');
        
        switch($trans_type_id)
        {
            case 1: //GOC
                $interval = " ".$goc_cutoff;
                break;
            case 2: //Unilevel
                $interval = " ".$unilevel_cutoff;
                break;
            case 6: //Direct Endorsement
                $interval = " ".$direct_cutoff;
                break;
            
        }
        
        if($result === false)
        {
            
            //Update last valid cutoff
            $query = "UPDATE ref_cutoffs SET status = 2
                      WHERE transaction_type_id = :trans_type_id
                        AND status = 1";
            $command = $conn->createCommand($query);
            $command->bindParam(':trans_type_id', $trans_type_id);
            $result = $command->execute();
            
           
            if(count($result)>0)
            {
                $query2 = "INSERT INTO ref_cutoffs (transaction_type_id, last_cutoff_date, next_cutoff_date)
                            SELECT
                              rc.transaction_type_id,
                              rc.next_cutoff_date AS last_cutoff_date,
                              DATE_ADD(rc.next_cutoff_date, INTERVAL ".$interval.") AS next_cutoff_date
                            FROM ref_cutoffs rc
                            WHERE rc.transaction_type_id = :trans_type_id AND rc.status = 2
                            ORDER BY rc.cutoff_id DESC LIMIT 1;";

                $command2 = $conn->createCommand($query2);
                $command2->bindParam(':trans_type_id', $trans_type_id);
                $result2 = $command2->execute();

                if(count($result2)>0)
                {
                    return $conn->getLastInsertID();
                }
                else
                {
                    return false;
                }
            }
            
        }
        else
        {
            return $result['cutoff_id'];
        }

        
    }
    
    public function check_valid_cutoff($trans_type_id)
    {
        $result = ReferenceModel::get_cutoff_dates($trans_type_id);
        
        if(count($result)> 0)
        {
            $this->last_cutoff_date = date('Y-m-d',strtotime($result['last_cutoff_date']));
            $this->next_cutoff_date = date('Y-m-d',strtotime($result['next_cutoff_date']));

            if($this->current_date > $this->last_cutoff_date && $this->current_date <= $this->next_cutoff_date)
                return $result['cutoff_id'];
            else
                return false;
        }
    }
    
    public function get_payout_rate($trans_type_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM ref_payout_rate 
                    WHERE transaction_type_id = :trans_type_id 
                    AND status = 1
                    ORDER BY payout_rate_id DESC 
                    LIMIT 1";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':trans_type_id', $trans_type_id);
        $result = $command->queryRow();
        return $result['amount'];
    }
    
    public function get_all_cutoff($trans_type_id)
    {
        $conn = Yii::app()->db;
        
        $query = "SELECT
                    cutoff_id,
                    CONCAT(DATE_FORMAT(DATE_ADD(last_cutoff_date, INTERVAL 1 DAY), '%b %d, %Y'), ' - ', DATE_FORMAT(next_cutoff_date, '%b %d, %Y')) AS cutoff_date
                  FROM ref_cutoffs
                  WHERE transaction_type_id = :trans_type_id
                  ORDER BY cutoff_id DESC";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':trans_type_id', $trans_type_id);
        $result = $command->queryAll();
        return $result;
    }
    
    public function get_cutoff_by_id($cutoff_id)
    {
        $conn = Yii::app()->db;
        
        $query = "SELECT CONCAT(DATE_FORMAT(DATE_ADD(last_cutoff_date, INTERVAL 1 DAY), '%b %d, %Y'), ' - ', DATE_FORMAT(next_cutoff_date, '%b %d, %Y')) AS cutoff_date,
                         last_cutoff_date, 
                         next_cutoff_date
                    FROM ref_cutoffs 
                  WHERE cutoff_id = :cutoff_id";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':cutoff_id', $cutoff_id);
        $result = $command->queryRow();
        return $result;
    }
    
    public function list_cutoffs($trans_type_id)
    {
        
        return CHtml::listData(ReferenceModel::get_all_cutoff($trans_type_id), 'cutoff_id', 'cutoff_date');
    }
    
    public function is_first_cutoff($trans_type_id)
    {
        $conn = Yii::app()->db;
        
        $query = "SELECT MIN(cutoff_id) as cutoff_id
                    FROM ref_cutoffs 
                  WHERE transaction_type_id = :trans_type_id";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':trans_type_id', $trans_type_id);
        $result = $command->queryRow();
        return $result['cutoff_id'];
        /*
        $cutoff_count = $result['cutoff_count'];
        
        if($cutoff_count == 1)
            return true;
        else
            return false;
         * 
         */
    }
    
    public function toggle_job_scheduler($status)
    {
        $conn = $this->_connection;
        $trx = $conn->beginTransaction();
        $query = "UPDATE ref_variables SET variable_value = :status
                  WHERE variable_name = 'JOB_SCHEDULER';";
        $command = $conn->createCommand($query);
        $command->bindParam(':status', $status);
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
    
    public function get_schedule_variables()
    {
        $conn = $this->_connection;
        $query = "SELECT * FROM ref_variables WHERE (variable_id > 5 AND variable_id < 10) OR variable_id = 16";
        $command = $conn->createCommand($query);
        $result = $command->queryAll();
        return $result;
    }
    
    public function get_rates_variables()
    {
        $conn = $this->_connection;
        $query = "SELECT * FROM ref_variables WHERE variable_id >= 10 AND variable_id <= 15";
        $command = $conn->createCommand($query);
        $result = $command->queryAll();
        return $result;
    }
    
    public function get_payout_rates()
    {
        $conn = $this->_connection;
        $query = "SELECT p.payout_rate_id, 
                        p.transaction_type_id,
                        t.transaction_type_name,
                        p.amount                        
                    FROM ref_payout_rate p
                    INNER JOIN ref_transaction_types t ON p.transaction_type_id = t.transaction_type_id
                    WHERE p.status = 1";
        $command = $conn->createCommand($query);
        $result = $command->queryAll();
        return $result;
    }
    
    public function get_payout_rates_by_id($id)
    {
        $conn = $this->_connection;
        $query = "SELECT p.payout_rate_id, 
                        p.transaction_type_id,
                        t.transaction_type_name,
                        p.amount                        
                    FROM ref_payout_rate p
                    INNER JOIN ref_transaction_types t ON p.transaction_type_id = t.transaction_type_id
                    WHERE p.payout_rate_id = :payout_rate_id";
        $command = $conn->createCommand($query);
        $command->bindParam(':payout_rate_id', $id);
        $result = $command->queryRow();
        return $result;
    }
    
    public function get_variables_by_id($variable_id)
    {
        $conn = $this->_connection;
        $query = "SELECT * FROM ref_variables WHERE variable_id = :variable_id";
        $command = $conn->createCommand($query);
        $command->bindParam(':variable_id', $variable_id);
        $result = $command->queryRow();
        return $result;
    }
    
    public function update_ref_variables($id,$value)
    {
        $conn = $this->_connection;        
        $trx = $conn->beginTransaction();
        
        $query = "UPDATE ref_variables SET variable_value = :variable_value
                    WHERE variable_id = :variable_id";
        $command = $conn->createCommand($query);
        $command->bindParam(':variable_value', $value);
        $command->bindParam(':variable_id', $id);
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
    
    public function update_payout_rate($id,$trans_type_id,$value)
    {
        $conn = $this->_connection;        
        $trx = $conn->beginTransaction();
        
        $query = "UPDATE ref_payout_rate SET status = 2
                    WHERE payout_rate_id = :payout_rate_id";
        $command = $conn->createCommand($query);
        $command->bindParam(':payout_rate_id', $id);
        $command->execute();
        
        try
        {
            if(!$this->hasErrors())
            {
                $query2 = "INSERT INTO ref_payout_rate (transaction_type_id, amount, created_by_id)
                            VALUES (:transaction_type_id, :amount, :created_by_id)";
                $command2 = $conn->createCommand($query2);
                $command2->bindParam(':transaction_type_id', $trans_type_id);
                $command2->bindParam(':amount', $value);
                $command2->bindParam(':created_by_id', Yii::app()->user->getId());
                $command2->execute();
                
                if(!$this->hasErrors())
                    $trx->commit();
                else
                    $trx->rollback();
            }
        }
        catch(PDOException $e)
        {
            $trx->rollback();
        }
    }
    
    public function verify_payout_rate($id,$trans_type_id,$amount)
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM ref_payout_rate
                    WHERE payout_rate_id = :payout_rate_id
                        AND transaction_type_id = :trans_type_id
                        AND amount = :amount";
        $command = $conn->createCommand($query);
        $command->bindParam(':payout_rate_id', $id);
        $command->bindParam(':trans_type_id', $trans_type_id);
        $command->bindParam(':amount', $amount);
        $result = $command->queryAll();
        if(count($result)>0)
            return true;
        else
            return false;
    }
}
?>
