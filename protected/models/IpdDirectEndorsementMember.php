<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 03-29-2014
------------------------*/

class IpdDirectEndorsementMember extends CFormModel
{
    public $_connection;
    
    public function __construct()
    {
        $this->_connection = Yii::app()->db;
    }
    
    public function getIpdDirectEndorsement($member_id)
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    d.direct_endorsement_id,
                    CONCAT(md.last_name, ', ', md.first_name, ' ', md.middle_name) AS member_name,
                    DATE_FORMAT(d.date_created,'%M %d, %Y') AS date_created,
                    DATE_FORMAT(d.date_approved,'%M %d, %Y') AS date_approved,
                    CONCAT(md2.last_name, ', ', md2.first_name, ' ', md2.middle_name) AS approved_by,
                    DATE_FORMAT(d.date_claimed,'%M %d, %Y') AS date_claimed,
                    CONCAT(md3.last_name, ', ', md3.first_name, ' ', md3.middle_name) AS claimed_by,
                    d.status
                  FROM distributor_endorsements d
                    LEFT OUTER JOIN member_details md
                      ON d.distributor_id = md.member_id
                    LEFT OUTER JOIN member_details md2
                      ON d.approved_by_id = md2.member_id
                    LEFT OUTER JOIN member_details md3
                      ON d.claimed_by_id = md3.member_id
                  WHERE d.endorser_id = :member_id
                  ORDER BY d.date_created DESC;";
        
        $command =  $conn->createCommand($query);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        
        return $result;
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
