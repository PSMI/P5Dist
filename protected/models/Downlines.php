<?php

/*
 * @author : owliber
 * @date : 2014-02-03
 */

class Downlines extends CFormModel
{
    public $_connection;
    public $endorser_id;
    public $member_id;
    public $date_from;
    public $date_to;
    
    public function __construct() {
        $this->_connection = Yii::app()->db;
    }
    
    public function levels($member_id)
    {
        $conn = $this->_connection;
//        if(count($member_id)>1)
//            $member_id = implode(',',$member_id);
        
        $query = "SELECT
                    member_id AS downline
                  FROM members m
                  WHERE m.upline_id IN ($member_id);";
        
        $command = $conn->createCommand($query);
        //$command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();        
        
        return $result;
    }
    
    public function firstLevel()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    member_id AS downline
                  FROM members m
                  WHERE m.upline_id = :member_id;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        $result = $command->queryAll();
        
        return $result;  
    }
    
    public function officialFirstLevel()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    member_id AS downline
                  FROM members
                  WHERE upline_id = :member_id
                  AND placement_status = 1
                  ORDER BY placement_date ASC;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        $result = $command->queryAll();
        
        return $result;  
    }
    
    public function firstFive()
    {
    	$conn = $this->_connection;
	$query = "SELECT
                    member_id AS downline
                  FROM members m
                  WHERE m.upline_id = :member_id
                    LIMIT 5;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        $result = $command->queryAll();        
        
        return $result;

    }    
    
    public function getDownlinesWCompleteDownlines($lists)
    {
        
        $member_lists = implode(',',$lists);
        $conn = $this->_connection;
        $query = "SELECT
                    m.member_id as downline
                  FROM members m
                    LEFT JOIN members m1
                      ON m.member_id = m1.upline_id
                  WHERE m.member_id IN ($member_lists)
                  GROUP BY m.member_id
                  HAVING COUNT(m.upline_id) = 5";        
         
        $command = $conn->createCommand($query);
        $result = $command->queryAll();        
        
        return $result;
    }
    
    public function getDirectEndorsed()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    member_id AS downline
                  FROM members m
                  WHERE m.upline_id = :member_id
                    OR m.endorser_id = :member_id;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        $result = $command->queryAll();
        
        return $result;
    }
    public function nextLevel($member_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    m.member_id AS downline
                 FROM members m
                  WHERE m.upline_id IN (SELECT
                    m1.member_id
                  FROM members m1
                  WHERE m1.upline_id IN ($member_id) )";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        
        return $result;
    }
    
    public function nextLessFiveLevel($member_ids)
    {
        $conn = $this->_connection;
        $query = "SELECT
                    m.member_id AS downline
                  FROM members m
                    LEFT JOIN members m2 ON m.member_id = m2.upline_id
                  WHERE m.member_id IN (
                      SELECT
                        m1.member_id
                      FROM members m1
                      WHERE m1.upline_id IN ($member_ids)
                    )
                  GROUP BY m.member_id
                  HAVING COUNT(m.upline_id) < 5";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_ids);
        $result = $command->queryAll();
        
        return $result;
    }
    
    /**
     * This model function is used to retrieve the downline information.
     * @author Noel Antonio
     * @date 02/11/2014
     * @param string $member_ids set of member id separated by comma
     * @return array resultset
     */
    public function downlineInfo($member_ids)
    {
        $conn = $this->_connection;
        
        $query = "SELECT 
                    m.member_id,
                    md.last_name, md.first_name, md.middle_name,
                    m.date_joined as date_enrolled,
                    m.upline_id,
                    m.endorser_id,
                    m.placement_date
                  FROM members m
                    INNER JOIN member_details md ON m.member_id = md.member_id
                  WHERE m.member_id IN ($member_ids)
                    AND m.placement_status = 1
                  ORDER BY m.placement_date";
        
        $command = $conn->createCommand($query);
        $result = $command->queryAll();
        
        return $result;
    }
    
    
    /**
     * This model function is used to retrieve the total count of downlines.
     * @author Noel Antonio
     * @date 02/11/2014
     * @param int $member_id
     * @return int count of row
     */
    public function getDownlineCount($member_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT count(member_id) as count FROM members
            WHERE upline_id = :member_id AND placement_status = 1";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryRow();
        
        return $result["count"];
    }
    
    /**
     * This model function is used to retrieve the total count of unilevel downlines.
     * @author Noel Antonio
     * @date 02/11/2014
     * @param int $member_id
     * @return int count of row
     */
    public function getUnilevelCount($member_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT count(member_id) as count FROM members
            WHERE endorser_id = :member_id AND placement_status = 1";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryRow();
        
        return $result["count"];
    }
    
    
    /**
     * This model function is used to retrieve all direct endorsements
     * entry of the member.
     * @author Noel Antonio
     * @date 02/11/2014
     * @param int $member_id
     * @return array resultset
     */
    public function directEndorse($member_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    member_id AS downline
                  FROM members m
                  WHERE m.endorser_id = :member_id AND placement_status = 1
                  ORDER BY placement_date ASC;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        
        return $result;
        
    }
    
    public function findDirectEndorseByDate()
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    member_id AS downline
                  FROM members m
                  WHERE m.endorser_id = :member_id 
                    AND m.placement_status = 1
                    AND m.placement_date > :date_from 
                   AND m.placement_date <= :date_to
                 ";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $this->member_id);
        $command->bindParam(':date_from', $this->date_from);
        $command->bindParam(':date_to', $this->date_to);
        $result = $command->queryAll();
        
        return $result;
        
    }
    
    /**
     * This model function is used to retrieve all direct endorsements
     * entry of the member.
     * @author Noel Antonio
     * @date 02/11/2014
     * @param int $member_id
     * @return array resultset
     */
    public function getIPDDirectEndorse($member_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    member_id AS downline
                  FROM members m
                  WHERE m.endorser_id = :member_id AND placement_status = 1
                  AND m.account_type_id = 5
                  ORDER BY placement_date ASC;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        
        return $result;
        
    }
}
?>
